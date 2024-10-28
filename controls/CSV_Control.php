<?php
namespace Jakaria\Tablentor\Controls;

use Elementor\Base_Data_Control;
// Register a custom control to select only CSV
class CSV_Control extends Base_Data_Control {
    public function get_type() {
        return 'tablentor-csv';
    }

    public function enqueue() {
        wp_enqueue_media();
        // Enqueue JavaScript to handle the CSV-only restriction
        wp_enqueue_script('tablentor-csv-control', CMPRTBL_ASSET_DIR . 'admin/js/csv-control.js', ['jquery'], time(), true);
    }

    public function content_template() {
        ?>
        <div class="elementor-control-field">
            <label class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <input type="text" class="csv-url" data-setting="{{ data.name }}" placeholder="Upload CSV" readonly />
                <button class="button csv-upload"><?php esc_html_e('Select CSV', 'plugin-domain'); ?></button>
            </div>
        </div>
        <?php
    }
}
