<?php
require __DIR__ . "/vendor/autoload.php";

use Navarr\Minecraft\Profile;

$config = require __DIR__ . "/config.php";

try
{
	$dbo = new PDO("{$config['engine']}:host={$config['host']};dbname={$config['database']}", $config['username'], $config['password']);
}
catch (PDOException $e)
{
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}

$options = getopt(
		"",
		["column:", "database:", "table:", "dashes"]
	);


if (!isset($options['column'])) {
	echo "'--column' is a required argument\n";
	die();
}

if (!isset($options['table'])) {
	echo "'--table' is a required argument\n";
	die();
}

$database = (isset($options['database'])) ? $options['database'] : $config['database'];
$column = $options['column'];
$table = $options['table'];

echo "Converting $column on $database.table\n";

$user_query = "SELECT $column FROM $database.$table GROUP BY $column";

$users = $dbo->query($user_query);
if (!$users) echo "A argument you have given isn't correct!\n $user_query\n";

foreach ($users->fetchAll() as $user)
{
	$username = $user[$column];

	if (isset($options['dashes']))
	{
		if (strlen($username) == 36) continue;
	}
	else
	{
		if (strlen($username) == 32) continue;
	}


	try
	{
		$uuid = Profile::fromUsername($username)->uuid;
		if (isset($options['dashes']))
		{
			#Why are these not already in the response from Mojang?
			$uuid = substr_replace($uuid, '-', 8, 0);
			$uuid = substr_replace($uuid, '-', 13, 0);
			$uuid = substr_replace($uuid, '-', 18, 0);
			$uuid = substr_replace($uuid, '-', 23, 0);
		}
		echo "Updating $username -> $uuid\n";
		$user_update_query = "UPDATE $database.$table SET $column='$uuid' WHERE $column='$username'";
		$dbo->query($user_update_query);
	}
	catch (RuntimeException $e)
	{
		echo "$username: Not a username!\n";
	}

}
