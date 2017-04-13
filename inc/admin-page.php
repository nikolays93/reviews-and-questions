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
function admin_review_fields( $fields, $option_name ){
    $defaults = array('your-name', 'your-phone', 'your-email');
    foreach ($fields as &$field) {
        $field['type'] = 'checkbox';
        if( in_array($field['id'], $defaults) )
            $field['default'] = 'on';
            
    }
    return $fields;
}
add_filter( RQ_PAGE_SLUG . '_columns', function(){return 2;} );
$page->add_metabox( 'fields_side', 'Fields', 'RQ\metabox_body', 'side');
function metabox_body(){
    add_filter( 'dt_admin_options', 'RQ\admin_review_fields', 5, 2 );
    $active = get_option( RQ_PAGE_SLUG );
    if( isset($active['inputs']) )
        $active = $active['inputs'];
    WPForm::render(
        apply_filters( 'dt_admin_options', _review_fields(), RQ_PAGE_SLUG . '[inputs]'),
        $active,
        true
        );
}

// add_action( $page_slug . '_inside_side_container', 'submit_button', 20 );