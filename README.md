# ssc-weather

This is an in development but will be a WordPress plugin to display UK Met Office weather data. I'm getting it all working in a CLI command first, will get unit tests working then the last step will be to make it display on a website.

## CLI command

`wp ssc-weather three_hour_forecast`

Which so far produces:

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



## Met Office API docs

Locations

https://www.metoffice.gov.uk/datapoint/support/documentation/uk-locations-site-list-detailed-documentation

Three hourly forecast

https://www.metoffice.gov.uk/datapoint/product/uk-3hourly-site-specific-forecast/detailed-documentation
