<?php 


require get_stylesheet_directory() . '/inc/block-patterns.php';

function dark_realm_theme_enqueue_scripts()
{
    wp_enqueue_script(
        'adbar-js',
        get_template_directory_uri() . '/assets/js/adbar.js',
        array(),
        wp_get_theme()->get('Version'),
        true 
    );

}
add_action('wp_enqueue_scripts', 'dark_realm_theme_enqueue_scripts');

// Add Customizer
require get_stylesheet_directory() . '/inc/customizer.php';


// Upsell in the customizer

if ( class_exists( 'WP_Customize_Section' ) ) {
	class DarkRealm_Upsell_Section extends WP_Customize_Section {
		public $type = 'dark-realm-upsell';
		public $button_text = '';
		public $url = '';
		public $background = '';
		public $text_color = '';
		protected function render() {
			$background = ! empty( $this->background ) ? esc_attr( $this->background ) : 'linear-gradient(90deg,rgb(0,0,0) 0%,rgb(0,0,0) 35%,rgb(0,0,0) 70%,rgb(0,0,0) 100%)
            ';
			$text_color       = ! empty( $this->text_color ) ? esc_attr( $this->text_color ) : '#fff';
			?>
			<li id="accordion-section-<?php echo esc_attr( $this->id ); ?>" class="dark_realm_upsell_section accordion-section control-section control-section-<?php echo esc_attr( $this->id ); ?> cannot-expand">
				<h3 class="accordion-section-title" style="border: 0; color:#fff; background:<?php echo esc_attr( $background ); ?>;">
					<?php echo esc_html( $this->title ); ?>
					<a href="<?php echo esc_url( $this->url ); ?>" class="button button-secondary alignright" target="_blank" style="margin-top: -4px;"><?php echo esc_html( $this->button_text ); ?></a>
				</h3>
			</li>
			<?php
		}
	}
}


// Add Get Started
require get_stylesheet_directory() . '/inc/get-started/get-started.php';



function dark_realm_notice() {
    $user_id = get_current_user_id();
    if ( !get_user_meta( $user_id, 'dark_realm_notice_dismissed' ) ) {
 
        ?>
        <div class="updated notice notice-success is-dismissible notice-get-started-class" data-notice="get-start" style="display: flex-inline;padding: 10px;">
        <h2 style="color: #FFC300"><?php esc_html_e('☆☆☆☆☆', 'dark-realm'); ?><br></h2>
            <p><?php esc_html_e('This is just a sample of what the Dark Realm Template can do, the Premium Version is waiting for you!', 'dark-realm'); ?></p>
            <a style="margin-top: 18px;" class="button button-primary" target="_blank"
               href="<?php echo esc_url('https://preview.realtimethemes.com/dark-realm/'); ?>"><?php esc_html_e('See Premium Version', 'dark-realm') ?></a>
               <a href="?dark-realm-dismissed" style="margin-top: 18px;" class="button button-secondary"><?php esc_html_e('Dismiss', 'dark-realm'); ?></a>
        </div>
        <?php
        }
}
add_action( 'admin_notices', 'dark_realm_notice' );
    
function dark_realm_notice_dismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['dark-realm-dismissed'] ) ) 
        add_user_meta( $user_id, 'dark_realm_notice_dismissed', 'true', true );
}
add_action( 'admin_init', 'dark_realm_notice_dismissed' );

/* Theme credit link */
define('DarkRealm_BUY_NOW',__('https://realtimethemes.com/dark-realm','dark-realm'));
define('DarkRealm_PRO_DEMO',__('https://preview.realtimethemes.com/dark-realm','dark-realm'));
define('DarkRealm_REVIEW',__('https://realtimethemes.com/dark-realm','dark-realm'));
define('DarkRealm_SUPPORT',__('https://realtimethemes.com/','dark-realm'));


?>