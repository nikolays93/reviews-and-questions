<?php
namespace RQ;

/**
 * Register Post Type
 */
add_action('init', 'RQ\register_review_type' );
add_action( 'contextual_help', 'RQ\add_review_help_text', 10, 3 );

function register_review_type(){
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
    'has_archive' => true,
    'hierarchical' => false,
    'menu_position' => null,
    'menu_icon'   => 'dashicons-format-status',
    'supports' => array('title', 'editor', 'excerpt', 'custom-fields', 'page-attributes')
    );
    register_post_type(RQ_TYPE, $args);
}
function add_review_help_text($contextual_help, $screen_id, $screen) {
    if ('edit-'.RQ_TYPE == $screen->id || RQ_TYPE == $screen->id ) {
        $contextual_help =
        '<h4>Используйте ContactForm7</h4><p>Если добавить в форму [text] с именем dp_review и дать ему любое значение (При этом его можно скрыть при помощи css) помимо отправленного сообщения, система создаст "Запись" типа "Отзыв".</p>

        <p>Не работает если опция выключена. При выключении опции данные скрываются (НЕ Удаляются из базы).</p>

        <label><strong>К примеру:</strong></label>
        <p>[text* your-name][textarea your-message][text dp_review class:hide-me "text с именем dp_review"]</p>';
    }
    return $contextual_help;
}