# Aircall Report

A reporting tool to help analysis your Aircall call data and understand when calls come and what happens to them.  It uses the Aircall API (https://developer.aircall.io/).  The data is retreived the AirCall API and stored in a MySQL database.  You can see the analysis of the calls from the reporting screen.

### Development

Want to contribute? Great!

Open your favorite Terminal and run these commands from the root of the project.
```sh
$ composer install
```

Once database is created, run the following command to generate databse tables(database name can be edited from .env file within root of the project)
```sh
$ php artisan migrate
```

To set app id and app key of your aircall account edit second parameters for "air_call_id" and "air_call_key" or change it directly from .env file.

Visit {domain name/ip address}/get-old-data to store old data from aircall to our database.

After that database will be automatically updated every day according to aircall account


### Todos

 - Write MOAR Tests
 - Add Night Mode

License
----

MIT

