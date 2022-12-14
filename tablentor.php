<?php
/**
 * Plugin Name: Tablentor
 * Description: Create table using elementor.
 
 * Author: Jakaria Istauk
 * Version: 1.1.0
 * Author URI: https://jakariaistauk.com
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
        define( 'CMPRTBL_ASSETS', plugins_url( 'assets', CMPRTBL ) );
    }

    /**
     * Hooks
     */
    public function hook() {

        $admin = new Admin;
        add_action( 'plugins_loaded', [ $admin, 'i18n'] );

        $front = new Front;
        add_action( 'wp_head', [ $front, 'head' ] );

        $widgets = new Widgets;
        // add_action( 'elementor/elements/categories_registered', [ $widgets, 'register_category' ] );
        add_action( 'elementor/widgets/widgets_registered', [ $widgets, 'register_widgets' ] );
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