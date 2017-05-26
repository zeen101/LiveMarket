<?php
/**
 * Registers zeen101's Live Market Widgets
 *
 * @package zeen101's Live Market
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
		$dateformat = get_option( 'date_format' );
		
		if ( empty( $settings['api_key'] ) ) {
			return '<h1 class="error">' . __( 'You Must Enter a Valid Live Market API Key in the Live Market Plugin', 'livemarket' ) . '</h1>';
		}

		$advertisements = get_livemarket_advertisements();
		if ( !empty( $advertisements->success ) && !empty( $advertisements->data ) ) {
			$out = '<ul>';
			foreach( $advertisements->data as $advertisement ) {
				if ( get_option( 'permalink_structure' ) ) {
					$link = get_permalink( $settings['livemarket_page'] ) . $advertisement->id;
				} else {
					$link = get_permalink( $settings['livemarket_page'] ) . '?store=' . $advertisement->id;
				}
				$out .= '<li>';
				$out .= '<a href="' . $link . '">' . $advertisement->title . '</a> ';
				$out .= '<span class="livemarket_date">' . date_i18n( $dateformat, strtotime( get_date_from_gmt( $advertisement->created_at ) ) ) . '</span>';
				$out .= '</li>';
			}
			$out .= '</ul>';
		} else {
			$out = '<h1 class="error">' . __( 'Unable to find marketplace stores.', 'livemarket' ) . '</h1>';
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
		
		$instance          = $old_instance;	
		$instance['title'] = $new_instance['title'];
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
			echo '<h1 class="error">' . __( 'You Must Enter a Valid Live Market API Key in the Live Market Plugin', 'livemarket' ) . '</h1>';
		}
        	
		//Defaults
		$defaults = array(
			'title' => 'Live Market',
		);
		
		extract( wp_parse_args( $instance, $defaults ) );
		
		?>
		<p>
        	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'livemarket' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( strip_tags( $title ) ); ?>" />
        </p>
        <?php
	
	}

}