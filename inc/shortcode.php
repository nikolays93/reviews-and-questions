<?php

class DT_Reviews_Questions_SC extends DT_Reviews_Questions
{
  function __construct()
  {
      parent::$tabs[] = array('SC_TAB', array($this, 'shortcode_tab'), 'Archive Page Shortcode');

      add_shortcode( 'reviews_and_questions', array($this, 'shortcode') );
  }

  function shortcode_tab(){
  	?>
  	<p><label for='raq-shortcode'> Вставьте этот код на страницу, где хотите показать отзывы: </label></p>

  	<p>Вы также можете скачать папку wp-content/plugins/reviews-and-questions/<strong>template-parts</strong> к себе в тему для предопределения шаблона</p>
    <div class="postbox-container normal-container">
        <div class="postbox">
          <h2 class="hndle"><span>Reviews And Questions Archive Shortcode</span></h2>
          <div class="inside">
            <?php echo "<textarea id='raq-shortcode' class='widefat' rows=8>[reviews_and_questions]</textarea>"; ?>
          </div>
        </div>
      </div>
      <?php
  }

  function get_template($template_args = array()){
  	extract($template_args);

  	$path = 'template-parts/content-review.php';
  	if( $located = locate_template(array($path)) ){
  		require $located;
  	}
  	else {
  		require RQ_DIR . '/' . $path;
  	}
  }

  function shortcode( $atts ) {
  	extract(shortcode_atts( array(
  		'per_page' => '-1',
    'order' => 'DESC', // ASC
    ), $atts ) );

  	$query = new \WP_Query( array(
  		'post_type' => self::POST_TYPE,
  		'posts_per_page' => $per_page,
  		'post_status' => apply_filters( 'reviews_and_questions_post_status', 'publish'),
  		'order'   => $order
  		) );

  	ob_start();
  	while ( $query->have_posts() ) {
  		$query->the_post();

  		$this->get_template();
  	}
  	return ob_get_clean();
  }

}
new DT_Reviews_Questions_SC();