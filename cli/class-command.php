<?php

namespace SSC_Weather;
use WP_CLI;

/**
 * Class command
 *
 * https://www.metoffice.gov.uk/datapoint/support/documentation/uk-locations-site-list-detailed-documentation
 * https://www.metoffice.gov.uk/datapoint/product/uk-3hourly-site-specific-forecast/detailed-documentation
 *
 * @package SSC_Weather
 */
class command {
	
	const API_KEY = '';
	const LOCATION = '353786'; // Swanage
	const THREE_HOUR_FORECAST = 'http://datapoint.metoffice.gov.uk/public/data/val/wxfcs/all/json/%d?res=3hourly&key=%s';
	const CACHE_DURATION = 60*5;

	function three_hour_forecast() {

		$data = $this->get_response();
		WP_CLI::log( print_r( json_decode( $data, true ), 1 ) );
	}

	private function get_response() {

		$url = sprintf( self::THREE_HOUR_FORECAST, self::LOCATION, self::API_KEY );
		$hash = md5( $url );

		WP_CLI::log( $url );

		if( $data = get_transient( $hash ) ) {
			WP_CLI::log( 'transient data exists.' );
			return unserialize( $data );
		}

		$response = wp_remote_get( $url, array( 'timeout' => 2 ) );

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
			WP_CLI::log( "no response data" );
		}

	}
	
}