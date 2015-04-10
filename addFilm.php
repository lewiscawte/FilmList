<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 24/02/15
 * Time: 20:25
 */

/*
 * @type POST script
 * Add film data to the database from admin/AddFilm.
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

	// If we have problems, log it to a file.
	if ( mysqli_connect_errno() ) {
		// This should probably die after logging?
		file_put_contents( 'connection.log', "Failed to connect to MySQL: " . mysqli_connect_error() );
	}

	// Select/USE our database so we don't have to worry about prefixing all fields.
	mysqli_select_db( $connection, DB_NAME );

	// Insert our information into the film table.
	// Escape strings with htmlspecialchars().
	// Make sure the enable flag is stored correctly, as a bool in the database.
	// Force 'year' to be stored as a valid integer.
	// Convert 'date' into the correct MySQL DATETIME format.
	mysqli_query( $connection, "INSERT INTO film ( film_name, film_active, film_year, film_added )
		VALUES ( '" . htmlspecialchars( $_POST['name'] ) . "', '" . (bool)$_POST['enable'] . "', '"
		. (int)$_POST['year'] . "', '" . date( 'Y-m-d H:i:s' ) . "' )" );

	// Get the value of film_id which is AUTO_INCREMENT. We'll need this to store related data in other
	// fields.
	$id = mysqli_insert_id( $connection );

	$adaptations = array(
		'inspiration' => htmlspecialchars( $_POST['inspiration'] ),
		'inspired' => htmlspecialchars( $_POST['adaptations'] ),
	);

	// Not inserting the following will cause the film pages to not output any content.

	// film_details for our more advanced, non-identifying data.
	mysqli_query( $connection, "INSERT INTO film_details ( film_id, film_runtime, film_plot, film_budget, film_budgetcurrency, film_tags, film_adaptations )
		VALUES ( '" . $id . "', '" . (int)$_POST['runtime'] . "', '" . htmlspecialchars( $_POST['plot'] ) . "', '" . $_POST['budget'] . "', 'USD', '" . htmlspecialchars( $_POST['tags'] ) . "', '" . serialize( $adaptations ) . "' );" );

	// film_locations for our data on where various files related to this film are on the system.
	mysqli_query( $connection, "INSERT INTO film_locations ( film_id, film_base )
		VALUES ( '" . $id . "', '" . htmlspecialchars( $_POST['basepath'] ) . "' )" );

	// film_media for where can find the correct file names of things like posters and trailers.
	// Should be editable later, hence why this is being left empty bar creating the field to stop
	// blank output.
	// @TODO: Build in better error handling to remove this basicly blank insert.
	mysqli_query( $connection, "INSERT INTO film_media ( film_id ) VALUE ( '" . $id . "' )" );

	// Close up the connection, go to sleep database.
	mysqli_close( $connection );

	// We're done, lets go back to the admin panel.
	header( 'Location: index.php?page=admin/index' );
}