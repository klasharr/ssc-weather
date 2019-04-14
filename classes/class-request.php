<?php

require_once( SSC_WEATHER_PLUGIN_DIR . 'classes/class-weather-object.php' );

class Request {

	const LOCATION = '353786'; // Swanage
	const THREE_HOUR_FORECAST = 'http://datapoint.metoffice.gov.uk/public/data/val/wxfcs/all/json/%d?res=3hourly&key=%s';
	const CACHE_DURATION = 60 * 30;
	const TIMEOUT = 2;

	public function three_hour_forecast() {

		$raw_data = $this->get_response();
		$o = new Weather_Object();
		$o->init( $raw_data );

		return array(
			'data' => array(
				'forecast' => $o->get_forecast_data(),
				'location' => $o->get_location_value( 'name')
			),
			'response' => array(
				'duration' => $raw_data[ 'response_duration' ],
				'cache_time' => date( 'Y-m-d H:i:s', $raw_data[ 'timestamp' ] ),
			),
		);
	}



	/**
	 * @return mixed
	 * @throws Exception
	 */
	private function get_response() {

		$url = sprintf( self::THREE_HOUR_FORECAST, self::LOCATION, get_option( 'met_office_key' ) );
		$hash = md5( $url );

		if( $data = get_transient( $hash ) ) {
			return $data;
		}

		$t1 = microtime( true );
		$response = wp_remote_get( $url, array( 'timeout' => self::TIMEOUT ) );
		$t2 = microtime( true );
		$request_duration = $t2 - $t1;

		if ( is_array( $response ) ) {

			if( empty( $response['response']['code'] ) ) {
				throw new Exception( 'Invalid response, no error code' );
			}

			if( !in_array( $response['response']['code'], array( 200 ) ) ) {

				throw new Exception(
					sprintf( 'Response code: %d. Message: %s. URL: %s',
						$response['response']['code'],
						$response['response']['message'],
						$url
					)
				);
			}

			$header = $response['headers'];
			$body   = $response['body'];
			$status = $response['response']['code'];

			if( empty( $body ) ) {
				throw new Exception( 'No data in response body.' );
			}

			$data = array(
				'data' => $body,
				'timestamp' => time(),
				'response_duration' => $request_duration,
			);

			set_transient( $hash, $data, self::CACHE_DURATION );
			return $data;

		} elseif( $response instanceof WP_Error ) {
			throw new Exception( $response->get_error_message() );
		}

	}



}