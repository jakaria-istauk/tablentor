<?php
namespace Jakaria\Tablentor;

use \Elementor\Plugin as Elementor_Plugin;
/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Front
 * @author Jakaria Istauk <jakariamd35@gmail.com>
 */
class Widgets{
    
    /**
     * Registers categories for widgets
     *
     * @since 1.0
     */
    public function register_category( $elements_manager ) {
        $elements_manager->add_category(
            'comparison-table',
            [
                'title' => __( 'Comparison Table' ),
                'icon'  => 'eicon-price-table',
            ]
        );
    }

    /**
     * Register THE widgets
     *
     * @since 1.0
     */
    public function register_widgets() {
        $widgets = [
            [
                'path' => CMPRTBL_DIR . "/widgets/basic-table.php",
                'class' => "Jakaria\\Tablentor\\Basic_Table"
            ],
            [
                'path' => CMPRTBL_DIR . "/widgets/table-csv.php",
                'class' => "Jakaria\\Tablentor\\Table_CSV"
            ]
        ];

        foreach ( $widgets as $widget ) {
            if ( file_exists( $widget['path'] ) ) {
                require_once( $widget['path'] );

                if ( class_exists( $widget['class'] ) ) {
                    Elementor_Plugin::instance()->widgets_manager->register( new $widget['class']() );
                }
            }
        }
    }


    public function enqueue_styles(){
        wp_enqueue_style( 'tablentor-data-table', CMPRTBL_ASSET_DIR . "front/css/data-table.min.css", array(), CMPRTBL_VERSION );
    }

    public function enqueue_scripts() {
        wp_enqueue_script( 'tablentor-data-table', CMPRTBL_ASSET_DIR . "front/js/data-table.min.js", [ 'jquery' ], CMPRTBL_VERSION, true );
        wp_enqueue_script( 'tablentor-table-csv', CMPRTBL_ASSET_DIR . "front/js/table-csv.js", [ 'jquery' ], time(), true );
    }
}