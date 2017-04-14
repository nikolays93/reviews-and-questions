<?php
namespace RQ;

global $raq_post_type;

$raq_post_type = array(
    array( 'id' => 'name',
        'type'=> 'text',
        'label' => 'Post type general name',
        'desc' => 'The handle (slug) name of the post type, usually plural.',
        'default' => 'reviews',
        // 'required' => 'true'
        ),
    array( 'id' => 'labels][menu_name', // or label
        'type' => 'text',
        'label' => 'Menu name',
        'desc' => 'display left menu label. same as name (if empty)',
        'default' => 'Reviews'
        ),
    array( 'id' => 'labels][singular_name',
        'type' => 'text',
        'label' => 'Singular name',
        'desc' => 'name for one object of this post type.',
        'placeholder' => 'e.g. article'
        ),
    array( 'id' => 'description',
        'type' => 'textarea',
        'label' => 'Description',
        'desc' => '',
        'cols' => '90',
        'placeholder' => 'Not have description'
        ),
    //labels|name_admin_bar
    array('id' => 'labels][add_new',
        'type' => 'text',
        'placeholder' => 'Добавить отзыв',
        'label' => 'Add new',
        'desc' => 'The add new text. The default is "Add New" for both hierarchical and non-hierarchical post types. When internationalizing this string, please use a gettext context matching your post type.'),
    array('id' => 'labels][add_new_item',
        'type' => 'text',
        'placeholder' => 'Добавить отзыв',
        'label' => 'Add new item',
        'desc' => 'Default is Add New Post/Add New Page'),
    array('id' => 'labels][new_item',
        'type' => 'text',
        'placeholder' => 'Новый отзыв',
        'label' => 'New item',
        'desc' => 'Default is New Post/New Page.'),
    array('id' => 'labels][edit_item',
        'type' => 'text',
        'placeholder' => 'Изменить отзыв',
        'label' => 'Edit item',
        'desc' => 'Default is Edit Post/Edit Page'),
    array('id' => 'labels][view_item',
        'type' => 'text',
        'placeholder' => 'Показать отзыв',
        'label' => 'View item',
        'desc' => 'Default is View Post/View Page.'),
    array('id' => 'labels][all_items',
        'type' => 'text',
        'placeholder' => 'Все отзывы',
        'label' => 'All items',
        'desc' => 'String for the submenu. Default is All Posts/All Pages.'),
    array('id' => 'labels][search_items',
        'type' => 'text',
        'placeholder' => 'Найти отзыв',
        'label' => 'Search items',
        'desc' => 'Default is Search Posts/Search Pages.'),
    array('id' => 'labels][not_found',
        'type' => 'text',
        'placeholder' => 'Отзывы не найдены',
        'label' => 'Not found',
        'desc' => 'Default is No posts found/No pages found.'),
    array('id' => 'labels][not_found_in_trash',
        'type' => 'text',
        'placeholder' => 'Отзывы в корзине не найдены',
        'label' => 'Not found in Trash',
        'desc' => 'Default is No posts found in Trash/No pages found in Trash.'),
    array('id' => 'public',
        'type' => 'checkbox',
        'label' => 'Public',
        'desc' => 'Публичный или используется только технически',
        //'data-hide' => 'publicly_queryable, show_ui'
        ),
    array('id' => 'publicly_queryable',
        'type' => 'checkbox',
        'label' => 'Publicly queryable',
        'desc' => 'Показывать во front\'е',
        ),
    array('id' => 'show_ui',
        'type' => 'checkbox',
        'label' => 'Show UI',
        'desc' => 'Показывать управление типом записи',
        ),
    array('id' => 'show_in_menu',
        'type' => 'checkbox',
        'label' => 'Show in Menu',
        'desc' => 'Показывать ли в админ-меню',
        ),
    // array('id' => 'rewrite',
    //  'type' => 'checkbox',
    //  'label' => 'Rewrite',
    //  'desc' => 'ЧПУ',
    //  // 'default' => 'on'
    //  ),
    array('id' => 'has_archive',
        'type' => 'checkbox',
        'label' => 'Has archive',
        'desc' => 'Поддержка архивной страницы',
        ),
    array('id' => 'hierarchical',
        'type' => 'checkbox',
        'label' => 'Hierarchical',
        'desc' => 'Родители / тексономии',
        ),
    array('id' => 'query_var',
        'type' => 'text',
        'label' => 'Query var',
        'desc' => '$post_type в query_var',
        ),
    array('id' => 'capability_type',
        'type' => 'text',
        'label' => 'Capability as',
        'desc' => 'Права такие же как..',
        // 'default' => 'post'
        ),
    array('id' => 'menu_position',
        'type' => 'number',
        'label' => 'Menu position',
        'desc' => '',
        ),
    array('id' => 'menu_icon',
        'type' => 'text',
        'label' => 'Menu icon',
        // 'default' => 'dashicons-admin-post',
        )
    );

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