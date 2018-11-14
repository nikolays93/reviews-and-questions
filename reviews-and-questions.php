<?php

/*
 * Plugin Name: Reviews And Questions
 * Plugin URI: https://github.com/nikolays93/reviews-and-questions
 * Description: Add site reviews and questions support.
 * Version: 1.2
 * Author: NikolayS93
 * Author URI: https://vk.com/nikolays_93
 * Author EMAIL: NikolayS93@ya.ru
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: reviews_and_questions
 * Domain Path: /languages/
 */

namespace NikolayS93\Reviews;

use NikolayS93\WPAdminPage as Admin;
use NikolayS93\WPAdminPage\Util as Util;

if ( !defined( 'ABSPATH' ) ) exit('You shall not pass');

require_once ABSPATH . "wp-admin/includes/plugin.php";

if (version_compare(PHP_VERSION, '5.3') < 0) {
    throw new \Exception('Plugin requires PHP 5.3 or above');
}

class Plugin
{
    const HOOK = 'rq';
    const DEFAULT_SHORTOCDE = 'reviews_and_questions';
    const DEFAULT_POST_TYPE = 'review';
    const DEFAULT_META_NAME = '_review';

    protected static $data;
    protected static $options;

    private function __construct() {}
    private function __clone() {}

    /**
     * Get option name for a options in the Wordpress database
     */
    public static function get_option_name()
    {
        return apply_filters("get_{DOMAIN}_option_name", DOMAIN);
    }

    /**
     * Define required plugin data
     */
    public static function define()
    {
        self::$data = get_plugin_data(__FILE__);

        if( !defined(__NAMESPACE__ . '\DOMAIN') )
            define(__NAMESPACE__ . '\DOMAIN', self::$data['TextDomain']);

        if( !defined(__NAMESPACE__ . '\PLUGIN_DIR') )
            define(__NAMESPACE__ . '\PLUGIN_DIR', __DIR__);

        __('Reviews And Questions', DOMAIN);
        __('Add site reviews and questions support.', DOMAIN);
    }

    /**
     * include required files
     */
    public static function initialize()
    {
        load_plugin_textdomain( DOMAIN, false, basename(PLUGIN_DIR) . '/languages/' );

        $autoload = PLUGIN_DIR . '/vendor/autoload.php';
        if( file_exists($autoload) ) include $autoload;

        require PLUGIN_DIR . '/include/utils.php';
        require PLUGIN_DIR . '/include/register.php';
        require PLUGIN_DIR . '/include/post-meta.php';

        require PLUGIN_DIR . '/include/wpcf7.php';
        // require PLUGIN_DIR . '/include/shortcode.php';

        add_action('init', __NAMESPACE__ . '\register_review_type');
        add_action('contextual_help', __NAMESPACE__ . '\review_help', 10, 3);

        add_action('edit_form_after_title', __NAMESPACE__ . '\reorder_boxes');
        add_action( 'load-post.php',     __NAMESPACE__ . '\init_metaboxes' );
        add_action( 'load-post-new.php', __NAMESPACE__ . '\init_metaboxes' );
        add_action( 'add_meta_boxes', __NAMESPACE__ . '\custom_postexerpt_box', 99 );
    }

    static function uninstall() { delete_option( self::get_option_name() ); }
    static function activate()
    {
        add_option(
            self::get_option_name(),
            array(
                'fields' => array(
                    'your-name' => 'on',
                    'your-phone' => 'on',
                    'your-email' => 'on',
                ),
            )
        );
    }

    // public static function _admin_assets()
    // {
    // }

    public static function admin_menu_page()
    {
        $page = new Admin\Page(
            Utils::get_option_name(),
            __('R&Q (Reviews And Questions)', DOMAIN),
            array(
                'parent'      => 'options-general.php',
                'menu'        => __('Reviews and Questions', DOMAIN),
                // 'validate'    => array(__CLASS__, 'validate_options'),
                'permissions' => 'manage_options',
                'columns'     => 2,
            )
        );

        // $page->set_assets( array(__CLASS__, '_admin_assets') );

        $page->set_content( function() {
            Utils::get_admin_template('menu-page.php', false, $inc = true);
        } );

        $page->add_section( new Admin\Section(
            'wpcf7',
            __('Contact Form 7', DOMAIN),
            function() {
                Utils::get_admin_template('section.php', false, $inc = true);
            }
        ) );

        // $page->add_section( new Admin\Section(
        //     'ownform',
        //     __('Reviews And Questions', DOMAIN),
        //     function() {
        //         Utils::get_admin_template('section1.php', false, $inc = true);
        //     }
        // ) );

        // $page->add_section( new Admin\Section(
        //     'archive',
        //     __('Archive Page Shortcode', DOMAIN),
        //     function() {
        //         Utils::get_admin_template('section2.php', false, $inc = true);
        //     }
        // ) );

        $metabox1 = new Admin\Metabox(
            'ReviewFields',
            __('Review Fields', DOMAIN),
            function() {
                Utils::get_admin_template('metabox.php', false, $inc = true);
            },
            $position = 'side',
            $priority = 'high'
        );

        $page->add_metabox( $metabox1 );

        // $metabox2 = new Admin\Metabox(
        //     'metabox2',
        //     __('metabox2', DOMAIN),
        //     function() {
        //         Utils::get_admin_template('metabox2.php', false, $inc = true);
        //     },
        //     $position = 'side',
        //     $priority = 'high'
        // );

        // $page->add_metabox( $metabox2 );
    }
}

Plugin::define();

// register_activation_hook( __FILE__, array( __NAMESPACE__ . '\Plugin', 'activate' ) );
// register_uninstall_hook( __FILE__, array( __NAMESPACE__ . '\Plugin', 'uninstall' ) );
// register_deactivation_hook( __FILE__, array( __NAMESPACE__ . '\Plugin', 'deactivate' ) );

add_action( 'plugins_loaded', array( __NAMESPACE__ . '\Plugin', 'initialize' ), 10 );
add_action( 'plugins_loaded', array( __NAMESPACE__ . '\Plugin', 'admin_menu_page' ), 10 );
