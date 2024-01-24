<?php 
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','root');
define('DB_NAME','library');
// Establish database connection.
try
{
// Instancier la classe PDO
}
catch (PDOException $e)
{
    exit("Error: " . $e->getMessage());
}
