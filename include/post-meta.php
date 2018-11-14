<?php

namespace NikolayS93\Reviews;

use \NikolayS93\WP_Post_Metabox as Metabox;
use NikolayS93\WPAdminForm\Form as Form;

function reorder_boxes() {
    global $post, $wp_meta_boxes;

    if( Utils::get_post_type() != $post->post_type ) return false;

    do_meta_boxes(get_current_screen(), 'advanced', $post);
    unset($wp_meta_boxes[ get_post_type($post) ]['advanced']);
}

function init_metaboxes() {
    $screen = get_current_screen();
    if( !isset($screen->post_type) || Utils::get_post_type() != $screen->post_type ) return false;

    $arActive = Utils::get('fields');
    if( !empty($arActive) ) {
        $boxes = new Metabox( Utils::get_post_type() );
        $boxes->add_box('Отзыв', __NAMESPACE__ . '\metabox_render', false, 'high' );

        $fields = array();
        $active = array_keys( $arActive );

        foreach ($active as $active_name) {
            $fields[] = '_' . $active_name;
        }

        $boxes->add_fields( $fields );
    }
}

function metabox_render() { // $post, $data
    $arActive = Utils::get('fields');

    if( !empty($arActive) ) {
        $active = array_keys( $arActive );
        $fields = Utils::get_fields();

        /**
         * Pass active only
         */
        foreach ($fields as $key => &$field) {
            if( !in_array($field['id'], $active) ) unset($fields[$key]);

            $field['id'] = '_' . $field['id'];
        }

        $form = new Form( $fields, array(
            'is_table' => true,
            'postmeta' => true,
        ) );

        $form->display();

        // wp_nonce_field( $data['args'][0], $data['args'][0].'_nonce' );
    }
    else {
        echo 'Параметры отзыва не установлены и/или не требуются';
    }
}

function custom_postexerpt_box() {
    remove_meta_box( 'postexcerpt', Utils::get_post_type(), 'normal' );
    add_meta_box('admin_answer_postexcerpt', __('Ответ администратора', DOMAIN), __NAMESPACE__ . '\custom_postexerpt_box_content', Utils::get_post_type(), 'normal');
}

function custom_postexerpt_box_content() {
    global $post;

    printf('<label class="screen-reader-text" for="excerpt">%s</label>', __('Excerpt', DOMAIN));
    echo "<textarea rows='1' cols='40' name='excerpt' tabindex='6' id='excerpt'>{$post->post_excerpt}</textarea>";
}
