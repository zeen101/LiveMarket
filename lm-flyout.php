<?php 

/**
* Load the base class
*/
class LiveMarket_Flyout {
	
	function __construct()	{
        add_action( 'wp_footer', array( $this, 'display_flyout' ) );
	}

	/**
	 * Kick it off
	 * 
	 */
	public function display_flyout() {

        $settings = get_livemarket_settings();

        if ( $settings['livemarket_flyout'] != 'on' ) {
            return;
        }

        ?>
        <div class="livemarket-flyout-container">
            <div class="livemarket-flyout-header active" style="background: <?php echo $settings['flyout_bg_color']; ?>;">
                <a class="livemarket-flyout-header-link" href="#">
                    <h3 class="livemarket-flyout-teaser"><?php echo esc_attr( $settings['flyout_title'] ); ?></h3>
                </a>
            </div>
            <div class="livemarket-flyout-content">
                <span class="livemarket-flyout-content-close">x</span>
                <h3 style="color: <?php echo $settings['flyout_bg_color']; ?>" class="livemarket-flyout-title"><?php echo esc_html( $settings['flyout_title'] ); ?></h3>
                <div class="livemarket-flyout-text">
                    <p><?php echo wp_kses_post( $settings['flyout_message'] ); ?></p>
                </div>
                <p class="livemarket-flyout-cta">
                    <a class="livemarket-flyout-cta-link" style="background: <?php echo $settings['flyout_bg_color']; ?>; " target="_blank" href="https://my.livemarket.pub/publication/<?php echo $settings['publication_id']; ?>/advertise/"><?php echo esc_attr( $settings['flyout_button_text'] ); ?></a>
                </p>
            </div>
        </div>

        <?php 
        
	}

}

new LiveMarket_Flyout();