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

        ?>
        <div class="livemarket-flyout-container">
            <div class="livemarket-flyout-header active">
                <a class="livemarket-flyout-header-link" href="#">
                    <h3 class="livemarket-flyout-teaser">Get free promotions for your business... for a week!</h3>
                </a>
            </div>
            <div class="livemarket-flyout-content">
                <span class="livemarket-flyout-content-close">x</span>
                <h3 class="livemarket-flyout-title">Get free promotions for your business... for a week!</h3>
                <div class="livemarket-flyout-text">
                    <p>For a limited time we are giving away a week of <strong>unlimited free promotions</strong> for your organization. Our new LiveMarket Special Offers, Dining Deals, & Events reach over 30,000 local readers. Drop your email off below and we will send you what you need to get your promotions live... for free.</p>
                </div>
                <p class="livemarket-flyout-cta">
                    <a class="livemarket-flyout-cta-link" href="#">Let's Get Your Promo Going!</a>
                </p>
            </div>
        </div>

        <style>
            .livemarket-flyout-container {
                position: fixed;
                top: 10%;
                width: 380px;
                z-index: 10000;
                background: #fff;
                box-shadow: 0px 10px 28px rgba(0,0,0,0.36);
                border-radius: 8px 0 0 8px;
                right: 0;
                height: auto;
            }
            .livemarket-flyout-header.active {
                display: block;
            }
            .livemarket-flyout-header {
                display: none;
                background: #7AB872;
                position: fixed;
                z-index: 10000;
                text-align: left;
                border-radius: 8px 8px 0 0;
                box-shadow: 0px 10px 28px rgba(0,0,0,0.36);
                width: 340px;
                top: 10%;
                height: auto;
                transform: rotate(-90deg) !important;
                transform-origin: right center;
            }
            .livemarket-flyout-header a {
                text-decoration: none;
                font-size: 16px;
                padding: 20px;
                display: block;
            }
            .livemarket-flyout-teaser {
                color: #fff;
                line-height: 1.2;
               
            }
            .livemarket-flyout-content {
                padding: 20px;
                display: none;
            }
            .livemarket-flyout-content.active {
                display: block;
            }
            .livemarket-flyout-title {
                font-size: 22px;
                color: #7AB872;
                margin-bottom: 20px;
            }
            .livemarket-flyout-text {
                margin-bottom: 20px;
            }
            .livemarket-flyout-text p {
                font-size: 14px;
                line-height: 1.2;
            }
            .livemarket-flyout-cta .livemarket-flyout-cta-link {
                background: #7AB872;
                padding: 10px 15px;
                color: #fff;
                text-transform: uppercase;
                letter-spacing: 1px;
                font-size: 14px;
                text-decoration: none;
                border-radius: 3px;
                display: inline-block;
            }
            .livemarket-flyout-cta .livemarket-flyout-cta-link:hover {
                text-decoration: underline;
            }
            .livemarket-flyout-content-close {
                position: absolute;
                top: 12px;
                right: 12px;
                color: #666;
                font-weight: bold;
                font-size: 22px;
                cursor: pointer;
            }
        </style>
        <?php 
        

	}

}

new LiveMarket_Flyout();