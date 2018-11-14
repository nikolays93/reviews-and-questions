<?php

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

add_shortcode( 'reviews_and_questions', array($this, 'shortcode') );
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
