<?php

if( empty( $_POST ) ) {
	form();
} else {
	execute();
}

function form() {
	echo '<html>';
	echo '<head><title>Install Film List</title></head>';
	echo '<body>';
	echo '<h1>Install Film List</h1>';
	echo '<form name="installFL" id="installFL" method="post" action="' . htmlspecialchars( $_SERVER["PHP_SELF"] ) . '" />';
	echo '<label>Database Host: <input type="text" name="dbHost" value="localhost"/></label><br />';
	echo '<label>Database User: <input type="text" name="dbUser" value="myuser"/></label><br />';
	echo '<label>Database Pass: <input type="text" name="dbPass" value="mypassword"/></label><br />';
	echo '<label>Database: <input type="text" name="dbName" value="filmlist"/></label><br />';
	echo '<label>Database Host: <input type="text" name="name" value="localhost"/></label><br />';
	echo '<label>Address: <input type="text" name="webAddress" value="http://filmlist.myserver.com"/></label><br />';
	echo '<label>Films per Page: <input type="number" name="filmsPage" value="25"/></label><br />';
	echo '<input type="submit" />';
	echo '</form>';
	echo '</body></html>';
}

function execute() {
	$connection = new mysqli(
		$_POST['dbHost'],
		$_POST['dbUser'],
		$_POST['dbPass'],
		$_POST['dbName']
	);

	if ( mysqli_connect_errno() ) {
		file_put_contents( 'connection.log', "Failed to connect to MySQL: " . mysqli_connect_error() );
		die( 'Failed to connect to SQL server.php ');
	}

	$settings = '<?php
define( "DB_HOST", "' . $_POST['dbHost'] . '" );
define( "DB_USER", "' . $_POST['dbUser'] . '" );
define( "DB_PASS", "' . $_POST['dbPass'] . '" );
define( "DB_NAME", "' . $_POST['dbName'] . '" );';

	file_put_contents( 'settings.php', $settings );
	
	mysqli_query( $connection, "CREATE DATABASE IF NOT EXISTS " . $_POST['dbName'] );

	mysqli_select_db( $connection, $_POST['dbName'] );

	$schema = file_get_contents( 'maintenance/schema.sql' );
	if ( !$schema ) {
		die( 'Could not get schema!' );
	}

	mysqli_multi_query( $connection, $schema );

	mysqli_query( $connection, "INSERT INTO config ( config_item, config_group, config_value )
		VALUES ( 'BaseURL', '', '" . $_POST['webAddress'] . "' )" );
	mysqli_query( $connection, "INSERT INTO config ( config_item, config_group, config_value )
		VALUES ( 'ListLimit', '', '" . $_POST['filmsPage'] . "' )" );

	mysqli_close( $connection );

	header( 'Location: index.php?page=index' );
}