# MyMessage

## About the project
This is a CRUD project regarding messages with e-mail notifications, created with Laravel and Vue.js.

## How to run the project in your machine
It's required to have [git](https://git-scm.com/downloads), [composer](https://getcomposer.org/download/) and [npm](https://nodejs.org/en/download/) installed to follow these steps:
1. Clone this repository with ```git clone https://github.com/Romanti-Ezer/MyMessage.git```
2. Enter in the folder of the project ```cd MyMessage```
3. Install dependencies and generate build
    * Install composer dependencies with ```composer install```
    * Install npm dependencias and generate build with ```npm install && npm run dev```
4. Create a database
    * You can give any name you want
    * Database collation can be ```latin1_swedish_ci```
5. Configure ```.env``` file
    * Copy the ```.env.example``` and rename to ```.env```
    * In this file configure the database connection
    * Configure SMTP to send notifications. I recommend [mailtrap](https://mailtrap.io/) for testing without problems
6. Generate an application key running ```php artisan key:generate```
7. Migrate database
    * Run ```php artisan migrate``` to create the tables in the database
8. Configure task manager to run Laravel Schedule
    * If using crontab, you can execute ```0 0 * * * /path/to/project/artisan schedule:run >/dev/null 2>&1``` to run Laravel Schedule every day
    * if you want to test this task in the moment, change App/Console/Kernel.php to ```everyMinute()``` in line 30, and run ```php artisan schedule:run``` to execute the task and verify the messages
    * To send a message, don't forget to create a message with valid start and expiration dates
    * The task outputs some lines to ```storage/logs/send_message_output.log```
9. Run tests
    * Run ```vendor/bin/phpunit``` to execute all tests
    * Run ```vendor/bin/phpunit --filter name_of_test``` to execute a single test
10. Configure virtual host if necessary
    * Here I have a configuration that can help.
    ```
    <VirtualHost mymessage.backoffice:80>
        ServerAdmin romantigds@gmail.com
        DocumentRoot "E:/Projetos/mymessage/public"
        <Directory "E:/Projetos/mymessage/public">
            Options All
            AllowOverride All
            Order Allow,Deny
            Allow from all
            Require all granted
        </Directory>
        ServerName mymessage.backoffice
        ServerAlias mymessage.backoffice
        ErrorLog "logs/mymessage.backoffice.log"
    </VirtualHost>
    ```
    * If you create a virtual host, don't forget to change the APP_URL in the ```.env``` file
11. Access the app url and create an account to use the system
