<?php

// Set Connection
require_once "../admin/config/conn.php";

// Set Timezone
date_default_timezone_set('America/Sao_Paulo');

// Save lead
if (!empty($_POST["email"])) {

	// Variables
	$email = $_POST["email"];

	// Tabela leads
	try {

        // Prepare a select statement
		$sql = "INSERT INTO leads (email) VALUES (?)";
		$stmt = mysqli_prepare($conn, $sql);

		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt, "s", $email);
		
		// Attempt to execute the prepared statement
		mysqli_stmt_execute($stmt);
         

	} catch (Exception $e) {
		echo $e->getMessage();
	} finally {
		$statement->close();
		mysqli_close($conn);
    }

} else {
	exit('No post object');
}