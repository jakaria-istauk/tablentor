<?php
namespace Jakaria\Tablentor;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Admin
 * @author Jakaria Istauk <jakariamd35@gmail.com>
 */
class Admin{
    /**
     * Internationalization
     */
    public function i18n() {
        load_plugin_textdomain( 'tablentor', false, CMPRTBL_DIR . '/languages/' );
    }

    public function editor_enqueue_scripts(){
        wp_enqueue_style( 'tablentor-editor', CMPRTBL_ASSET_DIR . "admin/css/editor.css", array(), time() );
	}
}