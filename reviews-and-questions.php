<?php
/*
Plugin Name: R&Q (Reviews And Questions)
Description: Add site reviews and questions support.
Plugin URI: https://github.com/nikolays93/reviews-and-questions.git
Author: NikolayS93
Author URI: https://vk.com/nikolays_93
Version: 1.0 alpha
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*
    Copyright (C) Year  Author  Email

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace RQ;

define('RQ_TYPE', 'review');
define('RQ_DIR', plugin_dir_path( __FILE__ ) );
define('RQ_PAGE_SLUG', 'reviews_and_questions' );
define('RQ_META_NAME', '_review_data' );
define('RQ_HOOK_NAME', 'RQ' );

include_once RQ_DIR . 'class/class-wp-form-render.php';
include_once RQ_DIR . 'class/class-wp-admin-page-render.php';
include_once RQ_DIR . 'class/class-wp-post-boxes.php';

function _review_fields(){
    $review_fields = array(
        array(
            'id' => 'your-name',
            'type' => 'text',
            'label' => 'Имя',
            ),
        array(
            'id' => 'your-phone',
            'type' => 'text',
            'label' => 'Телефон',
            ),
        array(
            'id' => 'your-email',
            'type' => 'text',
            'label' => 'Email',
            ),
        array(
           'id' => 'your_city',
           'type' => 'text',
           'label' => 'Город',
           ),
        // array(
        //  'id' => 'your_review_rating',
        //  'type' => 'text',
        //  'label' => 'Рэйтинг',
        //  ),
        array(
         'id' => 'your-work',
         'type' => 'text',
         'label' => 'Организация',
         ),
        // array(
        //  'id' => 'your-custom',
        //  'type' => 'text',
        //  'label' => '',
        //  ),
        // array(
        //  'id' => 'your-custom2',
        //  'type' => 'text',
        //  'label' => '',
        //  )
        );
    return $review_fields;
}

include_once RQ_DIR . 'inc/post-type.php';
include_once RQ_DIR . 'inc/admin-page.php';
// include_once RQ_DIR . 'inc/metabox.php';
include_once RQ_DIR . 'inc/create-post.php';
include_once RQ_DIR . 'inc/shortcodes.php';


// todo: review form shortcode
// editble forms

class DTReview
{

	/**
	 * Front functions
	 */
	// static public function get_review_options($post_id=null){
	// 	if(! $post_id ){
	// 		global $post;

	// 		if( isset($post->ID) )
	// 			$post_id = $post->ID;
	// 		else
	// 			return false;
	// 	}

	// 	$fields = self::_review_fields();

	// 	$result =array();
	// 	foreach ($fields as $field) {
	// 		$key = str_replace('_your_', '', $field['id']);
	// 		$result[$key] = get_post_meta( $post_id, $field['id'], true );
	// 	}

	// 	return $result;
	// }
}
// new DTReview();


// u may use DTReview::get_review_options( get_post_id() ); in content template