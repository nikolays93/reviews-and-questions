<?php
namespace RQ;

/**
 * Create Post Review
 */
add_action('wpcf7_mail_sent', 'RQ\create_post_review', 50, 1 );
function create_post_review($contact_form){
    $posted_data = $contact_form->posted_data;
    $submission = \WPCF7_Submission::get_instance();
    $posted_data = $submission->get_posted_data();

    if( isset($posted_data[RQ_HOOK_NAME]) )
        return;

    $meta = array();
    require_once(ABSPATH .'wp-blog-header.php');

    if ( is_user_logged_in() ){
        $current_user = wp_get_current_user();
        $meta['user_id'] = $current_user->ID;
    }

    $name = $meta[RQ_META_NAME]['your-name'] = (!empty($posted_data['your-name'])) ?
        sanitize_text_field($posted_data['your-name']) : 'Не указано';
    $meta['user_ip'] = $_SERVER['REMOTE_ADDR'];
    $date = $meta['posted_date'] = date('d.m.Y');

    $fields = apply_filters( 'active_review_fields', _review_fields() );
    foreach ($fields as $field) {
        $key = $field['id'];
        if( isset( $posted_data[ $key ] ) && $value = $posted_data[ $key ] ){
            $meta[RQ_META_NAME][ $key ] = sanitize_text_field($value);
        }
    }

    $message = ( ! empty($posted_data['your-message'])) ?
        sanitize_text_field($posted_data['your-message']) : '';

    $new_review = array(
        'post_title' => "Сообщение от {$name}. ({$date}г.)",
        'post_content' => $message,
        'post_date' => date('Y-m-d H:i:s'),
        'post_excerpt' => '',
        'post_status' => 'pending',
        'post_type' => RQ_TYPE,
        'meta_input' => $meta
        );
    wp_insert_post( $new_review, true );

    // $debug = array();
    // $debug[] = $posted_data;
    // $debug[] = $new_review;
    // file_put_contents(__DIR__.'/debug.log', print_r($debug, 1));
}