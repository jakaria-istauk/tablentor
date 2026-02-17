<?php
/**
 * Plugin Name: Tablentor
 * Description: Create tables effortlessly in Elementor using our Table Widget. You can either build tables manually by adding rows and columns or render them dynamically from a CSV file
 
 * Author: Jakaria Istauk
 * Version: 3.0.1
 * Author URI: https://profiles.wordpress.org/jakariaistauk/
 * Text Domain: tablentor
 * Domain Path: /languages
 *
 * Comparison Table is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Comparison Table is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

namespace Jakaria\Tablentor;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main class for the plugin
 * @package Plugin
 * @author Jakaria <jakariamd35@gmail.com>
 */
final class Plugin {
    
    public static $_instance;

    public function __construct() {
        $this->include();
        $this->define();
        $this->hook();
    }

    /**
     * Includes files
     */
    public function include() {
        require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
    }

    /**
     * Define variables and constants
     */
    public function define() {
        // constants
        define( 'CMPRTBL', __FILE__ );
        define( 'CMPRTBL_DIR', dirname( CMPRTBL ) );
        define( 'CMPRTBL_ASSETS', plugins_url( 'assets/', CMPRTBL ) );

        define( 'CMPRTBL_PREFIX', 'CMPRTBL' );
        define( 'CMPRTBL_FILE', __FILE__ );
        define( 'CMPRTBL_BASENAME', plugin_basename( __FILE__ ) );
        define( 'CMPRTBL_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
        define( 'CMPRTBL_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
        define( 'CMPRTBL_ASSET_DIR', trailingslashit( plugin_dir_url( __FILE__ ) . 'assets/' ) );
        define( 'CMPRTBL_VERSION', '3.0.1' );
        define( 'CMPRTBL_DEV_MODE', file_exists( CMPRTBL_PATH . '/.git' ) );
    }

    /**
     * Hooks
     */
    public function hook() {

        $admin = new Admin;
        add_action( 'plugins_loaded', [ $admin, 'i18n'] );
        add_action( 'elementor/editor/after_enqueue_styles', [ $admin, 'editor_enqueue_scripts'] );

        $front = new Front;
        add_action( 'wp_head', [ $front, 'head' ] );

        $widgets = new Widgets;
        // add_action( 'elementor/elements/categories_registered', [ $widgets, 'register_category' ] );
        add_action( 'elementor/widgets/widgets_registered', [ $widgets, 'register_widgets' ] );
        add_action( 'elementor/frontend/after_enqueue_styles', [ $widgets, 'enqueue_styles' ] );
        add_action( 'elementor/frontend/after_enqueue_scripts', [ $widgets, 'enqueue_scripts' ] );

        $blocks = new Blocks;
        add_action( 'init', [ $blocks, 'register_blocks' ] );
    }
 
    /**
     * Cloning is forbidden.
     */
    public function __clone() { }

    /**
     * Unserializing instances of this class is forbidden.
     */
    public function __wakeup() { }

    /**
     * Instantiate the plugin
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}

Plugin::instance();
