<?php

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

require_once( SSC_WEATHER_PLUGIN_DIR . 'classes/class-request.php' );

if ( class_exists( 'WP_CLI' ) ) {

	require_once( SSC_WEATHER_PLUGIN_DIR . 'cli/class-command.php' );
	$command = new Command;
	WP_CLI::add_command( 'ssc-weather', $command );

}

function ssc_weather_callback_forecast() {

	try{

		$o = new Request;
		$data = $o->three_hour_forecast();
		return rest_ensure_response( $data );

	} catch ( Exception $e ) {

		return new WP_Error( 'sscw-1', esc_html__( 'Error:'. $e->getMessage(), 'scc-weather' ),
			array( 'status' => 500 )
		);
	}

}


function ssc_weather_register_routes() {

	register_rest_route( 'ssc-weather/v1', '/forecast', array(
		'methods'  => WP_REST_Server::READABLE,
		'callback' => 'ssc_weather_callback_forecast',
	) );

}

add_action( 'rest_api_init', 'ssc_weather_register_routes' );