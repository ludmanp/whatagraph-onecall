# OneCall Api integration into WhatAgraph

## Setup

To setup system you need to fill .env values for OneCall API key and 
WhataGraph API token
```
OPENWEATHERMAP_API_KEY=
WHATAGRAPH_API_TOKEN=
```

Afterwards metrics for OneCall response data should be initialised with artisan comman

```
php artisan whatagraph:init
```

When metrics are initiated you can run 

## Run data collection

```
php artisan whatagraph:one-call {locations*}
```

`{locations*}` should be replaces with one or more locations, for example

```
php artisan whatagraph:one-call Riga Vilnius Tallinn
```

To make data collection scheduled every day there is two options. 

1. You can schedule cron job with `whatagraph:one-call` command. This approach allow not make changes in codebase. 
2. Or it is possible to register commend in `App\Console\Kernel::schedule` method like 
```
$schedule->command('whatagraph:one-call Riga Vilnius')->dailyAt('12:00')
```

## How it works

### Metrics initialisation

This function checks, if all hardcoded weather metrics are presented in WhataGraph metrics list
by calling `/integration-metrics` method. If there is more, the one page returned, service iteratively 
collects data from all available pages. I assume, that metrics count is not very big, so it is not 
big deal to hold few tens of items in memory. In other case it would be good idea
to process every data page separately.

Afterwards for metrics that already exists in WhataGraph is used update method (`PUT`)
and create method (`POST`) for new ones.

### Data collection

First of all we translate find coordinates for location using Geocoding API. 
If no data found process stops for this location.

Afterwards current weather data are received from One Call API, excluding all 
other unnecessary data.   

If data received timestamp is converted to date string, location name + state (if presented) + country
are converted to string and used as dimension name. We check if dimension `location`
exists and if not, it is created.

And then weather data is pushed to WhataGraph.

### Exception handling

There is no handling of exceptions in the application, on the contrary for all http requests
exception is thrown in case of error. This approach from my opinion is good
in case of that kind of applications, when we should be aware in case of any error.

In more proper solution any alert should be sent in case of error. 
