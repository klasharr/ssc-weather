<?php

require_once( SSC_WEATHER_PLUGIN_DIR . 'classes/class-weather-object.php' );

/**
 * Class command
 *
 * https://www.metoffice.gov.uk/datapoint/support/documentation/uk-locations-site-list-detailed-documentation
 * https://www.metoffice.gov.uk/datapoint/product/uk-3hourly-site-specific-forecast/detailed-documentation
 *
 * @package SSC_Weather
 */
class command {
	
	// const API_KEY = ''; using an option instead currently I don't want to accidentally commit
	// my API_KEY.

	const LOCATION = '353786'; // Swanage
	const THREE_HOUR_FORECAST = 'http://datapoint.metoffice.gov.uk/public/data/val/wxfcs/all/json/%d?res=3hourly&key=%s';
	const CACHE_DURATION = 60*5;
	const TIMEOUT = 2;

	function three_hour_forecast() {

		try {

			$raw_data = json_decode( $this->get_response(), true );

			$o = new Weather_Object();
			$o->init( $raw_data );

//			WP_CLI::log( print_r( $o->get_forecast(), 1 ) );

			$tmp = array(
				'06:00' => 'Early morning',
				'09:00' => 'Morning',
				'12:00' => 'Early afternoon',
				'15:00' => 'Afternoon',
				'18:00' => 'Evening',
			);


			foreach( $o->get_forecast() as $date => $three_hourly_forecasts ) {

				WP_CLI::log( $date );
				WP_CLI::log( '' );

				WP_CLI::log(

					str_pad('Time',20," ", STR_PAD_RIGHT ) .
					str_pad('Wind',12," ", STR_PAD_RIGHT ) .
					str_pad('Gust',12," ", STR_PAD_RIGHT ) .

					str_pad('Temp',12," ", STR_PAD_RIGHT ) .
					str_pad('Rain',12," ", STR_PAD_RIGHT )

				);

				foreach( $three_hourly_forecasts as $hours => $segment ) {

					if( in_array( $hours, array( '00:00', '03:00', '21:00' ) ) ) continue;

					WP_CLI::log(
						str_pad($tmp[ $hours ],20," ", STR_PAD_RIGHT ) .
						str_pad($segment[ 'Wind Speed' ],12," ", STR_PAD_RIGHT ) .
						\cli\Colors::colorize( '%G'. str_pad($segment[ 'Wind Gust' ].'%n' ,12," ", STR_PAD_RIGHT ) ) .

						str_pad($segment[ 'Temperature' ],12," ", STR_PAD_RIGHT ) .
						str_pad($segment[ 'Precipitation Probability' ],12," ", STR_PAD_RIGHT )
					);

				}
				WP_CLI::log( '' );
			}

		} catch ( Exception $e ) {

			WP_CLI::log( $e->getMessage() );

		}
	}

	private function get_response() {

		$url = sprintf( self::THREE_HOUR_FORECAST, self::LOCATION, get_option( 'met_office_key' ) );
		$hash = md5( $url );

		if( $data = get_transient( $hash ) ) {
			WP_CLI::log( 'transient data exists.' );
			return unserialize( $data );
		}

		$response = wp_remote_get( $url, array( 'timeout' => self::TIMEOUT ) );

		if ( is_array( $response ) ) {

			$header = $response['headers'];
			$body = $response['body'];
			$status = $response['response']['code'];

			WP_CLI::log( $status );

			if( !empty( $body )  ){
				set_transient( $hash, serialize( $body ), self::CACHE_DURATION );
				WP_CLI::log( "Data exists" );
				return $body;
			}
			WP_CLI::log( "Data ! exists" );

		} else {
			WP_CLI::log( "No response." );
		}

	}
	
}