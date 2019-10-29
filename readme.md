## Lead Technical Mentor:SD | Remote Work Assessment

## About this app

This app has been build using Laravel 6.x. The app consumes an api to check student module progress and notifies the student whether he/she is behind or on track

# Server Requirements
* PHP >= 7.2.0
* BCMath PHP Extension
* Ctype PHP Extension
* JSON PHP Extension
* Mbstring PHP Extension
* OpenSSL PHP Extension
* PDO PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension

# Installing The App
The recommended way to install all packages is through Composer.
```
# Install Composer
curl -sS https://getcomposer.org/installer | php
```
Enviroment file ```.env``` contains sensitive config info. A sample copy is stored as ```.env.example```

# App structure and design
Functions that make call to the API and carry out the logic are located are in ```App\Http\Controllers\NotificationController.php```
The following fuctions are contained in this controller
* checkStudentModuleStatus() - Its the entry point for this app. The functions calls other necessary functions to achieve its goals
* getLiveModules() - Gets all live modules
* getProgression($module_id, $student_id) - Gets progress status of student
* notifyStudentsMidModule($student_id,$payload) - Makes the API call that sends the message to notify students mid-module
* checkIfMidDateReached($start_date, $end_date) - Checks if the module has reached mid date

To ensure that the application checks automatically each day if there are any notifications to send, a scheduler is set up
We create a new command class called ```daily``` in the ```app/Console/Commands``` directory and name the scheduled artisan command ```daily:update```
We then instantiate ```NotificationController{}``` class and call the method ```checkStudentModuleStatus()``` in the handle method of class ```daily```.
We then define our scheduled task in the schedule method of the App\Console\Kernel class. and set it to run once daily.

NOTE: When using the scheduler, we only need to add the following Cron entry to your server. ```* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1```(This is set up once for each project irrespective of the number of scheduled jobs)


