<?php
// Database configuration - uses environment variables for production (Render/TiDB Cloud)
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_NAME') ?: 'a_store';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'password';

$dsn = "mysql:host=$host;port=$port;dbname=$dbname";

$option = array(
  PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
);

// Enable SSL for PlanetScale (production)
if (getenv('DB_HOST')) {
  $option[PDO::MYSQL_ATTR_SSL_CA] = '/etc/ssl/certs/ca-certificates.crt';
  $option[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
}

try {
  $con = new PDO($dsn, $user, $pass, $option);
} catch (PDOException $e) {
  echo 'Failed To Connect: ' . $e->getMessage();
}
