# MU2UDCC
Mojang Username to UUID Database Column Converter

# Note
This was a made quickly and dirty. You should make a backup of your database before using!

#Install
Install dependencies using [Composer](https://getcomposer.org/download/):
`composer install`

Copy config.php.dist to config.php and update with your database credentials.

# Usage
## Flags
- `--column=[column]` This is where the Usernames are that you want to convert to UUID's.
- `--table=[tablename]` The table where your column is located.
- `--database=[databasename]` (Optional) Set what database you want to use. The default is what you define in the config.
- `--dashes` (Optional) Add in dashes `xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx`


## Example
To replace usernames in the owner column on the protections table (using the database from your config.php):
`php mu2udcc.php --table=protections --column=owner --dashes`

