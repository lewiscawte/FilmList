<?php
/**
 * Created by PhpStorm.
 * User: lewiscawte
 * Date: 24/02/15
 * Time: 20:25
 */

require_once "settings.php";

execute();

function execute() {
	$connection = new mysqli(
		DB_HOST,
		DB_USER,
		DB_PASS,
		DB_NAME
	);

	if ( mysqli_connect_errno() ) {
		file_put_contents( 'connection.log', "Failed to connect to MySQL: " . mysqli_connect_error() );
	}

	mysqli_select_db( $connection, DB_NAME );

	mysqli_query( $connection, "INSERT INTO film ( film_name, film_active, film_year, film_added )
		VALUES ( '" . htmlspecialchars( $_POST['name'] ) . "', '" . (bool)$_POST['enable'] . "', '"
		. (int)$_POST['year'] . "', '" . date( 'Y-m-d H:i:s' ) . "' )" );

	$id = mysqli_insert_id( $connection );

	$adaptations = array(
		'inspiration' => htmlspecialchars( $_POST['inspiration'] ),
		'inspired' => htmlspecialchars( $_POST['adaptations'] ),
	);

	// Not inserting the following will cause the film pages to not output any content.

	mysqli_query( $connection, "INSERT INTO film_details ( film_id, film_runtime, film_plot, film_budget, film_budgetcurrency, film_tags, film_adaptations )
		VALUES ( '" . $id . "', '" . (int)$_POST['runtime'] . "', '" . $_POST['plot'] . "', '" . $_POST['budget'] . "', 'USD', '" . htmlspecialchars( $_POST['tags'] ) . "', '" . serialize( $adaptations ) . "' );" );

	mysqli_query( $connection, "INSERT INTO film_locations ( film_id, film_base )
		VALUES ( '" . $id . "', '" . htmlspecialchars( $_POST['basepath'] ) . "' )" );

	mysqli_query( $connection, "INSERT INTO film_media ( film_id ) VALUE ( '" . $id . "' )" );

	mysqli_close( $connection );

	header( 'Location: index.php?page=admin/index' );
}