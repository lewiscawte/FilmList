<?php

require_once "index.php";

$context = new Context();

if ( isset($_POST['submit'] ) ) {
	$uname = mysql_real_escape_string( $_POST['username'] );
	$pass = hash( 'md5', mysql_real_escape_string( $_POST['password'] ) );

	$sql = $context->getDatabase()->doQuery( "SELECT * FROM user WHERE user_name='$uname'
		AND user_password='$pass' LIMIT 1" )->fetch_assoc();

	session_start();

	$_SESSION['username'] = $sql['user_name'];
	$_SESSION['loggedIn'] = true;

	header("Location: index.php?page=listfilms");

	exit;

} else {
	header("Location: index.php?page=login");
	exit;
}