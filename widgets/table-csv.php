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
				'label'        => esc_html__( 'Entries Per Page', 'tablentor' ),
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
				'label'     => __( 'Search', 'tablentor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'search_input'        => 'yes',
					'enable_data_table'   => 'yes',
					'first_row_as_header' =>  'yes'
				],
			]
		);

		$this->add_control(
			'search_input_label_styling_heading',
			[
				'label' => esc_html__( 'Label', 'tablentor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'search_input_label_typography',
				'selector' => '{{WRAPPER}} .tablentor-table-csv-container .dt-search label',
			]
		);

		$this->add_control(
			'search_input_label_color',
			[
				'label' => esc_html__( 'Text Color', 'tablentor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tablentor-table-csv-container .dt-search label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'search_input_styling_heading',
			[
				'label' => esc_html__( 'Input', 'tablentor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'search_input_typography',
				'selector' => '{{WRAPPER}} .tablentor-table-csv-container .dt-search .dt-input',
			]
		);

		$this->add_control(
			'search_input_text_color',
			[
				'label' => esc_html__( 'Text Color', 'tablentor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tablentor-table-csv-container .dt-search .dt-input' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'search_input_border',
				'selector' => '{{WRAPPER}} .tablentor-table-csv-container .dt-search .dt-input',
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
					'{{WRAPPER}} .tablentor-table-csv-container .dt-search .dt-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .tablentor-table-csv-container .dt-search .dt-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .tablentor-table-csv-container .dt-search .dt-input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Table Styling
		 *  
		 */
		$this->start_controls_section(
			'entries_per_page_styling',
			[
				'label' => __( 'Entries Per Page', 'tablentor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'first_row_as_header' => 'yes',
					'enable_data_table'   => 'yes',
					'pagination'          => 'yes',
					'top_bar_pagination'  => 'yes',
				],
			]
		);

		$this->add_control(
			'entries_per_page_info_styling_heading',
			[
				'label' => esc_html__( 'Info', 'tablentor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'entries_per_page_label_typography',
				'selector' => '{{WRAPPER}} .tablentor-table-csv-container .dt-length label',
			]
		);

		$this->add_control(
			'entries_per_page_label_color',
			[
				'label' => esc_html__( 'Color', 'tablentor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tablentor-table-csv-container .dt-length label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'entries_per_page_styling_heading',
			[
				'label' => esc_html__( 'Dropdown', 'tablentor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'entries_per_page_typography',
				'selector' => '{{WRAPPER}} .tablentor-table-csv-container .dt-length select.dt-input',
			]
		);

		$this->add_control(
			'entries_per_page_text_color',
			[
				'label' => esc_html__( 'Text Color', 'tablentor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tablentor-table-csv-container .dt-length select.dt-input' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'entries_per_page_border',
				'selector' => '{{WRAPPER}} .tablentor-table-csv-container .dt-length select.dt-input',
			]
		);

		$this->add_control(
			'entries_per_page_border_radius',
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
					'{{WRAPPER}} .tablentor-table-csv-container .dt-length select.dt-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'entries_per_page_border_padding',
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
					'{{WRAPPER}} .tablentor-table-csv-container .dt-length select.dt-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'entries_per_page_border_margin',
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
					'{{WRAPPER}} .tablentor-table-csv-container .dt-length select.dt-input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'soring_icon_color',
			[
				'label'     => esc_html__( 'Sorting Icon Color', 'tablentor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv .dt-column-order:before' => 'color: {{VALUE}}',
					'{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv .dt-column-order:after' => 'color: {{VALUE}}',
				],
				'condition' => [
					'first_row_as_header' => 'yes',
					'enable_data_table'   => 'yes',
					'sorting'             => 'yes',
				],
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
					'{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv thead tr th' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv thead tr th',
			]
		);

		$this->add_control(
			'heading_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'tablentor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv thead tr th' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'table_heading_background',
				'label' => __( 'Background', 'tablentor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv thead tr th',
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'table_row_border',
				'label' => __( 'Border', 'tablentor' ),
				'selector' => '{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv thead tr th',
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
					'{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv thead tr th' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv thead tr th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Table body Styling
		 *  
		 */
		$this->start_controls_section(
			'table_body',
			[
				'label' => __( 'Table Body', 'tablentor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'table_body_content_alignment',
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
					'{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv tbody tr td' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'table_body_content_typography',
				'selector' => '{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv tbody tr td',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'tablentor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv tbody tr td' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'table_body_content_background',
				'label'    => __( 'Background', 'tablentor' ),
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv tbody tr td',
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'table_body_content_border',
				'label'     => __( 'Border', 'tablentor' ),
				'selector'  => '{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv tbody tr td',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'table_body_content_border_radius',
			[
				'label'      => __( 'Border Radius', 'tablentor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'separator'	 => 'after',
				'selectors'  => [
					'{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv tbody tr td' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'table_body_content_padding',
			[
				'label'      => __( 'Padding', 'tablentor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'separator'	 => 'before',
				'selectors'  => [
					'{{WRAPPER}} .tablentor-table-csv-container .tablentor-table-csv tbody tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		if ( $is_editor && 'yes' === $settings['pagination'] ) {
			?>
			<div class="tablentor-dumy-row dt-container">
				<div class="dumy-column-start">
					<div class="dt-info" aria-live="polite" role="status"><?php esc_html_e( "Showing 1 to {$row_count} of " . count( $rows ) . " entries", 'tablentor' ); ?></div>
				</div>
				<div class="dumy-column-start">
					<div class="dt-paging">
						<nav aria-label="pagination">
							<button class="dt-paging-button disabled first" role="link" type="button"  aria-disabled="true" aria-label="First" data-dt-idx="first" tabindex="-1">«</button>
							<button class="dt-paging-button disabled previous" role="link" type="button"  aria-disabled="true" aria-label="Previous" data-dt-idx="previous" tabindex="-1">‹</button>
							<button class="dt-paging-button current" role="link" type="button"  aria-current="page" data-dt-idx="0">1</button>
							<button class="dt-paging-button" role="link" type="button"  data-dt-idx="1">2</button>
							<button class="dt-paging-button" role="link" type="button"  data-dt-idx="2">3</button>
							<button class="dt-paging-button next" role="link" type="button"  aria-label="Next" data-dt-idx="next">›</button>
							<button class="dt-paging-button last" role="link" type="button"  aria-label="Last" data-dt-idx="last">»</button>
						</nav>
					</div>
				</div>
			</div>
			<?php
			echo '<div style="background-color:#e7f3fe; color: #31708f;border-color: #bce8f1;padding: 15px;margin-top: 20px;border-radius: 5px;border: 1px solid #ddd;font-family: Arial, sans-serif;">
				<strong>' . esc_html__( 'Note:', 'tablentor' ) . '</strong> ' . esc_html__( 'All data will load on the frontend, and pagination will function there as well.', 'tablentor' ) . '
			</div>';
		}
		
		echo "</div>";
	}
}