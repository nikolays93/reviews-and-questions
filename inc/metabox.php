<?php
namespace RQ;

/**
 * Metabox
 */
add_action('edit_form_after_title', 'RQ\resort_boxes' );

add_action( 'load-post.php',     'RQ\metabox_action' );
add_action( 'load-post-new.php', 'RQ\metabox_action' );

function resort_boxes(){
    global $post, $wp_meta_boxes;

    if( $post->post_type == RQ_TYPE ){
        do_meta_boxes(get_current_screen(), 'advanced', $post);
        unset($wp_meta_boxes[get_post_type($post)]['advanced']);
    }
}
function metabox_action(){
    $screen = get_current_screen();
    if( !isset($screen->post_type) || $screen->post_type != RQ_TYPE )
        return false;

    $boxes = new WPPostBoxes();
    $boxes->add_box('Отзыв', 'RQ\metabox_render', false, 'high' );
    $boxes->add_fields( RQ_META_NAME );
}
function metabox_render($post, $data){
    WPForm::render( _review_fields(), get_post_meta( $post->ID, RQ_META_NAME, true ), true );
    wp_nonce_field( $data['args'][0], $data['args'][0].'_nonce' );
}

/**
 * Custom Excerpt Meta Box
 */
add_action( 'add_meta_boxes' , 'RQ\remove_postexcerpt_box', 99 );
add_action( 'add_meta_boxes',  'RQ\excerpt_box_action' );

function remove_postexcerpt_box(){
    remove_meta_box( 'postexcerpt' , RQ_TYPE, 'normal' );
}
function excerpt_box_action(){
    add_meta_box('raq_postexcerpt', __( 'Ответ администратора' ), 'RQ\excerpt_box_custom', RQ_TYPE, 'normal');
}
function excerpt_box_custom(){
    global $post;

    echo "<label class='screen-reader-text' for='excerpt'> {_('Excerpt')} </label>
    <textarea rows='1' cols='40' name='excerpt' tabindex='6' id='excerpt'>{$post->post_excerpt}</textarea>";
}
