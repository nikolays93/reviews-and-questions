<?php
/*
Plugin Name: R&Q (Reviews And Questions)
Plugin URI: https://github.com/nikolays93/reviews-and-questions
Description: Add site reviews and questions support.
Version: 1.1
Author: NikolayS93
Author URI: https://vk.com/nikolays_93
Author EMAIL: nikolayS93@ya.ru
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) )
  exit; // disable direct access

class DT_Reviews_Questions {
  const SETTINGS = 'reviews_and_questions';
  const METANAME = '_review_data';
  const POST_TYPE = 'review';
  const HOOK = 'rq';

  static public $settings = array();
  static public $post_type = array();
  static public $tabs = array();

  /* Singleton Class */
  private function __clone() {}
  private function __wakeup() {}

  private static $instance = null;
  public static function get_instance() {
    if ( ! isset( self::$instance ) )
      self::$instance = new self;

    return self::$instance;
  }

  public static function activate(){
    add_option( self::SETTINGS, array() );
  }

  public static function uninstall(){
    delete_option(self::SETTINGS);
  }

  /************************************* Initialize *************************************/
  private function __construct() {
    self::define_constants();
    self::load_classes();
    add_action('init', array($this, 'register_post_type'));
    add_action('contextual_help', array($this, 'review_help'), 10, 3);

    if(!is_admin())
      return;

    add_action('edit_form_after_title', array($this, 'reorder_boxes'));

    add_action( 'load-post.php',     array($this, 'init_metaboxes') );
    add_action( 'load-post-new.php', array($this, 'init_metaboxes') );
    add_action( 'add_meta_boxes', array($this, 'custom_postexerpt_box'), 99 );
  }

  private static function define_constants(){
    define('RQ_DIR', rtrim(plugin_dir_path( __FILE__ ), '/'));
  }
  private static function load_classes(){
    require_once RQ_DIR . '/class/class-wp-admin-page-render.php';
    require_once RQ_DIR . '/class/class-wp-form-render.php';
    require_once RQ_DIR . '/class/class-wp-post-boxes.php';
  }

  /********************************* Post Type Functions ********************************/
  function register_post_type(){
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
    register_post_type(self::POST_TYPE, $args);
  }

  function review_help($contextual_help, $screen_id, $screen) {
    if ('edit-'.self::POST_TYPE == $screen->id || self::POST_TYPE == $screen->id ) {
      $contextual_help =
      '<h4>Используйте ContactForm7</h4><p>Если добавить в форму [text] с именем dp_review и дать ему любое значение (При этом его можно скрыть при помощи css) помимо отправленного сообщения, система создаст "Запись" типа "Отзыв".</p>

      <p>Не работает если опция выключена. При выключении опции данные скрываются (НЕ Удаляются из базы).</p>

      <label><strong>К примеру:</strong></label>
      <p>[text* your-name][textarea your-message][text dp_review class:hide-me "text с именем dp_review"]</p>';
    }
    return $contextual_help;
  }

  /************************************** MetaBoxes *************************************/
  static public function get_instalized_inputs(){
    $active = WPForm::active(self::$settings, '_review_data', true);
    foreach ($active as $key => $value) {
      if($value == 'false' || $value == 'off' || ! $value )
        unset($active[$key]);
    }
    if( ! is_array($active) || sizeof($active) < 1 )
      return false;

    return array_keys($active);
  }

  function reorder_boxes(){
    global $post, $wp_meta_boxes;

    if( $post->post_type == self::POST_TYPE ){
      do_meta_boxes(get_current_screen(), 'advanced', $post);
      unset($wp_meta_boxes[get_post_type($post)]['advanced']);
    }
  }

  function init_metaboxes(){
    $screen = get_current_screen();
    if( !isset($screen->post_type) || $screen->post_type != self::POST_TYPE )
      return false;

    $boxes = new WPPostBoxes();
    $boxes->add_box('Отзыв', array($this, 'metabox_render'), false, 'high' );
    $boxes->add_fields( self::METANAME );
  }

  function metabox_render($post, $data){
    if( $installed = self::get_instalized_inputs() ){
      $fields = include RQ_DIR . '/inc/inputs.php';
      foreach ($fields as $key => $field) {
        $fields[$key]['name'] = $fields[$key]['id'];
        if( !in_array($field['id'], $installed) )
          unset($fields[$key]);
      }

      WPForm::render( $fields,
        WPForm::active(self::METANAME, false, true, true),
        true,
        array('admin_page' => self::METANAME)
        );
      wp_nonce_field( $data['args'][0], $data['args'][0].'_nonce' );
    }
    else {
      echo 'Параметры отзыва не установлены или не требуются';
    }
  }

  function custom_postexerpt_box(){
    remove_meta_box( 'postexcerpt', self::POST_TYPE, 'normal' );
    add_meta_box('admin_answer_postexcerpt', __( 'Ответ администратора' ), function(){
      global $post;

      echo "<label class='screen-reader-text' for='excerpt'> {_('Excerpt')} </label>
      <textarea rows='1' cols='40' name='excerpt' tabindex='6' id='excerpt'>{$post->post_excerpt}</textarea>";
    }, self::POST_TYPE, 'normal');
  }

  function init() {
    self::$settings = get_option( self::SETTINGS, array() );

    add_filter( self::SETTINGS . '_columns', function(){return 2;} );

    require_once RQ_DIR . '/inc/wpcf7.php';
    require_once RQ_DIR . '/inc/self-form.php';
    require_once RQ_DIR . '/inc/shortcode.php';

    $page_args = array(
      'parent' => 'options-general.php',
      'title'  => __('R&Q (Reviews And Questions)'),
      'menu'   => __('Reviews and Questions'),
      );
    $page_renders = array();
    foreach (self::$tabs as $tab) {
      $page_args['tab_sections'][$tab[0]] = $tab[2];
      $page_renders[$tab[0]] = $tab[1];
    }
    $page = new WPAdminPageRender( self::SETTINGS, $page_args, $page_renders );

    $page->add_metabox( 'ReviewFields', 'Review Fields', array($this, 'metabox_review_fields'), $position = 'side');

    $page->set_metaboxes();
  }

  /********************************** Admin Page Render *********************************/
  static function to_admin_fields( $fields ){
    $defaults = array('your-name', 'your-phone', 'your-email');
    foreach ($fields as $i => $field) {
      $fields[$i]['type'] = 'checkbox';

      if( in_array($field['id'], $defaults) )
        $fields[$i]['default'] = 'on';
    }
    return $fields;
  }

  function metabox_review_fields(){
    $fields = include RQ_DIR . '/inc/inputs.php';
    WPForm::render(
      self::to_admin_fields($fields),
      WPForm::active(self::SETTINGS, false, true),
      true,
      array('admin_page' => self::SETTINGS)
      );

    submit_button();
  }
}

add_action( 'plugins_loaded', function(){
  $p = DT_Reviews_Questions::get_instance(); $p->init();
} );
register_activation_hook( __FILE__, array( 'DT_Reviews_Questions', 'activate' ) );
// register_deactivation_hook( __FILE__, array( 'DT_Reviews_Questions', 'deactivate' ) );
register_uninstall_hook( __FILE__, array( 'DT_Reviews_Questions', 'uninstall' ) );