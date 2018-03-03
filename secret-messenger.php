<?php
/*
  Plugin Name: Secret Messenger
  Plugin URI: https://github.com/yoshida-san/secret-messenger
  Description: Add secret message in html source in your website.
  Version: 0.1.1
  Author: Satoshi Yoshida
  Author URI: https://github.com/yoshida-san/secret-messenger
  License: GPLv2 or later
 */
/*  Copyright 2015 Satoshi Yoshida (email : yos.3104@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

class SecretMessengerSettings {

	function __construct() {
		add_action('admin_menu', array($this, 'add_pages'));
	}

	function add_pages() {
		add_submenu_page('plugins.php', __('Secret Messenger', 'secret-messenger'), __('Secret Messenger', 'secret-messenger'), 'level_8', __FILE__, array($this, 'setting_view'));
	}

	function setting_view() {
		$post_settings = filter_input(INPUT_POST, "secret_messenger_settings", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		if (!is_null($post_settings)) {
			check_admin_referer('secret_messenger_settings');
			update_option('secret_messenger_settings', $post_settings);
			?><div class="updated fade"><p><strong><?php _e('Settings saved.', 'secret-messenger'); ?></strong></p></div><?php
		}
		?>
		<div class="bss-admin-settings-wrapper" style="padding: 20px;">
			<h1><?php _e('Secret Messenger Settings', 'secret-messenger'); ?></h1>
			<form action="" method="post" style="padding-left: 10px;">
				<?php
				wp_nonce_field('secret_messenger_settings');
				$settings = get_option('secret_messenger_settings');
				$message = isset($settings['message']) ? esc_html($settings['message']) : 'If you can dream it, you can do it.' . "\n" . 'By Walt Disney.';
				?>

				<h2 style="margin: 20px 0 5px 0;"><?php _e('Secret Message', 'secret-messenger'); ?></h2>
				<textarea name="secret_messenger_settings[message]" placeholder="Input your sercret message." style="width: 100%;height: 400px;"><?php echo $message; ?></textarea>

				<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e('Save', 'secret-messenger'); ?>"></p>
			</form>
			<h2 style="padding-left: 10px;"><?php _e('Support', 'secret-messenger'); ?></h2>
			<p style="padding-left: 10px;">
				<?php _e('Please contact me if you need help.', 'secret-messenger'); ?><br>
				<?php _e('Mail:<a href="mailto:yos.3104@gmail.com">yos.3104@gmail.com</a>', 'secret-messenger'); ?><br>
				<?php _e('Twitter:<a href="https://twitter.com/beek_jp" target="_blank">@beek_jp</a>', 'secret-messenger'); ?><br>
			</p>
		</div>
		<?php
	}

	function echo_secret_message() {
		$ret = PHP_EOL . PHP_EOL . '<!--' . PHP_EOL . PHP_EOL;
		$ret .= $this->get_secret_message();
		$ret .= PHP_EOL . PHP_EOL . '-->' . PHP_EOL . PHP_EOL;
		return $ret;
	}

	private function get_secret_message() {
		$option = get_option('secret_messenger_settings');
		return isset($option['message']) ? $option['message'] : 'If you can dream it, you can do it.' . "\n" . 'By Walt Disney.';
	}

}

$sm_settings = new SecretMessengerSettings();



/* Beek Smooth Scroller Plugin Load */

function load_secret_messenger_textdomain() {
	load_plugin_textdomain('secret-messenger', FALSE, basename(dirname(__FILE__)) . '/languages');
}

add_action('plugins_loaded', 'load_secret_messenger_textdomain');

/* echo scroller */

function echo_secret_message() {
	$sm_settings = new SecretMessengerSettings();
	echo $sm_settings->echo_secret_message();
}

add_action('wp_head', 'echo_secret_message');
