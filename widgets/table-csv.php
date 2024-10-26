<?php
namespace Jakaria\Tablentor;

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
			'section_counter',
			[
				'label' => __( 'General', 'tablentor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'column_count', [
				'label' => __( 'Number of Column', 'tablentor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 20,
				'default' => 3
			]
		);

		$this->add_control(
			'enable_table_search',
			[
				'label'        => esc_html__( 'Enable Search', 'tablentor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'tablentor' ),
				'label_off'    => esc_html__( 'No', 'tablentor' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'search_input_content',
			[
				'label'     => __( 'Search Input', 'tablentor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'enable_table_search' => 'yes'
				],
			]
		);

		$this->add_control(
			'search_input_placeholder',
			[
				'label'   => esc_html__( 'Placeholder', 'tablentor' ),
				'type'    => Controls_Manager::TEXT,
				'ai'      => [ 'active' => false ],
				'default' => esc_html__( 'Search', 'tablentor' ),
			]
		);

		$this->add_control(
			'search_input_alingnment',
			[
				'label'   => esc_html__( 'Alignment', 'tablentor' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'tablentor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'tablentor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'tablentor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'right',
				'toggle'    => false,
				'selectors' => [
					'{{WRAPPER}} .tablentor-bt-search' => 'display: flex;justify-content: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$con_val = [];
		$total_column = 20;
		for ( $rc = 1 ; $rc <= $total_column ; $rc++ ) {
			$con_val = range( $rc, $total_column);
			$this->start_controls_section(
				"content_section_{$rc}",
				[
					'label' => __( 'Column ', 'tablentor' ).$rc,
					'tab' => Controls_Manager::TAB_CONTENT,
					'conditions'=> [
						'terms' => [
							[
			                    'name' => 'column_count',
			                    'operator' => 'in',
			                    'value' => $con_val,
			                ],
						]
					]
				]
			);
			$var_name 	= "repeater_{$rc}";
			$$var_name 	= new Repeater();

			$$var_name->add_control(
				'is_heading',
				[
					'label' => __( 'Is this a heading ?', 'tablentor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'Yes', 'your-plugin' ),
					'label_off' => __( 'No', 'your-plugin' ),
					'return_value' => 'yes',
					'default' => '',
				]
			);

			$$var_name->add_control(
				'list_title', [
					'label' => __( 'Row Name', 'tablentor' ),
					'type' => Controls_Manager::TEXT,
					'default' => __( 'Row Name' , 'tablentor' ),
					'label_block' => true,
				]
			);

			$$var_name->add_control(
				'content_type', [
					'label' => __( 'Content Type', 'tablentor' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'content' 	=> __( 'Content', 'tablentor' ),
						'image'		=> __( 'Image', 'tablentor' ),
					],
					'default' => 'content',
					'label_block' => true,
				]
			);

			$$var_name->add_control(
				'list_content', [
					'label' => __( 'Content', 'tablentor' ),
					'type' => Controls_Manager::WYSIWYG,
					'default' => __( 'Row Content' , 'tablentor' ),
					'show_label' => false,
					'condition' => [
						'content_type' => 'content'
					]
				]
			);

			$$var_name->add_control(
				'list_image',
				[
					'label' => __( 'Choose Image', 'tablentor' ),
					'type' => Controls_Manager::MEDIA,
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
					],
					'condition' => [
						'content_type' => 'image'
					]
				]
			);

			$$var_name->add_control(
				'list_color',
				[
					'label' => __( 'Color', 'tablentor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}'
					],
				]
			);

			$this->add_control(
				"list_{$rc}",
				[
					'label' => __( 'Repeater List', 'tablentor' ),
					'type' => Controls_Manager::REPEATER,
					'fields' => $$var_name->get_controls(),
					'default' => [
						[
							'list_title' => __( 'Row #1', 'tablentor' ),
							'list_content' => __( 'Content 1', 'tablentor' ),
						],
						[
							'list_title' => __( 'Row #2', 'tablentor' ),
							'list_content' => __( 'Content 2', 'tablentor' ),
						],
					],
					'title_field' => '{{{ list_title }}}',
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

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$column_count = (int)sanitize_text_field( $settings['column_count'] );

		if ( $column_count < 1 ) {
			esc_html_e( 'Add Some Column', 'basic-table' );
			return;
		}

		$columns = $column_html = [];
		$max_rows = 0;
		for ($column = 1 ; $column <= $column_count ; $column++ ) { 
			if ( isset( $settings["list_{$column}"] ) ) {
				$row_count = count( $settings["list_{$column}"] );
				$max_rows  = $row_count > $max_rows ? $row_count : $max_rows;
			}
		}

		for ($column = 1 ; $column <= $column_count ; $column++ ) { 
			if ( isset( $settings["list_{$column}"] ) ) {
				for ( $col = 0; $col < $max_rows ; $col++) { 
					if ( isset( $settings["list_{$column}"][$col] ) ) {	
						$row = $settings["list_{$column}"][$col];
						$tag = $row['is_heading'] == 'yes' ? 'th' : 'td';
						$content = '';
						if ( $row['content_type'] == 'content' ) {
							$content = wp_kses( $row['list_content'], 'post' );
						}
						else if ( $row['content_type'] == 'image' ) {
							$img_url = esc_url( $row['list_image']['url'] );
							$content = "<img src='{$img_url}'>";
						}
						$column_html["row_{$col}"][] = "<$tag>{$content}</$tag>";
					}else{
						$column_html["row_{$col}"][] = "<$tag></$tag>";
					}
				}
			}
		}

		$rows_html 	= "";
		for ( $row = 0 ; $row < count( $column_html ) ; $row++ ) {
			$rows_html .= "<tr>". implode( ' ', $column_html["row_{$row}"] ) ."</tr>";
		}

		echo "<div id='tablentor-bt-" . esc_attr( $this->get_id() ) . "' class='ct-basic-table-container'>";
		if( 'yes' === $settings['enable_table_search'] ){
			echo "<div class='tablentor-bt-search'>";
			echo "<input class='tablentor-bt-search-input' placeholder='" . esc_attr( $settings['search_input_placeholder'] ) . "' />";
			echo "</div>";
		}
		echo "<table class='ct-basic-table' >" . wp_kses_post( $rows_html ) . "</table>";
		?>
		<script>
			jQuery(document).ready(function($){
				var container_id = '#tablentor-bt-<?php echo esc_attr( $this->get_id() ); ?>';
				$( container_id + " .tablentor-bt-search-input").on("keyup", function() {
					var value = $(this).val().toLowerCase();
					$( container_id + " .ct-basic-table tr").filter(function() {
						$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
						});
				});
			});
		</script>
		<?php
		echo "</div>";
	}
}