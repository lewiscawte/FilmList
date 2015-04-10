<?php

// POST should be empty if there are not inputs being posted.
if( empty( $_POST ) ) {
	// Assuming that the film hasn't been filled in yet, show
	// the installer form.
	form();
} else {
	// If there's been some data sent, actually set up FilmList.
	execute();
}

function form() {
	// Form HTML output (Context is not available and Twig is annoying)
	echo '<html>';
	echo '<head><title>Install Film List</title></head>';
	echo '<body>';
	echo '<h1>Install Film List</h1>';
	echo '<form name="installFL" id="installFL" method="post" action="' . htmlspecialchars( $_SERVER["PHP_SELF"] ) . '" />';
	echo '<label>Database Host: <input type="text" name="dbHost" value="localhost"/></label><br />';
	echo '<label>Database User: <input type="text" name="dbUser" value="myuser"/></label><br />';
	echo '<label>Database Pass: <input type="password" name="dbPass" value="mypassword"/></label><br />';
	echo '<label>Database: <input type="text" name="dbName" value="filmlist"/></label><br />';
	echo '<label>Address: <input type="text" name="webAddress" value="http://' . $_SERVER['HTTP_HOST'] . '"/></label><br />';
	echo '<label>Films per Page: <input type="number" name="filmsPage" value="25"/></label><br />';
	echo '<input type="submit" />';
	echo '</form>';
	echo '</body></html>';
}

function execute() {
	$connection = new mysqli(
		$_POST['dbHost'],
		$_POST['dbUser'],
		$_POST['dbPass']
	);

	if ( mysqli_connect_errno() ) {
		file_put_contents( 'connection.log', "Failed to connect to MySQL: " . mysqli_connect_error() );
		die( 'Failed to connect to SQL server.php ');
	}

	// Construct the settings file.
	$settings = '<?php

define( "DB_HOST", "' . $_POST['dbHost'] . '" );
define( "DB_USER", "' . $_POST['dbUser'] . '" );
define( "DB_PASS", "' . $_POST['dbPass'] . '" );
define( "DB_NAME", "' . $_POST['dbName'] . '" );';

	// Save the settings file to it's place.
	file_put_contents( 'settings.php', $settings );

	// Make sure the database exists
	mysqli_query( $connection, "CREATE DATABASE IF NOT EXISTS " . $_POST['dbName'] );

	// Start using our FilmList database.
	mysqli_select_db( $connection, $_POST['dbName'] );

	// Fetch the database schema.
	$schema = file_get_contents( 'maintenance/schema.sql' );
	if ( !$schema ) {
		die( 'Could not get schema!' );
	}

	// Append the following because for some reason, it fails to execute
	// the queries if you do them as separate queries (but runs the rest
	// of the script.)
	$schema .= "INSERT INTO config ( config_item, config_group, config_value )
		VALUES ( 'BaseURL', '', '" . (string)$_POST['webAddress'] . "' );";
	$schema .= "INSERT INTO config ( config_item, config_group, config_value )
		VALUES ( 'ListLimit', '', '" . (string)$_POST['filmsPage'] . "' );";

	// Some preset inserts for
	$schema .= file_get_contents( 'maintenance/defaultData.sql' );

	mysqli_multi_query( $connection, $schema );

	mysqli_close( $connection );

	header( 'Location: index.php?page=index' );
}