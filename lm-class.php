<?php
/**
 * Registers zeen101's LiveMarket class
 *
 * @package zeen101's LiveMarket
 * @since 1.0.0
 */

/**
 * This class registers the main issuem functionality
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'LiveMarket' ) ) {
	
	class LiveMarket {
		
		/**
		 * Class constructor, puts things in motion
		 *
		 * @since 1.0.0
		 */
		function __construct() {
		
			$settings = $this->get_settings();
			
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_wp_enqueue_scripts' ) );
			add_action( 'admin_print_styles', array( $this, 'admin_wp_print_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 15 );
					
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );

			add_action( 'wp', array( $this, 'process_requests' ) );
			
			if ( !empty( $settings['livemarket_page'] ) ) {
				add_action( 'wp_loaded',array( $this, 'wp_loaded' ) );
				add_filter( 'query_vars', array( $this, 'query_vars' ) );
				add_filter( 'rewrite_rules_array', array( $this, 'rewrite_rules_array' ) );
			}

		}
		
		function wp_loaded() {
			$settings = $this->get_settings();
			$rules = get_option( 'rewrite_rules' );
			$post = get_post( $settings['livemarket_page'] );
			if ( ! isset( $rules['(' . $post->post_name . ')/(.*)$'] ) 
				 || ! isset( $rules['(' . $post->post_name . ')/category/(.*)$'] ) 
				 || ! isset( $rules['(' . $post->post_name . ')/contributor/(.*)$'] ) ) {
				global $wp_rewrite;
			   	$wp_rewrite->flush_rules();
			}
		}
		
		function query_vars( $vars ) {
		    array_push( $vars, 'offer' );
		    array_push( $vars, 'category' );
		    array_push( $vars, 'contributor' );
		    return $vars;
		}
		
		function rewrite_rules_array( $rules ) {
			$settings = $this->get_settings();
			$newrules = array();
			$post = get_post( $settings['livemarket_page'] );
			$newrules['(' . $post->post_name . ')/category/(.*)$']    = 'index.php?pagename=$matches[1]&category=$matches[2]';
			$newrules['(' . $post->post_name . ')/contributor/(.*)$'] = 'index.php?pagename=$matches[1]&contributor=$matches[2]';
			$newrules['(' . $post->post_name . ')/(.*)$']             = 'index.php?pagename=$matches[1]&offer=$matches[2]';
			return $newrules + $rules;
		}
		
		/**
		 * Initialize pigeonpack Admin Menu
		 *
		 * @since 1.0.0
		 * @uses add_menu_page() Creates Pigeon Pack menu
		 * @uses add_submenu_page() Creates Settings submenu to Pigeon Pack menu
		 * @uses add_submenu_page() Creates Help submenu to Pigeon Pack menu
		 * @uses do_action() To call 'pigeonpack_admin_menu' for future addons
		 */
		function admin_menu() {
					
			add_menu_page( __( 'LiveMarket', 'livemarket' ), __( 'LiveMarket', 'livemarket' ), apply_filters( 'manage_livemarket_settings', 'manage_options' ), 'livemarket', array( $this, 'settings_page' ), LIVEMARKET_URL . '' );
			
			add_submenu_page( 'livemarket', __( 'Settings', 'livemarket' ), __( 'Settings', 'livemarket' ), apply_filters( 'manage_livemarket_settings', 'manage_options' ), 'livemarket', array( $this, 'settings_page' ) );			
		}
		
		function process_requests() {
				
			$settings = $this->get_settings();

			do_action( 'livemarket_before_process_requests', $settings );
			
		}
		
		/**
		 * Prints backend LiveMarket styles
		 *
		 * @since 1.0.0
		 */
		function admin_wp_print_styles() {
			
			wp_enqueue_style( 'livemarket_admin_style', LIVEMARKET_URL . 'css/admin.css', '', LIVEMARKET_VERSION );

		}
	
		/**
		 * Enqueues backend LiveMarket styles
		 *
		 * @since 1.0.0
		 */
		function admin_wp_enqueue_scripts( $hook_suffix ) {
			
			if ( 'toplevel_page_livemarket' === $hook_suffix ) {
				wp_enqueue_script( 'livemarket_admin_js', LIVEMARKET_URL . 'js/admin.js', array( 'jquery', 'wp-color-picker' ), LIVEMARKET_VERSION, true );
				wp_enqueue_style( 'wp-color-picker' ); 
			}
						
		}
		
		/**
		 * Enqueues frontend scripts and styles
		 *
		 * @since 1.0.0
		 */
		function frontend_scripts() {
			
			wp_enqueue_style( 'livemarket', LIVEMARKET_URL . 'css/livemarket.css', '', LIVEMARKET_VERSION );
			wp_enqueue_script( 'livemarket', LIVEMARKET_URL . 'js/livemarket.js', array( 'jquery' ), LIVEMARKET_VERSION );
			wp_localize_script( 'livemarket', 'livemarket_ajax', 
				array( 
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'security' => wp_create_nonce( 'livemarket-nonce' ),
				) 
			);
			
		}
		
		/**
		 * Get zeen101's LiveMarket options
		 *
		 * @since 1.0.0
		 */
		function get_settings() {
			
			$defaults = array( 
				'api_key' => '',
				'livemarket_page' => 0,
				'publication_id' => 0,
				'link_color' => '#19236E',
				'button_bg_color' => '#7AB872',
				'flyout_bg_color' => '#7AB872',
				'mobile_promotion' => 'on',
				'livemarket_flyout' => 'on',
				'flyout_title'	=> 'Get free promotions for your business!',
				'flyout_message'	=> 'For a limited time we are giving away a week of unlimited free promotions for your organization. Our new LiveMarket Special Offers, Dining Deals, & Events reach over 30,000 local readers. Click below to get started.',
				'flyout_button_text'	=> 'Get Free Promo'
			);
		
			$settings = get_option( 'livemarket' );
			$settings = wp_parse_args( $settings, $defaults );

			return apply_filters( 'livemarket_get_settings', $settings );
			
		}
		
		/**
		 * Update zeen101's LiveMarket options
		 *
		 * @since 1.0.0
		 */
		function update_settings( $settings ) {

			$settings = apply_filters( 'livemarket_update_settings', $settings );
			return update_option( 'livemarket', $settings );

		}
		
		/**
		 * Create and Display LiveMarket settings page
		 *
		 * @since 1.0.0
		 */
		function settings_page() {
			
			// Get the user options
			$settings = $this->get_settings();
			$settings_saved = false;

			if ( !empty( $_REQUEST['update_livemarket_settings'] ) ) {

				if ( !empty( $_REQUEST['api_key'] ) ) {
					$settings['api_key'] = trim( $_REQUEST['api_key'] );
				} else {
					$settings['api_key'] = '';
				}

				if ( !empty( $_REQUEST['livemarket_page'] ) ) {
					$settings['livemarket_page'] = trim( $_REQUEST['livemarket_page'] );
				} else {
					$settings['livemarket_page'] = 0;
				}

				if ( !empty( $_REQUEST['publication_id'] ) ) {
					$settings['publication_id'] = trim( $_REQUEST['publication_id'] );
				} else {
					$settings['publication_id'] = 0;
				}

				if ( isset( $_POST['mobile_promotion'] ) ) {
					$settings['mobile_promotion'] = 'on';
				} else {
					$settings['mobile_promotion'] = '';
				}

				if ( isset( $_POST['livemarket_flyout'] ) ) {
					$settings['livemarket_flyout'] = 'on';
				} else {
					$settings['livemarket_flyout'] = '';
				}

				if ( isset( $_POST['flyout_title'] ) ) {
					$settings['flyout_title'] = wp_unslash( sanitize_text_field( $_POST['flyout_title'] ) );
				}

				if ( isset( $_POST['flyout_message'] ) ) {
					$settings['flyout_message'] = wp_kses_post( $_POST['flyout_message'] );
				}

				if ( isset( $_POST['flyout_button_text'] ) ) {
					$settings['flyout_button_text'] = wp_unslash( sanitize_text_field( $_POST['flyout_button_text'] ) );
				}

				// Validate Link Color
				$link_color = trim( $_POST['link_color'] );
				$link_color = strip_tags( stripslashes( $link_color ) );
				 
				// Check if is a valid hex color
				if( FALSE === $this->check_color( $link_color ) ) {
					
					// Get the previous valid value
					$settings['link_color'] = $settings['link_color'];
				 
				} else {
				 
					$settings['link_color'] = $link_color;  
				 
				}

				// Validate Link Color
				$button_bg_color = trim( $_POST['button_bg_color'] );
				$button_bg_color = strip_tags( stripslashes( $button_bg_color ) );
				 
				// Check if is a valid hex color
				if( FALSE === $this->check_color( $button_bg_color ) ) {
					
					// Get the previous valid value
					$settings['button_bg_color'] = $settings['button_bg_color'];
				 
				} else {
				 
					$settings['button_bg_color'] = $button_bg_color;
				 
				}

				// Validate Link Color
				$flyout_bg_color = trim( $_POST['flyout_bg_color'] );
				$flyout_bg_color = strip_tags( stripslashes( $flyout_bg_color ) );
				 
				// Check if is a valid hex color
				if( FALSE === $this->check_color( $flyout_bg_color ) ) {
					
					// Get the previous valid value
					$settings['flyout_bg_color'] = $settings['flyout_bg_color'];
				 
				} else {
				 
					$settings['flyout_bg_color'] = $flyout_bg_color;
				 
				}
				
				$settings_saved = $this->update_settings( $settings );
				
			}
			
			if ( $settings_saved ) {
				
				// update settings notification ?>
				<div class="updated"><p><strong><?php _e( "Settings Updated", 'livemarket' );?></strong></p></div>
				<?php
				
			}
			
			// Display HTML form for the options below
			?>

			<div class="livemarket-settings-header">
				<img src="<?php echo esc_url( LIVEMARKET_URL ); ?>/images/livemarket-logo.png" alt="LiveMarket">
			</div>
			<div class=wrap>
            <div style="width:70%;" class="postbox-container">
            <div class="metabox-holder">	
            <div class="meta-box-sortables ui-sortable">
            
                <form id="issuem" method="post" action="">

					<?php do_action('livemarket_before_settings' ); ?>
                    
                    <div id="modules" class="postbox">
                    
                        <div class="handlediv" title="Click to toggle"><br /></div>
                        
                        <h3 class="hndle"><span><?php _e( 'General Settings', 'livemarket' ); ?></span></h3>
                        
                        <div class="inside">
                        
                        <table id="livemarket_administrator_options" class="form-table">
                        
                        	<tr>
                                <th><?php _e( 'API Token', 'livemarket' ); ?></th>
                                <td>
	                                <input type="text" id="api_key" class="large-text" name="api_key" value="<?php echo htmlspecialchars( stripcslashes( $settings['api_key'] ) ); ?>" />
	                                <p class="description"><?php printf( __( 'Register or sign into <a target="_blank" href="https://my.livemarket.pub">LiveMarket</a> to setup your first Publication and get your API key.', 'livemarket' ), '[livemarket]' ); ?></p>
                                </td>
                            </tr>
                            
                            <?php
	                        if ( !empty( $settings['api_key'] ) ) {
		                        $publications = get_livemarket_publications();
		                        if ( empty( $settings['publication_id'] ) && !empty( $publications->data ) ) {
			                        $settings['publication_id'] = $publications->data[0]->id;
		                        }
		                        ?>
	                            
	                        	<tr>
	                                <th><?php _e( 'LiveMarket Page', 'livemarket' ); ?></th>
	                                <td>
									<?php echo wp_dropdown_pages( array( 'name' => 'livemarket_page', 'echo' => 0, 'show_option_none' => __( '&mdash; Select &mdash;' ), 'option_none_value' => '0', 'selected' => $settings['livemarket_page'] ) ); ?>
	                                <p class="description"><?php printf( __( 'Add this shortcode to your LiveMarket page: %s', 'livemarket' ), '[livemarket]' ); ?></p>
	                                </td>
	                            </tr>
	                            
	                        	<tr>
	                                <th><?php _e( 'LiveMarket Publication', 'livemarket' ); ?></th>
	                                <td>
	                                <?php 
		                                if ( !empty( $publications->data ) ) {
			                                echo '<select name="publication_id" id="publication_id">';
							echo '<option value="0" ' . selected( $settings['publication_id'], 0, true ) . '>' . __( 'Select Your Publication', 'livemarket' ) . '</option>';
			                                foreach( $publications->data as $publication ) {
				                                echo '<option value="' . $publication->id . '" ' . selected( $settings['publication_id'], $publication->id, true ) . '>' . $publication->name . '</option>';
			                                }
			                                echo '</select>';
		                                } else {
			                                echo '<p>' . __( 'Please sign into <a href="http://my.livemarket.pub">LiveMarket</a> to setup your first Publication.', 'livemarket' );
		                                }
		                            ?>
	                                </td>
	                            </tr>


								<tr>
	                                <th><?php _e( 'Link Color', 'livemarket' ); ?></th>
	                                <td>
										<input type="text" class="widefat color-field" name="link_color" value="<?php echo esc_attr( $settings['link_color'] ); ?>">
									
	                                <p class="description"><?php _e( 'The link color for the LiveMarket widget and shortcode.', 'livemarket' ); ?></p>
	                                </td>
								</tr>

								<tr>
	                                <th><?php _e( 'Button Background Color', 'livemarket' ); ?></th>
	                                <td>
										<input type="text" class="widefat color-field" name="button_bg_color" value="<?php echo esc_attr( $settings['button_bg_color'] ); ?>">
									
	                                <p class="description"><?php _e( 'The button background color for the LiveMarket widget and shortcode.', 'livemarket' ); ?></p>
	                                </td>
								</tr>
								
								<tr>
									<th><?php _e( 'Mobile Promotion', 'livemarket' ); ?></th>
									<td>
										<p class="description"><input type="checkbox" id="mobile_promotion" <?php checked( $settings['mobile_promotion'], 'on' ); ?> name="mobile_promotion"  value="<?php echo $settings['mobile_promotion'] ? 'on' : ''; ?>" />
										<?php _e( 'On mobile devices, display a popup at the bottom of the page with the latest LiveMarket promotion.'); ?></p>
									</td>
								</tr>

								<tr>
									<th><?php _e( 'LiveMarket Flyout', 'livemarket' ); ?></th>
									<td>
										<p class="description"><input type="checkbox" id="livemarket_flyout" <?php checked( $settings['livemarket_flyout'], 'on' ); ?> name="livemarket_flyout"  value="<?php echo $settings['livemarket_flyout'] ? 'on' : ''; ?>" />
										<?php _e( 'Display a flyout encouraging promoters to sign up for LiveMarket.'); ?></p>
									</td>
								</tr>

								<tr>
	                                <th><?php _e( 'Flyout Background Color', 'livemarket' ); ?></th>
	                                <td>
										<input type="text" class="widefat color-field" name="flyout_bg_color" value="<?php echo esc_attr( $settings['flyout_bg_color'] ); ?>">
									
	                                <p class="description"><?php _e( 'The background color for the LiveMarket flyout.', 'livemarket' ); ?></p>
	                                </td>
								</tr>

								<tr>
									<th><?php _e( 'Flyout Title', 'livemarket' ); ?></th>
									<td>
										<input type="text" id="flyout_title" class="large-text" name="flyout_title" value="<?php echo esc_attr( $settings['flyout_title'] ); ?>" />
										<p class="description"></p>
									</td>
								</tr>

								<tr>
									<th><?php _e( 'Flyout Message', 'livemarket' ); ?></th>
									<td>
										<textarea id="flyout_message" rows="5" class="large-text" name="flyout_message"><?php echo esc_textarea( $settings['flyout_message'] ); ?></textarea>
										
									</td>
								</tr>

								<tr>
									<th><?php _e( 'Flyout Button Text', 'livemarket' ); ?></th>
									<td>
										<input type="text" id="flyout_button_text" class="large-text" name="flyout_button_text" value="<?php echo esc_html( $settings['flyout_button_text'] ); ?>" />
										<p class="description"></p>
										
									</td>
								</tr>
	                            
	                            <?php
	                        }
	                            
							wp_nonce_field( 'livemarket_settings', 'livemarket_settings_nonce' ); ?>

                        </table>
	                        
	                    <p class="submit">
                            <input class="button-primary" type="submit" name="update_livemarket_settings" value="<?php _e( 'Save Settings', 'livemarket' ) ?>" />
                        </p>
  
                        </div>
                        
                    </div>

                    <?php do_action('livemarket_after_settings'); ?>
	                    
                </form>
                
            </div>

			</div>
			
						</div>

						</div>

			<?php
			
		}

		/**
		 * Function that will check if value is a valid HEX color.
		 */
		public function check_color( $value ) { 
			
			if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #     
				return true;
			}
			
			return false;
		}
		
	}
	
}
