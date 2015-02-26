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

	print_r( var_export( $_POST ) );

	mysqli_select_db( $connection, DB_NAME );

	mysqli_query( $connection, "INSERT INTO rating ( rating_film_id, rating_user, rating_score, rating_text )
		VALUES ( '" . $_POST['filmID' ] . "', '" . htmlspecialchars( $_POST['name'] ) . "', '" . $_POST['score'] . "', '" . $_POST['text'] . "' )" );

	mysqli_close( $connection );

	header( 'Location: index.php?page=film&filmid=' . $_POST['filmID'] );
}