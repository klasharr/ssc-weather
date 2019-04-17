# ssc-weather

This is in development but will be a WordPress plugin to display UK Met Office weather data. I've found the [Met Office](https://www.metoffice.gov.uk/public/weather/wind-map/#?tab=map&map=GustSpeed&zoom=9&lon=-1.94&lat=50.71&fcTime=1541019600) to have the most accurate wind and wind gust predictions from the sources I look at for my area. 

I'm getting it all working in a CLI command and REST API endpoint first, then will add unit tests. The last step will be to make it display on the WordPress website. 

## REST_API Endpoint

View the data from this URL

`/wp-json/ssc-weather/v1/forecast`

## CLI command

Inspect the data from the shell from this command. 

`wp ssc-weather three_hour_forecast`

Only really a simple test which so far produces:

```
2019-04-14

Time                Wind        Gust      Temp        Rain        
Early morning       18mph       22mph     3C          4%          
Morning             18mph       25mph     5C          7%          
Early afternoon     20mph       27mph     7C          5%          
Afternoon           20mph       27mph     7C          4%          
Evening             18mph       25mph     7C          4%          

2019-04-15

Time                Wind        Gust      Temp        Rain        
Early morning       20mph       29mph     8C          4%          
Morning             25mph       36mph     9C          3%          
Early afternoon     25mph       36mph     10C         3%          
Afternoon           27mph       38mph     10C         4%          
Evening             20mph       31mph     10C         8%          
```

## Settings

Met Office API requests needs a [developer API](https://www.metoffice.gov.uk/datapoint/api) key which for now is stored in a transient added from the shell.

`met_office_key`

The location is hardcoded currently in the command class.

`const LOCATION = '353786'; // Swanage`

Locations are retrieved from the locations data endpoint (see below). These settings can be added to an admin page later.


## Met Office API docs

Locations

https://www.metoffice.gov.uk/datapoint/support/documentation/uk-locations-site-list-detailed-documentation

Three hourly forecast

https://www.metoffice.gov.uk/datapoint/product/uk-3hourly-site-specific-forecast/detailed-documentation
