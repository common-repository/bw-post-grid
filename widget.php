<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_BW_Post_Grid_Widget extends \Elementor\Widget_Base {

public function get_name() {
		return 'bw-post-grid';
	}

	public function get_title() {
		return __( 'BW Post Grid', 'bwpg' );
	}

	public function get_icon() {
		return 'fa fa-th';
	}

	public function get_categories() {
		return [ 'basic' ];
	}
	
	protected function _register_controls() {

		$this->start_controls_section(
			'section_my_custom',
			[
				'label' => esc_html__( 'Settings', 'bwpg' ),
			]
		);
		$this->add_control(
            'post_type',
            [
                'label' => esc_html__( 'Select post type', 'bwpg' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => bw_grid_post_type(),                                
            ]
        );
	    $this->add_control(
			'numberofposts',
			[
                'label' => esc_html__( 'Post Per Page', 'bwpg'),
                'description' => esc_html__( 'Give -1 for all post & No Pagination', 'bwpg' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => -1,
            ]
		);
		
		$this->add_control(
            'offset',
            [
                'label' => esc_html__( 'Post Offset', 'bwpg' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '0'
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => esc_html__( 'Order By', 'bwpg' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => bw_post_orderby_options(),
                'default' => 'date',

            ]
        );
        
        $this->add_control(
            'order',
            [
                'label' => esc_html__( 'Post Order', 'bwpg' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending'
                ],
                'default' => 'desc',

            ]
        );

        $this->add_control(
                'pagination_yes',
                [
                    'label' => esc_html__( 'Pagination Enabled', 'bwpg' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        1 => 'Yes',
                        2 => 'No'
                    ],
                    'default' => 1,
                    'condition' => [
                            'numberofposts!' => -1,
                        ]
                ]
            );
            
        $this->add_control(
                'show_excerpt',
                [
                    'label' => esc_html__( 'Show excerpt', 'bwpg' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        '2' => 'No',
                        '1' => 'Yes'
                        
                    ],
                    'default' => 1,
                    
                ]
            );
            
        $this->add_control(
                'excerpt_length',
                [
                    'label' => esc_html__( 'Excerpt length', 'bwpg' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'condition' => [
                    'show_excerpt' => ['1'],
                ],
                    'default' => 20,
                ]
            ); 
            
            
        $this->add_control(
                'show_date',
                [
                    'label' => esc_html__( 'Show date', 'bwpg' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => [
                        '1' => 'Yes',
                        '2' => 'No'
                    ],
                    'default' => '1',
                    
                ]
            );
	
		$this->end_controls_section();
		
		
		$this->start_controls_section(
            'section_style_grid',
            [
                'label' => esc_html__( 'Style', 'bwpg' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'title_font_size',
            [
                'label' => esc_html__( 'Title Size', 'bwpg' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
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
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .entry-title' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'bwpg' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .entry-title a' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();

	}
	    protected function render(){
    	    $settings = $this->get_settings_for_display();
    	    
    	    echo'<div class="elementor-shortcode">';
                echo do_shortcode('[bw_post_grid_shortcode show_date="'.$settings['show_date'].'" filter_thumbnail="" cat_exclude="" post_type="'.$settings['post_type'].'" show_excerpt="'.$settings['show_excerpt'].'" excerpt_length="'.$settings['excerpt_length'].'" pagination_yes="'.$settings['pagination_yes'].'" posts="'.$settings['numberofposts'].'" posts_per_row="" image_style="" sticky_ignore=""  orderby="'.$settings['orderby'].'" order="'.$settings['order'].'" offset="'.$settings['offset'].'"  terms="" taxonomy_type="" image_size="" ]');    
    		echo'</div>';
    		
	    }


}


