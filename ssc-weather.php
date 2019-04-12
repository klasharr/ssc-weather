<?php

namespace SSC_Weather;

/*
 Plugin Name: SSC Weather
 Plugin URI:
 Description:
 Author: Klaus Harris
 Version: 0.1
 Author URI: https://klaus.blog
 Text Domain: ssc-weather
 */


define( 'SSC_WEATHER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( class_exists( 'WP_CLI' ) ) {

	require_once( SSC_WEATHER_PLUGIN_DIR . 'cli/class-command.php' );
	$command = new Command;
	\WP_CLI::add_command( 'ssc-weather', $command );

}