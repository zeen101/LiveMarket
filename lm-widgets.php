<?php
/**
 * Registers zeen101's LiveMarket Widgets
 *
 * @package zeen101's LiveMarket
 * @since 1.0.0
 */
 
/**
 * Register our widgets classes with WP
 *
 * @since 1.0.0
 */
function register_livemarket_widgets() {
	
	register_widget( 'LiveMarket_Advertisements' );

}
add_action( 'widgets_init', 'register_livemarket_widgets' );

/**
 * This class registers and returns the Cover Image Widget
 *
 * @since 1.0.0
 */
class LiveMarket_Advertisements extends WP_Widget {
	
	/**
	 * Set's widget name and description
	 *
	 * @since 1.0.0
	 */
	function __construct() {
		
		$widget_ops = array( 'classname' => 'livemarket_list_widget', 'description' => __( 'Displays a list of Advertisements associated with your LiveMarket publication', 'issuem' ) );
		parent::__construct( 'LiveMarket_Advertisements', __( 'LiveMarket Advertisements', 'livemarket' ), $widget_ops );
	
	}
	
	/**
	 * Displays the widget on the front end
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {
		
		extract( $args );
		extract( $instance );
	
		$settings = get_livemarket_settings();
		
		if ( empty( $settings['api_key'] ) ) {
			return '<h1 class="error">' . __( 'You Must Enter a Valid LiveMarket API Token in the Live Market Plugin', 'livemarket' ) . '</h1>';
		}
		
		$out = widget_formatted_livemarket_advertisements( 0, $instance['limit'] ); //Page, Limit
		
		if ( !empty( $instance['show_signup'] ) ) {
			$out .= widget_formatted_livemarket_advertisement_signup_link();
		}
		
		if ( ! empty( $out ) ) {
			
			echo $before_widget;
			if ( $title) {
				echo $before_title . $title . $after_title;
			}
			echo '<div class="livemarket_list">';
			echo $out;
			echo '</div>';
			echo $after_widget;	
		
		}
	
	}

	/**
	 * Saves the widgets options on submit
	 *
	 * @since 1.0.0
	 * 
	 * @param array $new_instance
	 * @param array $old_isntance
	 */
	function update( $new_instance, $old_instance ) {
		
		$instance                = $old_instance;	
		$instance['title']       = $new_instance['title'];
		if ( 1 > $new_instance['limit'] ) {
			$new_instance['limit'] = 1;
		} else if ( 50 < $new_instance['limit'] ) {
			$new_instance['limit'] = 50;
		}
		$instance['limit']       = $new_instance['limit'];
		$instance['show_signup'] = (bool)$new_instance['show_signup'];
		return $instance;
		
	}

	/**
	 * Displays the widget options in the dashboard
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance
	 */
	function form( $instance ) {
	
		$settings = get_livemarket_settings();
		
		if ( empty( $settings['api_key'] ) ) {
			echo '<h1 class="error">' . __( 'You Must Enter a Valid LiveMarket API Token in the Live Market Plugin', 'livemarket' ) . '</h1>';
		}
        	
		//Defaults
		$defaults = array(
			'title'       => 'LiveMarket',
			'limit'       => 10,
			'show_signup' => true,
		);
		$instance = wp_parse_args( $instance, $defaults );
		
		?>
		<p>
        	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'livemarket' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( strip_tags( $instance['title'] ) ); ?>" />
        </p>
		<p>
        	<label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e( 'Limit (50 max):', 'livemarket' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo esc_attr( strip_tags( $instance['limit'] ) ); ?>" min="1" max="50" />
        </p>
		<p>
        	<label for="<?php echo $this->get_field_id('show_signup'); ?>"><?php _e( 'Show Signup Link:', 'livemarket' ); ?></label>
            <input id="<?php echo $this->get_field_id('show_signup'); ?>" name="<?php echo $this->get_field_name('show_signup'); ?>" type="checkbox" <?php checked( $instance['show_signup'] ); ?>" />
        </p>
        <?php
	
	}

}
