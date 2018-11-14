<?php

namespace NikolayS93\Reviews;

function register_review_type() {
    $labels = array(
        'name' => 'Отзывы',
        'singular_name' => 'Отзыв',
        'add_new' => 'Добавить отзыв',
        'add_new_item' => 'Добавить новый отзыв',
        'edit_item' => 'Изменить отзыв',
        'new_item' => 'Новый отзыв',
        'view_item' => 'Прочитать отзыв',
        'search_items' => 'Найти отзыв',
        'not_found' =>  'Отзывов не найдено',
        'not_found_in_trash' => 'В корзине нет отзывов',
        'parent_item_colon' => '',
        'menu_name' => 'Отзывы'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'menu_icon'   => 'dashicons-format-status',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes')
    );

    register_post_type(Utils::get_post_type(), $args);
}

function review_help($help, $screen_id, $screen) {
    $post_type = Utils::get_post_type();

    if ( 'edit-' . $post_type == $screen->id || $post_type == $screen->id )
    {
        $help = sprintf('<h4>%s</h4>', __('Используйте ContactForm7', DOMAIN));

        $help.= sprintf('<p>%s</p>', sprintf(
            __('Если добавить в форму %s помимо отправленного сообщения, система создаст "Запись" типа "Отзыв".', DOMAIN),
            '[hidden '.Plugin::HOOK.' "1"]'
        ));

        $help.= sprintf('<br><p>%s</p>', __('Не работает если опция выключена. При выключении опции данные скрываются (НЕ Удаляются из базы).'));
    }

    return $help;
}
