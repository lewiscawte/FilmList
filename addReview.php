<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 24/02/15
 * Time: 20:25
 */

// We're not (able?) to use the main context here
// so we have to load our own info for the database connections.
require_once "settings.php";

execute();

function execute() {
	// Create use a connection to the database.
	$connection = new mysqli(
		DB_HOST,
		DB_USER,
		DB_PASS,
		DB_NAME
	);

	// Log if connection couldn't be made.
	if ( mysqli_connect_errno() ) {
		// @TODO: Friendly message and/or die if a connection fails.
		file_put_contents( 'connection.log', "Failed to connect to MySQL: " . mysqli_connect_error() );
	}

	// USE so we don't have prefix all our tables.
	mysqli_select_db( $connection, DB_NAME );

	// Insert details. ID is auto incremented.
	mysqli_query( $connection, "INSERT INTO rating ( rating_film_id, rating_user, rating_score, rating_text )
		VALUES ( '" . $_POST['filmID' ] . "', '" . htmlspecialchars( $_POST['name'] ) . "', '" . $_POST['score'] . "', '" . $_POST['text'] . "' )" );

	// Clean up any loose connection.
	mysqli_close( $connection );

	// Lets go have a look at our rating in place.
	header( 'Location: index.php?page=film&filmid=' . $_POST['filmID'] );
}