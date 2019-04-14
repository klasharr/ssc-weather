<?php

require_once( SSC_WEATHER_PLUGIN_DIR . 'classes/class-request.php' );

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

	
public function three_hour_forecast(){

	try {


		$o = new Request();

		$tmp = array(
			'06:00' => 'Early morning',
			'09:00' => 'Morning',
			'12:00' => 'Early afternoon',
			'15:00' => 'Afternoon',
			'18:00' => 'Evening',
		);

		$data = $o->three_hour_forecast();

		WP_CLI::log( $data[ 'data' ][ 'location' ] . ' ' . $data[ 'response' ][ 'cache_time' ] );

		foreach ( $data[ 'data' ][ 'forecast' ] as $date => $three_hourly_forecasts ) {

			WP_CLI::log( $date );
			WP_CLI::log( '' );
			WP_CLI::log(

				str_pad( 'Time', 20, " ", STR_PAD_RIGHT ) .
				str_pad( 'Wind', 12, " ", STR_PAD_RIGHT ) .
				str_pad( 'Gust', 12, " ", STR_PAD_RIGHT ) .

				str_pad( 'Temp', 12, " ", STR_PAD_RIGHT ) .
				str_pad( 'Rain', 12, " ", STR_PAD_RIGHT )

			);

			foreach ( $three_hourly_forecasts as $hours => $segment ) {

				if ( in_array( $hours, array( '00:00', '03:00', '21:00' ) ) ) {
					continue;
				}

				WP_CLI::log(
					str_pad( $hours, 20, " ", STR_PAD_RIGHT ) .
					str_pad( $segment['Wind Speed'], 12, " ", STR_PAD_RIGHT ) .
					\cli\Colors::colorize( '%G' . str_pad( $segment['Wind Gust'] . '%n', 12, " ", STR_PAD_RIGHT ) ) .

					str_pad( $segment['Temperature'], 12, " ", STR_PAD_RIGHT ) .
					str_pad( $segment['Precipitation Probability'], 12, " ", STR_PAD_RIGHT )
				);

			}
			WP_CLI::log( '' );
		}
	} catch ( Exception $e ){
		WP_CLI::log( $e->getMessage() );
	}


}
	

	
}