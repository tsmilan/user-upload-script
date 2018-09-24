# User Upload Script

This PHP script accepts a CSV file as input and processes the CSV file. The parsed file data will be inserted into a MySQL database. 

## Requirements

- PHP and MySQL
- Database named `php_task`
- PHPUnit testing framework (can be installed by running `composer install`)

## Getting started

1. Install dependencies by running `composer install`
2. Start the script by running `php user_upload.php [options]` see command line directives and sample usage below
3. Use `./vendor/bin/phpunit` to run PHPUnit

## Command Line Directives

- --file [csv file name] – The name of the CSV to be parsed. This will insert users data to the database.
- --create_table – Create users table
- --dry_run – To be used with the --file directive to run the script without altering the database
- -u – MySQL username
- -p – MySQL password
- -h – MySQL host
- --help – This help

## Sample usage

- --file [filename] -u [username] -p [password] -h [host]
- --file [filename] --dry_run
- --dry_run --file [filename]
- --create_table -u [username] -p [password] -h [host]
- --create_table --file [filename] -u [username] -p [password] -h [host]
- --create_table -u [username] -p [password] -h [host] --file [filename]
- --help

## This script has been tested on

- Ubuntu 16.04.3
- PHP 7.0.32
- MySQL 5.6