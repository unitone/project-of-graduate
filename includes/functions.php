<?php

function check_auth() {
	
	if ( isset( $_SESSION['login'] ) && $_SESSION['login'] )
		return;
		
	if ( isset( $_SESSION['register'] ) && $_SESSION['register'] )
		return;

	if ( ! isset( $_COOKIE['key'] ) ) {
		header( 'Location: index.php' );
	}
}

function get_current_time( $format = 'Y-m-d h:i:s' ) {
	return date( $format, time());
}

function is_submit() {
	if ( isset( $_POST['submit'] ) )
		return true;
	return false;
}

function submit_action( $do ) {
	$action = isset( $_POST['do'] ) ? $_POST['do'] : '';
	if ( $action === $do )
		return true;
	return false;
}

function is_email( $email ) {
	$preg = "/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";
	if ( preg_match( $preg, $email ) )
		return true;
	return false;
}

function is_email_existed( $email = '' ) {
	global $db;

	if ( empty( $email ) )
		return true;

	$result = $db->get_var( $db->prepare( "SELECT id FROM user WHERE email=%s", $email ) );
	if ( $result )
		return true;
	return false;
}

function get_logged_user() {
	global $db;

	if ( ! isset( $_COOKIE['key'] ) )
		return;
		
	$id = $_COOKIE['key'];

	$user = $db->get_results( $db->prepare( "SELECT * FROM user WHERE id = %d", $id ) );
	return $user[0];
}

function get_user_by_id( $id ) {
	global $db;

	$user = $db->get_results( $db->prepare( "SELECT * FROM user WHERE id = %d", $id ), ARRAY_A );
	return $user[0];
}

?>