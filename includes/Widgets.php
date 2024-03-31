<?php
namespace Jakaria\Tablentor;

use \Elementor\Plugin as Elementor_Plugin;
use \Elementor\Controls_Manager;
use \Elementor\Scheme_Typography;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Box_Shadow;
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
     * Registers THE widgets
     *
     * @since 1.0
     */
    public function register_widgets( $widgets_manager ) {       
        $widgets_list = tablentor_widgets_list();

        if( ! empty( $widgets_list ) ){
            foreach( $widgets_list as $key => $widget ){
                if( isset( $widget['path'] ) && '' !== $widget['path'] && isset( $widget['class'] ) && '' !== $widget['path'] ){
                    require_once( $widget['path'] );

                    if( class_exists( $widget['class'] ) ) {
                        $widgets_manager->register( new $widget['class'] );
                    }
                }
            }
        }
    }
}