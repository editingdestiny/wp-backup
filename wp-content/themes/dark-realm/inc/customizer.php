<?php 

/**
 * Customizer
 * 
 * @package WordPress
 * @subpackage dark-realm
 * @since dark-realm1.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function dark_realm_customize_register( $wp_customize ) {
	$wp_customize->add_section( new DarkRealm_Upsell_Section($wp_customize,'upsell_section',array(
		'title'            => __( 'Dark Realm Pro', 'dark-realm' ),
		'button_text'      => __( 'Upgrade Pro', 'dark-realm' ),
		'url' 			   => esc_url( DarkRealm_BUY_NOW ),
		'priority'         => 0,
	)));
}
add_action( 'customize_register', 'dark_realm_customize_register' );

/**
 * Enqueue script for custom customize control.
 */
function dark_realm_custom_control_scripts() {
	wp_enqueue_script( 'dark-realm-custom-controls-js', get_template_directory_uri() . '/assets/js/custom-controls.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ), '1.0', true );
}
add_action( 'customize_controls_enqueue_scripts', 'dark_realm_custom_control_scripts' );
?>