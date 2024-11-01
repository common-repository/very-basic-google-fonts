<?php
/**
* Plugin Name: Very Basic Google Fonts
* Description: Loads Google Fonts.
* Version: 1.0
* Author: Banana Bread
* Author URI: https://profiles.wordpress.org/bananabread
* License: GPL12
*/

function google_fonts_register_plugin_settings() {
	register_setting( 'google_fonts_options', 'google_fonts_fonts' );
}

add_action( 'admin_init', 'google_fonts_register_plugin_settings' );

function google_fonts_load() {
  $google_fonts_fonts = get_option('google_fonts_fonts');
	$google_fonts_fonts_array = explode("\n", $google_fonts_fonts);
  $google_fonts_path = "";
  foreach ($google_fonts_fonts_array as $google_fonts_font) {
    if ($google_fonts_path !== "") {
      $google_fonts_font = str_replace(" ","+", $google_fonts_font);
      $google_fonts_path = $google_fonts_path . "%7c" . $google_fonts_font;
    } else {
      $google_fonts_path = $google_fonts_font;
    }
  }
  wp_register_style('google_fonts', 'https://fonts.googleapis.com/css?family=' . $google_fonts_path);
  wp_enqueue_style('google_fonts');
}
add_action( 'wp_enqueue_scripts', 'google_fonts_load' );

function google_fonts_options_page_html() {
  if (!current_user_can('manage_options')) {
    return;
  }
  ?>
  <div class="wrap">
    <h1>Google Fonts Loader</h1>
    <h2>Very simple loader of specific Google Fonts</h2>
  <form method="post" action="options.php">
     <?php
    settings_fields( 'google_fonts_options' );
    do_settings_sections( 'google_fonts_options' );
    ?>
    <label for="google_fonts_fonts"><b>Fonts to load, one name per line</b></label>
    <br/>
    <textarea name="google_fonts_fonts" id="google_fonts_fonts" rows="10" cols="20"><?php echo esc_attr(get_option('google_fonts_fonts')); ?></textarea>
    <?php submit_button(); ?>
  </form>

  <?php
}

function google_fonts_options_page() {
  add_submenu_page(
    'options-general.php', 
    'Google Fonts', 
    'Google Fonts', 
    'manage_options', 
    'google_fonts', 
    'google_fonts_options_page_html'
  ); 
}

add_action('admin_menu', 'google_fonts_options_page');
