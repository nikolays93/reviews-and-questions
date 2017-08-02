<?php

class DT_Reviews_Questions_WPCF7 extends DT_Reviews_Questions
{
  function __construct()
  {
      parent::$tabs[] = array('WPCF7_TAB', array($this, 'WPCF7_Tab'), 'Contact Form 7');

      add_action('wpcf7_mail_sent', 'create_post_review_from_wpcf', 50, 1 );
  }

  function WPCF7_Tab(){
    $code = '';

    if( isset(self::$settings['_review_data']) ){
      $realy_active = array();
      foreach (self::$settings['_review_data'] as $key => $value) {
        if( $value != 'false' && $value != 'off' && $value != '0' )
          $realy_active[$key] = $value;
      }

      $id_actives = array_keys($realy_active);
      $fields = include RQ_DIR . '/inc/inputs.php';
      foreach ($fields as $field) {
        if( in_array($field['id'], $id_actives) )
          $code .= "[{$field['type']} {$field['id']} class:form-control placeholder \"{$field['label']}\"]\n";
      }
      $code .= '[textarea your-message class:form-control x6 placeholder "Ваше сообщение"]' . "\n";
      $code .= '[hidden '.self::HOOK.' class:hidden "leave message"]' . "\n";
      $code .= '[submit class:btn class:btn-primary "Отправить"]';

      echo "<p><label for='wpcf-template'> Вставьте этот код в шаблон формы 'Contact Form 7' для создания формы отправки сообщения: </label></p>";
      ?>
      <div class="postbox-container normal-container">
        <div class="postbox">
          <h2 class="hndle"><span>WP Contact Form 7 Code</span></h2>
          <div class="inside">
            <?php echo "<textarea id='wpcf-template' class='widefat' rows=8>".esc_html( $code )."</textarea>"; ?>
          </div>
        </div>
      </div>
      <?php
    }
    if($code == '')
      echo "Установите нужные параметры и сохраните изменения";
  }

  function create_post_review_from_wpcf($contact_form){
    $posted_data = $contact_form->posted_data;
    $submission = \WPCF7_Submission::get_instance();
    $posted_data = $submission->get_posted_data();

    if( ! isset($posted_data[RQ_HOOK_NAME]) )
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

    foreach (_review_fields() as $field) {
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
}
new DT_Reviews_Questions_WPCF7();