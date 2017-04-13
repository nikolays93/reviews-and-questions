<?php
namespace RQ;

/**
 * Admin Page
 */
$page_render = new WPAdminPageRender(RQ_PAGE_SLUG, array(
    'title' => 'R&Q (Reviews And Questions)',
    'menu' => __('Reviews and Questions','domain'),
    ), 'RQ\raq_page_render' );

function raq_page_render(){
    var_dump(get_option(RQ_PAGE_SLUG));
    return false;
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

/**
 * Meta Boxes
 */
add_filter( RQ_PAGE_SLUG . '_columns', function(){return 2;} );

$page_render->add_metabox( 'fields_side', 'Fields', 'RQ\metabox_body', 'side');
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

$page_render->add_metabox( 'wpcf7-code', 'WP Contact Form 7 Code', 'RQ\wpcf7_shortcodes', 'normal');
function wpcf7_shortcodes(){
    $code = '';
    $active_settings = get_option(RQ_PAGE_SLUG);

    if( isset($active_settings['inputs']) ){
        $realy_active = array();
        foreach ($active_settings['inputs'] as $key => $value) {
            if( $value != 'false' && $value != 'off' && $value != '0' )
                $realy_active[$key] = $value;
        }

        $id_actives = array_keys($realy_active);
        $fields = _review_fields();
        foreach ($fields as $field) {
            if( in_array($field['id'], $id_actives) )
                $code .= "<p>[{$field['type']} {$field['id']} class:form-control placeholder \"{$field['label']}\"]</p>\n";
        }
        $code .= '<p>[textarea your-message class:form-control x6 placeholder "Ваше сообщение"]</p>
        [hidden '.RQ_HOOK_NAME.' class:hidden "leave message"]
        [submit class:btn class:btn-primary "Отправить"]';

        echo "<p><label for='wpcf-template'> Вставьте этот код в шаблон формы 'Contact Form 7' для создания формы отправки сообщения: </label></p><textarea id='wpcf-template' cols=131 rows=8 style='width:100%;'>".esc_html( $code )."</textarea>";
    }

   
    

    // var_dump($realy_active);
    // $active = isset($active_settings['inputs']) ? array_keys($active_settings['inputs']) : array();
    // $fields = array();

    // foreach ($all_fields as $field) {
    //     if( in_array($field['id'], $active) )
    //         $fields[] = $field;
    // }
    // return $fields;

}

$page_render->set_metaboxes();
add_action( RQ_PAGE_SLUG . '_inside_side_container', 'submit_button', 20 );