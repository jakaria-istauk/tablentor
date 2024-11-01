<?php
namespace Jakaria\Tablentor;

use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

class Table_CSV extends Widget_Base {

	public $id;

	public function __construct( $data = [], $args = null ) {
	    parent::__construct( $data, $args );
	}

	public function get_script_depends() {
		return [ 'tablentor-basic-table' ];
	}

	public function get_style_depends() {
		return [ 'tablentor-basic-table' ];
	}

	public function get_name() {
			return 'tablentor-table-csv';
	}

	public function get_title() {
		return __( 'Table CSV', 'tablentor' );
	}

	public function get_icon() {
		return 'eicon-table tablentor';
	}

	public function get_categories() {
		return  [ 'basic' ];
	}

	protected function is_dynamic_content():bool {
		return false;
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'table_content_section',
			[
				'label' => __( 'Content', 'tablentor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'csv_type',
			[
				'label'   => esc_html__( 'CSV Type', 'tablentor' ),
				'type'    => Controls_Manager::HIDDEN,
				'options' => [
					'text' => [
						'title' => esc_html__( 'Text', 'tablentor' ),
						'icon' => 'eicon-animation-text',
					],
					'file' => [
						'title' => esc_html__( 'File', 'tablentor' ),
						'icon' => 'eicon-document-file',
					],
				],
				'default' => 'text',
				'toggle' => true,
			]
		);

		$this->add_control(
			'csv_text',
			[
				'label'     => esc_html__( 'CSV Data', 'tablentor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'rows'      => 10,
				'ai'        => [ 'active' =>false ],
				'default'   => "name, age, address \nJohn, 25, 123 Street Name City \nJane, 30, 456 Another St Another City",
				'condition' => [
					'csv_type' => 'text'
				]
			]
		);

		$this->add_control(
			'csv_file',
			[
				'label'       => esc_html__( 'File Url', 'tablentor' ),
				'type'        => Controls_Manager::URL,
				'options'     => false,
				'label_block' => true,
				'placeholder' => esc_html__( 'Paste your csv URL', 'tablentor' ),
				'condition'   => [
					'csv_type' => 'file'
				]
			]
		);

		$this->add_control(
			'first_row_as_header',
			[
				'label'        => esc_html__( 'First Row/Line as Header', 'tablentor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'enable_data_table',
			[
				'label'        => esc_html__( 'Enable Data Table', 'tablentor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'description' => __( 'Data tables are used to organize and display data in a structured grid. Learn more at <a href="https://example.com/data-tables" target="_blank">Data Tables</a>.'),
				'condition'   => [
					'first_row_as_header' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'data_table_settings_section',
			[
				'label'     => __( 'Data Table', 'tablentor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition'   => [
					'enable_data_table' => 'yes',
					'first_row_as_header' => 'yes'
				]
			]
		);

		$this->add_control(
			'search_input',
			[
				'label'        => esc_html__( 'Search Input', 'tablentor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'tablentor' ),
				'label_off'    => esc_html__( 'Hide', 'tablentor' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'pagination',
			[
				'label'        => esc_html__( 'Pagination', 'tablentor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'tablentor' ),
				'label_off'    => esc_html__( 'Hide', 'tablentor' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'top_bar_pagination',
			[
				'label'        => esc_html__( 'Header Entry Manager', 'tablentor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'tablentor' ),
				'label_off'    => esc_html__( 'Hide', 'tablentor' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'   => [
					'pagination' => 'yes',
				]
			]
		);

		$this->add_control(
			'sorting',
			[
				'label'        => esc_html__( 'Sorting', 'tablentor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Enable', 'tablentor' ),
				'label_off'    => esc_html__( 'Disable', 'tablentor' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'search_input_styling',
			[
				'label'     => __( 'Search Input', 'tablentor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_table_search' => 'yes'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'search_input_typography',
				'selector' => '{{WRAPPER}} .tablentor-bt-search-input',
			]
		);

		$this->add_control(
			'search_input_text_color',
			[
				'label' => esc_html__( 'Text Color', 'tablentor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tablentor-bt-search-input' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'search_input_border',
				'selector' => '{{WRAPPER}} .tablentor-bt-search-input',
			]
		);

		$this->add_control(
			'search_input_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'tablentor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'default' => [
					'top'    => 4,
					'right'  => 4,
					'bottom' => 4,
					'left'   => 4,
					'unit'   => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .tablentor-bt-search-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'search_input_border_padding',
			[
				'label' => esc_html__( 'Padding', 'tablentor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'default' => [
					'top'    => 5,
					'right'  => 5,
					'bottom' => 5,
					'left'   => 5,
					'unit'   => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .tablentor-bt-search-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'search_input_border_margin',
			[
				'label' => esc_html__( 'Margin', 'tablentor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'default' => [
					'top'    => 0,
					'right'  => 0,
					'bottom' => 10,
					'left'   => 0,
					'unit'   => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .tablentor-bt-search-input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Table Styling
		 *  
		 */
		$this->start_controls_section(
			'Table_Design',
			[
				'label' => __( 'Table Styling', 'tablentor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'tablentor' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
				'selectors' => [
					'{{WRAPPER}} .ct-basic-table' => 'margin:0;overflow:hidden;',
					'{{WRAPPER}} .ct-basic-table tr td, {{WRAPPER}} .ct-basic-table tr th' => 'overflow:hidden;',
				],
			]
		);

		$this->add_control(
			'column_width',
			[
				'label' => __( 'Column Width', 'tablentor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ct-basic-table tr th' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ct-basic-table tr td' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'table_background',
				'label' => __( 'Background', 'tablentor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ct-basic-table',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'table_border',
				'label' => __( 'Border', 'tablentor' ),
				'selector' => '{{WRAPPER}} .ct-basic-table',
				'separator'	=> 'before'
			]
		);

		$this->add_control(
			'table-border-radius',
			[
				'label' => __( 'Border Radius', 'tablentor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'separator'	=> 'after',
				'selectors' => [
					'{{WRAPPER}} .ct-basic-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'table_box_shadow',
				'label' => __( 'Box Shadow', 'tablentor' ),
				'selector' => '{{WRAPPER}} .ct-basic-table',
				'separator'	=> 'before'
			]
		);

		$this->add_control(
			'table_padding',
			[
				'label' => __( 'Padding', 'tablentor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'separator'	=> 'before',
				'selectors' => [
					'{{WRAPPER}} .ct-basic-table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'table_margin',
			[
				'label' => __( 'Margin', 'tablentor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ct-basic-table' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Table heading Styling
		 *  
		 */
		$this->start_controls_section(
			'table_heading',
			[
				'label' => __( 'Table Heading', 'tablentor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'header_content_typography',
			[
				'label' 	   => __( 'Alignment', 'tablentor' ),
				'type' 		   => Controls_Manager::CHOOSE,
				'options' 	   => [
					'left' 		=> [
						'title' 	=> __( 'Left', 'tablentor' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'center' 	=> [
						'title' 	=> __( 'Center', 'tablentor' ),
						'icon' 		=> 'eicon-text-align-center',
					],
					'right' 	=> [
						'title' 	=> __( 'Right', 'tablentor' ),
						'icon' 		=> 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ct-basic-table tr th' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .ct-basic-table tr th',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'table_heading_background',
				'label' => __( 'Background', 'tablentor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ct-basic-table tr th',
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'table_row_border',
				'label' => __( 'Border', 'tablentor' ),
				'selector' => '{{WRAPPER}} .ct-basic-table tr th',
				'separator'	=> 'before'
			]
		);

		$this->add_control(
			'table_row-border-radius',
			[
				'label' => __( 'Border Radius', 'tablentor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'separator'	=> 'after',
				'selectors' => [
					'{{WRAPPER}} .ct-basic-table tr th' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'table_heading_padding',
			[
				'label' => __( 'Padding', 'tablentor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'separator'	=> 'before',
				'selectors' => [
					'{{WRAPPER}} .ct-basic-table tr th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Table Columns Styling
		 *  
		 */
		$this->start_controls_section(
			'table_columns',
			[
				'label' => __( 'Table Columns', 'tablentor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'column_content_typography',
			[
				'label' 	   => __( 'Alignment', 'tablentor' ),
				'type' 		   => Controls_Manager::CHOOSE,
				'options' 	   => [
					'left' 		=> [
						'title' 	=> __( 'Left', 'tablentor' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'center' 	=> [
						'title' 	=> __( 'Center', 'tablentor' ),
						'icon' 		=> 'eicon-text-align-center',
					],
					'right' 	=> [
						'title' 	=> __( 'Right', 'tablentor' ),
						'icon' 		=> 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ct-basic-table tr td' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_columns_typography',
				'selector' => '{{WRAPPER}} .ct-basic-table tr td',
			]
		);


		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'table_columns_background',
				'label' => __( 'Background', 'tablentor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ct-basic-table tr td',
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'table__columns_border',
				'label' => __( 'Border', 'tablentor' ),
				'selector' => '{{WRAPPER}} .ct-basic-table tr td',
				'separator'	=> 'before'
			]
		);

		$this->add_control(
			'table_columns-border-radius',
			[
				'label' => __( 'Border Radius', 'tablentor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'separator'	=> 'after',
				'selectors' => [
					'{{WRAPPER}} .ct-basic-table tr td' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'table_columns_padding',
			[
				'label' => __( 'Padding', 'tablentor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'separator'	=> 'before',
				'selectors' => [
					'{{WRAPPER}} .ct-basic-table tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Table Styling
		 *  
		 */
		$this->start_controls_section(
			'table_imge_design',
			[
				'label' => __( 'Table Images', 'tablentor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'column_img_width',
			[
				'label' => __( 'Image Width', 'tablentor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .ct-basic-table tr th img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ct-basic-table tr td img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'column_img_height',
			[
				'label' => __( 'Image Height', 'tablentor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ct-basic-table tr th img' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ct-basic-table tr td img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'table__images_border',
				'label' => __( 'Border', 'tablentor' ),
				'selector' => '{{WRAPPER}} .ct-basic-table tr td img, {{WRAPPER}} .ct-basic-table tr th img',
				'separator'	=> 'before'
			]
		);

		$this->add_control(
			'table_images-border-radius',
			[
				'label' => __( 'Border Radius', 'tablentor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'separator'	=> 'after',
				'selectors' => [
					'{{WRAPPER}} .ct-basic-table tr td img, {{WRAPPER}} .ct-basic-table tr th img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		$csv_text = '';

		if ( 'text' === $settings['csv_type'] ) {
			$csv_text = $settings['csv_text'];
		} else if ( 'file' === $settings['csv_type'] ) {
			if ( ! empty( $settings['csv_file']['url'] ) ) {
				$fileExtension = pathinfo($settings['csv_file']['url'], PATHINFO_EXTENSION );
				if ( strtolower( $fileExtension) !== 'csv' ) {
					esc_html_e( 'Error: The file is not a CSV.', 'tablentor' );
					return;
				}

				$csvContent = file_get_contents($settings['csv_file']['url'] );

				if ($csvContent === false) {
					echo "Error: Unable to retrieve the CSV file.";
					return [];
				}

				// Split CSV content into rows by newline
				$rows = preg_split('/\r\n|\n|\r/', trim($csvContent));
			}
		}

		if ( empty( $csv_text ) ) {
			return;
		}

		$rows = preg_split('/\r\n|\n|\r/', $csv_text);

		if ( empty( $rows ) ) return;

		$is_editor = Plugin::instance()->editor->is_edit_mode();
		$table_css_id = 'tablentor-table-csv-' . $this->get_id();
		$this->add_render_attribute( 'table-csv-wrapper', [
			'id' => $table_css_id,
			'class' => 'tablentor-table-csv-container'
		]);

		if ( 'yes' === $settings['enable_data_table'] ) {
			$options = [ 'table' => 'yes' ];

			if ( 'yes' === $settings['search_input'] ) {
				$options['searching'] = 'yes';
			}

			if ( 'yes' === $settings['pagination'] ) {
				$options['paging'] = 'yes';
			}

			if ( 'yes' !== $settings['search_input'] && 'yes' !== $settings['pagination'] ) {
				$this->add_render_attribute( 'table-csv-wrapper', 'class', 'hide-table-heading' );
			}

			if ( isset( $settings['top_bar_pagination'] ) && 'yes' !== $settings['top_bar_pagination'] ) {
				$options['paging_length'] = 'no';

				if ( $is_editor ) {
					$this->add_render_attribute( 'table-csv-wrapper', 'class', 'hide-table-length' );
				}
			}

			if ( 'yes' === $settings['sorting'] ) {
				$options['ordering'] = 'yes';
			}

			$options = base64_encode( wp_json_encode( $options ) );
			$this->add_render_attribute( 'table-csv-wrapper', 'data-table', $options );
		} else {
			$this->add_render_attribute( 'table-csv-wrapper', 'class', 'no-data-table' );
		}

		echo '<div '; $this->print_render_attribute_string( 'table-csv-wrapper' ); echo '>';
		echo "<table class='tablentor-table-csv'>";

		if ( 'yes' === $settings['first_row_as_header'] ){
			$header_row = trim( $rows[0] );

			if ( $header_row ){
				echo '<thead>';
				echo '<tr>';
				$header_columns = str_getcsv( $header_row );

				foreach( $header_columns as $column ) {
					echo "<th>" . esc_html( trim( $column ) ) . "</th>";
				}

				echo '</tr>';
				echo '</thead>';
			}
			unset( $rows[0] );
		}

		echo "<tbody>";
		$row_count = 0;
		foreach ( $rows as $key => $line ) {
			if ( ! empty( trim( $line ) ) ) {
				$columns = str_getcsv($line);
				echo '<tr>';
				foreach( $columns as $column ) {
					echo "<td>" . $this->parse_text_editor( $column ) . "</td>";
				}
				echo '</tr>';
			}

			$row_count ++;
			if ( $is_editor && $row_count > 12 ) {
				break;
			}
		}
		echo "</tbody>";
		echo "</table>";

		if ( $is_editor ) {
			echo '<div style="background-color:#e7f3fe; color: #31708f;border-color: #bce8f1;padding: 15px;margin-top: 20px;border-radius: 5px;border: 1px solid #ddd;font-family: Arial, sans-serif;">
				<strong>' . esc_html__( 'Note:', 'tablentor' ) . '</strong> ' . esc_html__( 'All Data Will load on frontend', 'tablentor' ) . '
			</div>';
		}
		
		echo "</div>";
	}
}