<?php

// CREATE TABLE leads (
//     id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
//     email VARCHAR(255) NOT NULL UNIQUE,
//     created_at DATETIME DEFAULT CURRENT_TIMESTAMP
// );

// CREATE TABLE users (
//     id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
//     username VARCHAR(50) NOT NULL UNIQUE,
//     password VARCHAR(255) NOT NULL,
//     created_at DATETIME DEFAULT CURRENT_TIMESTAMP
// );

/* Database credentials.  */

// Local
define('DB_SERVER',   'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME',     'landing_db');

// Produção
// define('DB_SERVER',   'host');
// define('DB_USERNAME', 'user');
// define('DB_PASSWORD', '');
// define('DB_NAME',     'landing_db');

/* Attempt to connect to MySQL database */
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($conn === false) { die("ERROR: Could not connect. " . mysqli_connect_error()); }