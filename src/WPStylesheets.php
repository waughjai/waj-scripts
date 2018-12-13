<?php

declare( strict_types = 1 );
namespace WaughJ\WPScripts
{
	use WaughJ\FileLoader\FileLoader;
	use WaughJ\WPMetaBox\WPMetaBox;

	class WPStylesheets
	{
		public static function init() : void
		{
			self::$sheet_manager = new WPSheetManager
			(
				new FileLoader
					([
						'directory-url' => get_stylesheet_directory_uri(),
						'directory-server' => get_stylesheet_directory(),
						'shared-directory' => 'css',
						'extension' => 'css'
					]),
				'wp_enqueue_style',
				new WPMetaBox
					(
						'page-css',
						'Page Stylesheets'
					)
			);

			add_action
			(
				'admin_menu',
				function()
				{
					add_theme_page
					(
						__( 'Directories', 'textdomain' ),
						__( 'Directories', 'textdomain' ),
						'manage_options',
						'theme_directories',
						function()
						{
							echo '<h1>Directories</h1>';
						}
					);

					add_settings_section
					(
						'main_stylesheet',
						__( 'Main Stylesheet', 'textdomain' ),
						function()
						{
						},
						'waj_design'
					);

					register_setting
					(
						'waj_design',
						'main_stylesheet',
						[
							'type' => 'string',
							'sanitize_callback' => 'sanitize_html_class',
							'default' => null
						]
					);

					add_settings_field
					(
						'main_stylesheet',
						__( 'Main Stylesheet', 'textdomain' ),
						function()
						{
							?><input type="text" name="main_stylesheet" id="main_stylesheet" placeholder="Main Stylesheet" value="<?= get_option( 'main_stylesheet', '' ); ?>" /><?php
						},
						'waj_design',
						'main_stylesheet',
						[
							'label_for' => 'main_stylesheet'
						]
					);
				}
			);
		}

		public static function register( string $name ) : void
		{
			self::$sheet_manager->register( $name, 'wp_enqueue_scripts' );
		}

		private static $sheet_manager;
	}
}
