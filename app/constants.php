<?php

/**
 * Constants file
 *
 * @author Trisha Milan <tshmilan@gmail.com>
 */

define("DB_NAME", "php_task");
define("INVALID_COMMAND", "Invalid command. Use --help for the list of available commands.\n");
define("INVALID_COMMAND_FILE", "Invalid command. Usage: --file [filename] -u [username] -p [password] -h [host] or --file [filename] --dry_run\n");
define("INVALID_COMMAND_CREATE_TABLE", "Invalid command. Usage: --create_table -u [username] -p [password] -h [host] or --create_table --file [filename] -u [username] -p [password] -h [host]");
define("INVALID_MYSQL_COMMAND", "Invalid command. This must be used as an option to --file [filename] or --create_table \n\nSample usage: \n --create_table -u [username] -p [password] -h [host] \n --file [filename] -u [username] -p [password] -h [host]");
