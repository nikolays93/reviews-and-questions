<?php
namespace RQ;

/**
 * Admin Page
 */
$page = new WPAdminPageRender(RQ_PAGE_SLUG, array(
    'title' => 'R&Q (Reviews And Questions)',
    'menu' => __('reviews and questions','domain'),
    ), 'RQ\raq_page_render' );

function raq_page_render(){
    echo 'Its page for test classes <Br>';
    var_dump(get_option(RQ_PAGE_SLUG));

    $inputs = array(
    	array(
    		'type' => 'checkbox',
    		'id'   => 'test',
    		'label'=> 'test label',
    		'desc' => 'test description'
    		),
    	array(
    		'type' => 'number',
    		'id'   => 'year',
    		'label'=> 'Year',
    		'before' => 'Now: ',
    		'after' => ' year.',
    		'desc' => 'Second Test field',
    		'default' => '2017'
    		),
    	);
    WPForm::render(
    	apply_filters( 'dt_admin_options', $inputs, RQ_PAGE_SLUG ),
    	get_option( RQ_PAGE_SLUG ),
    	true
    	);
}

// add_filter( RQ_PAGE_SLUG . '_columns', function(){return 2;} );
// $page->add_metabox( 'handle', 'metabox label', 'metabox_body', 'side');
// function metabox_body(){
// 	echo "Its metabox render body function. How easy? ";
// 	submit_button();
// }

// add_action( $page_slug . '_inside_side_container', 'submit_button', 20 );