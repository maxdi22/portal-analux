<?php
namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Jws_Price_Table extends Widget_Base {

	public function get_name() {
		return 'jws-price-table';
	}

	public function get_title() {
		return esc_html__( 'Price Table', 'auriane' );
	}

	public function get_icon() {
		return 'eicon-price-table';
	}

	public function get_keywords() {
		return [ 'pricing', 'table', 'product', 'image', 'plan', 'button' ];
	}
    public function get_categories() {
		return [ 'jws-elements' ];
	}

	protected function register_controls() {
         $plan_args           = array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'DESC',
			'meta_query'     => array(
				array(
					'key'     => '_rp_sub:subscription_product',
					'value'   => 'yes',
					'compare' => '=',
				),
			),
		);
        $products            = new \WP_Query( $plan_args );
		$products_plan_array = array( esc_html__( '', 'auriane' ) => 'Choose plan' );
		if ( $products->have_posts() ) {
			while ( $products->have_posts() ) {
				$products->the_post();
				$title                         = get_the_title();
				$id                            = get_the_ID();
				$products_plan_array[ $id ] = $title;
			}
             wp_reset_postdata();  
		}
       
		$this->start_controls_section(
			'section_side',
			[
				'label' => esc_html__( 'Settings', 'auriane' ),
			]
		);
        $this->add_control(
			'section_pricing_title',
			[
				'label' => esc_html__( 'Title', 'auriane' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Subscription Plan', 'auriane' ),
			]
		);
        $this->add_control(
			'text-nav-on',
			[
				'label' => __( 'Nav', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'auriane' ),
				'label_off' => __( 'No', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        $this->add_control(
			'text-nav-1', [
				'label' => __( 'Text nav 1', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Monthly' , 'auriane' ),
				'label_block' => true,
                'condition' => [
						'text-nav-on' => [ 'yes' ],
				],
			]
		);
         $this->add_control(
			'text-nav-2', [
				'label' => __( 'Text nav 2', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Yearly' , 'auriane' ),
				'label_block' => true,
                'condition' => [
						'text-nav-on' => [ 'yes' ],
				],
			]
		);


		$this->end_controls_section();
        /** Plan 1 **/
		$this->start_controls_section(
			'section_pricing_1',
			[
				'label' => esc_html__( 'Pricing 1', 'auriane' ),
			]
		);
        $this->add_control(
			'section_pricing_1_roles',
			[
				'label' => esc_html__( 'Roles', 'auriane' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'PERSONAL', 'auriane' ),
			]
		);
        $this->add_control(
			'section_pricing_1_popular',
			[
				'label' => __( 'Popular', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'auriane' ),
				'label_off' => __( 'No', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
        $this->add_control(
			'section_pricing_1_popular_text',
			[
				'label' => esc_html__( 'Popular Text', 'auriane' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'POPULAR', 'auriane' ),
                'condition' => [
						'section_pricing_1_popular' => [ 'yes' ],
				],
			]
		);
        $this->add_control(
			'section_pricing_1_title',
			[
				'label' => esc_html__( 'Title', 'auriane' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Free', 'auriane' ),
			]
		);
        $this->add_control(
			'section_pricing_1_price_mon',
			[
				'label' => esc_html__( 'Price Monthly', 'auriane' ),
				'type' => Controls_Manager::NUMBER,
			]
		);
        $this->add_control(
			'section_pricing_1_price_year',
			[
				'label' => esc_html__( 'Price Year', 'auriane' ),
				'type' => Controls_Manager::NUMBER,
			]
		);
        
        
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'section_pricing_1_fr_text', [
				'label' => __( 'Title', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'List Title' , 'auriane' ),
				'label_block' => true,
                'condition' => [
						'section_pricing_1_fr_select' => [ 'text' ],
				],
			]
		);
        $repeater->add_control(
			'section_pricing_1_fr_select',
			[
				'label' => __( 'Chooose Type', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'text',
				'options' => [
					'text'  => __( 'Text', 'auriane' ),
					'check' => __( 'Check', 'auriane' ),
				],
			]
		);
        $repeater->add_control(
			'section_pricing_1_fr_name_mobile', [
				'label' => __( 'Title Mobile', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'List Title' , 'auriane' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'list1',
			[
				'label' => __( 'Feature List Value', 'auriane' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'section_pricing_1_fr_text' => __( 'text #1', 'auriane' ),

					],

				],
				'title_field' => '{{{ section_pricing_1_fr_name_mobile }}}',
			]
		);
        
         $this->add_control(
			'section_pricing_1_buy_name',
			[
				'label' => esc_html__( 'Button Buy Name', 'auriane' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Get started', 'auriane' ),
			]
		);
        $this->add_control(
			'section_pricing_1_buy_free',
			[
				'label' => __( 'Plan Free', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'auriane' ),
				'label_off' => __( 'No', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
        $this->add_control(
			'section_pricing_1_buy_mon',
			[
				'label' => __( 'Plan add to cart (Plan ID) for month', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' =>  $products_plan_array,
                'condition' => [
						'section_pricing_1_buy_free' => [ 'no' ],
				],
			]
		);    
        $this->add_control(
			'section_pricing_1_buy_year',
			[
				'label' => __( 'Plan add to cart (Plan ID) for year', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' =>  $products_plan_array,
                'condition' => [
						'section_pricing_1_buy_free' => [ 'no' ],
				],
			]
		);
        
		$this->end_controls_section();
        
        /** Plan 2 **/
		$this->start_controls_section(
			'section_pricing_2',
			[
				'label' => esc_html__( 'Pricing 2', 'auriane' ),
			]
		);
        $this->add_control(
			'section_pricing_2_roles',
			[
				'label' => esc_html__( 'Roles', 'auriane' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'PERSONAL', 'auriane' ),
			]
		);
        $this->add_control(
			'section_pricing_2_popular',
			[
				'label' => __( 'Popular', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'auriane' ),
				'label_off' => __( 'No', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
        $this->add_control(
			'section_pricing_2_popular_text',
			[
				'label' => esc_html__( 'Popular Text', 'auriane' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'POPULAR', 'auriane' ),
                'condition' => [
						'section_pricing_1_popular' => [ 'yes' ],
				],
			]
		);
        $this->add_control(
			'section_pricing_2_title',
			[
				'label' => esc_html__( 'Title', 'auriane' ),
				'type' => Controls_Manager::NUMBER,
				'default' => esc_html__( 'Business', 'auriane' ),
			]
		);
        $this->add_control(
			'section_pricing_2_price_mon',
			[
				'label' => esc_html__( 'Price Monthly', 'auriane' ),
				'type' => Controls_Manager::NUMBER,
			]
		);
        $this->add_control(
			'section_pricing_2_price_year',
			[
				'label' => esc_html__( 'Price Year', 'auriane' ),
				'type' => Controls_Manager::TEXT,
			]
		);
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'section_pricing_2_fr_text', [
				'label' => __( 'Title', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'List Title' , 'auriane' ),
				'label_block' => true,
                'condition' => [
						'section_pricing_2_fr_select' => [ 'text' ],
				],
			]
		);
        $repeater->add_control(
			'section_pricing_2_fr_select',
			[
				'label' => __( 'Chooose Type', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'text',
				'options' => [
					'text'  => __( 'Text', 'auriane' ),
					'check' => __( 'Check', 'auriane' ),
				],
			]
		);
        $repeater->add_control(
			'section_pricing_2_fr_name_mobile', [
				'label' => __( 'Title', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'List Title' , 'auriane' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'list2',
			[
				'label' => __( 'Feature List Value', 'auriane' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'section_pricing_2_fr_text' => __( 'text #1', 'auriane' ),

					],

				],
				'title_field' => '{{{ section_pricing_2_fr_name_mobile }}}',
			]
		);
        
         $this->add_control(
			'section_pricing_2_buy_name',
			[
				'label' => esc_html__( 'Button Buy Name', 'auriane' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Get Business', 'auriane' ),
			]
		);
        $this->add_control(
			'section_pricing_2_buy_free',
			[
				'label' => __( 'Plan Free', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'auriane' ),
				'label_off' => __( 'No', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
        $this->add_control(
			'section_pricing_2_buy_mon',
			[
				'label' => __( 'Plan add to cart (Plan ID) for month', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' =>  $products_plan_array,
                'condition' => [
						'section_pricing_2_buy_free' => [ 'no' ],
				],
			]
		);    
        $this->add_control(
			'section_pricing_2_buy_year',
			[
				'label' => __( 'Plan add to cart (Plan ID) for year', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' =>  $products_plan_array,
                'condition' => [
						'section_pricing_2_buy_free' => [ 'no' ],
				],
			]
		);

		$this->end_controls_section();
        
        /** Plan 3 **/
		$this->start_controls_section(
			'section_pricing_3',
			[
				'label' => esc_html__( 'Pricing 3', 'auriane' ),
			]
		);
        $this->add_control(
			'section_pricing_3_roles',
			[
				'label' => esc_html__( 'Roles', 'auriane' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'PERSONAL', 'auriane' ),
			]
		);
        $this->add_control(
			'section_pricing_3_popular',
			[
				'label' => __( 'Popular', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'auriane' ),
				'label_off' => __( 'No', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
        $this->add_control(
			'section_pricing_3_popular_text',
			[
				'label' => esc_html__( 'Popular Text', 'auriane' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'POPULAR', 'auriane' ),
                'condition' => [
						'section_pricing_3_popular' => [ 'yes' ],
				],
			]
		);
        $this->add_control(
			'section_pricing_3_title',
			[
				'label' => esc_html__( 'Title', 'auriane' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Enterprise', 'auriane' ),
			]
		);
        $this->add_control(
			'section_pricing_3_price_mon',
			[
				'label' => esc_html__( 'Price Monthly', 'auriane' ),
				'type' => Controls_Manager::NUMBER,
			]
		);
        $this->add_control(
			'section_pricing_3_price_year',
			[
				'label' => esc_html__( 'Price Year', 'auriane' ),
				'type' => Controls_Manager::NUMBER,
			]
		);
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'section_pricing_3_fr_text', [
				'label' => __( 'Title', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'List Title' , 'auriane' ),
				'label_block' => true,
                'condition' => [
						'section_pricing_3_fr_select' => [ 'text' ],
				],
			]
		);
        $repeater->add_control(
			'section_pricing_3_fr_select',
			[
				'label' => __( 'Chooose Type', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'text',
				'options' => [
					'text'  => __( 'Text', 'auriane' ),
					'check' => __( 'Check', 'auriane' ),
				],
			]
		);
        $repeater->add_control(
			'section_pricing_3_fr_name_mobile', [
				'label' => __( 'Title', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'List Title' , 'auriane' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'list3',
			[
				'label' => __( 'Feature List Value', 'auriane' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'section_pricing_3_fr_text' => __( 'text #1', 'auriane' ),

					],

				],
				'title_field' => '{{{ section_pricing_3_fr_name_mobile }}}',
			]
		);
         $this->add_control(
			'section_pricing_3_buy_name',
			[
				'label' => esc_html__( 'Button Buy Name', 'auriane' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Get Enterprise', 'auriane' ),
			]
		);
        $this->add_control(
			'section_pricing_3_buy_free',
			[
				'label' => __( 'Plan Free', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'auriane' ),
				'label_off' => __( 'No', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
        $this->add_control(
			'section_pricing_3_buy_mon',
			[
				'label' => __( 'Plan add to cart (Plan ID) for month', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' =>  $products_plan_array,
                'condition' => [
						'section_pricing_3_buy_free' => [ 'no' ],
				],
			]
		);    
        $this->add_control(
			'section_pricing_3_buy_year',
			[
				'label' => __( 'Plan add to cart (Plan ID) for year', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' =>  $products_plan_array,
                'condition' => [
						'section_pricing_3_buy_free' => [ 'no' ],
				],
			]
		);

		$this->end_controls_section();

	}


	protected function render() {
		$settings = $this->get_settings_for_display();
        global $jws_option;
        $currency_code = ( isset( $jws_option['cars-currency-symbol'] ) && ! empty( $jws_option['cars-currency-symbol'] ) ) ? $jws_option['cars-currency-symbol'] : '';
		if ( function_exists( 'jws_get_currency_symbols' ) ) {
			$currency_symbol = jws_get_currency_symbols( $currency_code );
		}else {
			$currency_symbol = '$';
		}
        ?>

		<div class="jws-plan-table">
            <div class="row">
               <div class="plan-side">
                   <?php if(!empty($settings['section_pricing_title'])) : ?>
                   
                    <h3 class="jws-plan-table-title"><?php echo esc_html($settings['section_pricing_title']); ?></h3>
                   
                   <?php endif; ?> 
                   <?php if($settings['text-nav-on'] == 'yes') : ?> 
                       <div class="change-plan">
                            <a class="active" href="" data-plan="month"><?php echo esc_html($settings['text-nav-1']); ?></a>
                            <a href="" data-plan="year"><?php echo esc_html($settings['text-nav-2']); ?></a>
                       </div> 
                   <?php endif; ?>
        	       <?php
                        if ( $settings['side_list'] ) {
                			echo '<ul class="ct_ul_ol">';
                			foreach (  $settings['list'] as $item ) {
                				echo '<li class="plan-feature-name">' . $item['side_name'] . '</li>';
                			}
                			echo '</ul>';
                		}
                    ?>
                </div> 
                <div class="plan-content">
                    <div class="row">
                        <?php 
                            for ($i = 1; $i <= 3; $i++) { ?>
                            
                            
                                <div class="col-xl-4 col-lg-4 col-12">
                                    <div class="plan-roles">
                                         <?php
                                            if($settings['section_pricing_'.$i.'_popular'] == 'yes') {
                                               echo '<span class="popular">'.esc_html($settings['section_pricing_'.$i.'_popular_text']).'</span>'; 
                                            }
                                            echo '<span class="roles">'.esc_html($settings['section_pricing_'.$i.'_roles']).'</span>';
                                         ?>
                                    </div>
                                    <div class="plan-item">
                                       
                                       
                                        <h3 class="plan-name">
                                            <?php echo esc_html($settings['section_pricing_'.$i.'_title']); ?>
                                        </h3>
                                        <div class="plan-price">
                                            <?php 
                                  
                                                if($settings['section_pricing_'.$i.'_price_mon'] > 0 || !empty($settings['section_pricing_'.$i.'_price_mon'])) { ?>
                                                    <?php echo esc_html__('from ','auriane'); ?>
                                                    <span class="mon price-select active" data-plan="month"><?php echo '<span>'.esc_html($currency_symbol.$settings['section_pricing_'.$i.'_price_mon']).'</span>'; echo esc_html__('/month ','auriane'); ?></span>
                                                    <span class="year price-select" data-plan="year"><?php echo '<span>'.esc_html($currency_symbol.$settings['section_pricing_'.$i.'_price_year']).'</span>'; echo esc_html__('/month ','auriane'); ?></span>      
                                                <?php }else{
                                                    echo '-';
                                                }
                                            ?>
                                          
                                        </div>
                                        <div class="buy-plan">
                                        <?php
                          
                                          if($settings['section_pricing_'.$i.'_buy_free'] != 'yes') { 

                                            if(!empty($settings['section_pricing_'.$i.'_buy_mon'])) {
                                              ?>
                                                <a class="button-buy-plan month active" data-plan="month" href="<?php echo wc_get_checkout_url().'?add-to-cart='.$settings['section_pricing_'.$i.'_buy_mon'].''; ?>">
                                                    <?php echo esc_html($settings['section_pricing_'.$i.'_buy_name']); ?>  
                                                </a>
                                              <?php  
                                            }
                                            if(!empty($settings['section_pricing_'.$i.'_buy_year'])) { ?>
                                               <a class="button-buy-plan year" data-plan="year" href="<?php echo wc_get_checkout_url().'?add-to-cart='.$settings['section_pricing_'.$i.'_buy_year'].''; ?>">
                                                    <?php echo esc_html($settings['section_pricing_'.$i.'_buy_name']); ?>  
                                               </a>  
                                            <?php }   
                                           }else {  ?>
                                                <a class="button-buy-plan-free active" href="<?php echo get_current_user_id() ? get_author_posts_url( get_current_user_id() ) : '#'; ?>">
                                                    <?php echo esc_html($settings['section_pricing_'.$i.'_buy_name']); ?>  
                                               </a> 
                                          <?php }
                                         ?>
                                         </div>
                                  
                                        
                                           <?php 
                                                if ( $settings['list'.$i.''] ) {
                                        			echo '<ul class="plan-features ct_ul_ol">';
                                        			foreach (  $settings['list'.$i.''] as $fr_item ) {
                                        			     
                                                        if($fr_item['section_pricing_'.$i.'_fr_select'] == 'text') {
                                                          echo '<li class="plan-feature-value"><span class="plan-name-mobile">'.$fr_item['section_pricing_'.$i.'_fr_name_mobile'].'</span>' . $fr_item['section_pricing_'.$i.'_fr_text'] . '</li>';  
                                                        } else {
                                                            echo '<li class="plan-feature-value"><span class="plan-name-mobile">'.$fr_item['section_pricing_'.$i.'_fr_name_mobile'].'</span><i class="fas fa-check"></i></li>';
                                                        }
                                                     
                                        				
                                        			}
                                        			echo '</ul>';
                                        		}
                                           ?>
                                      
                                    </div>
                                
                                </div>
                            
                            
                               
                            <?php }
                        ?>
                    </div>
                </div>
            </div>
		</div>

		<?php
	}

	protected function content_template() {}
}
