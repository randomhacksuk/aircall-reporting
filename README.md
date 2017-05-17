# Aircall Report

A reporting tool to help analysis your Aircall call data and understand when calls come and what happens to them.  It uses the Aircall API (https://developer.aircall.io/).  The data is retreived the AirCall API and stored in a MySQL database.  You can see the analysis of the calls from the reporting screen.

### Development

Want to contribute? Great!

Open your favorite Terminal and run these command from the root of the project.
```sh
$ composer install
```

Once database is created, run the following command to generate databse tables(database name can be edited from .env file within root of the project)
```sh
$ php artisan migrate
```

To set app id and app key of your aircall account change it from .env file.

The are several scheduled tasks, which will update database if you configure it clear.

The crontab file can be reached by running this command in Terminal

```sh
$ crontab -e
```

This line need to be added to crontab file:


```sh
* * * * * php /path/to/your/project/artisan schedule:run >> /dev/null 2>&1
```

Visit {domain name/ip address}/get-old-data to store old data from aircall to our database.

After that database will be automatically updated according to aircall account users activity by frequency, which set in crontab file.



