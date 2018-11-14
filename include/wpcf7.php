<?php

namespace NikolayS93\Reviews;

add_action('wpcf7_mail_sent', __NAMESPACE__ . '\create_post_review_from_wpcf', 50, 1 );
function create_post_review_from_wpcf($contact_form) {
    $submission = \WPCF7_Submission::get_instance();
    $posted_data = $submission->get_posted_data();

    if( ! isset($posted_data[Plugin::HOOK]) )
        return;

    $meta = array();
    require_once(ABSPATH .'wp-blog-header.php');

    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        $meta['user_id'] = $current_user->ID;
    }

    $name = $meta[ Utils::get_meta_name() ]['your-name'] = (!empty($posted_data['your-name'])) ?
    sanitize_text_field($posted_data['your-name']) : 'Не указано';
    $meta['user_ip'] = $_SERVER['REMOTE_ADDR'];
    $date = $meta['posted_date'] = date('d.m.Y');

    $fields = include RQ_DIR . '/inc/inputs.php';
    foreach ($fields as $field) {
        $key = $field['id'];
        if( isset( $posted_data[ $key ] ) && $value = $posted_data[ $key ] ){
            $meta[ Utils::get_meta_name() ][ $key ] = sanitize_text_field($value);
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
        'post_type' => self::POST_TYPE,
        'meta_input' => $meta
    );
    wp_insert_post( $new_review, true );

    // $debug = array();
    // $debug[] = $posted_data;
    // $debug[] = $new_review;
    // file_put_contents(__DIR__.'/debug.log', print_r($debug, 1));
}
