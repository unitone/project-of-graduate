<?php

function add_feel( $content, $user_id = 0 ) {
	global $db;

	if ( is_null( $content ) )
		return;

	if ( ! $user_id )
		$user_id = $_COOKIE['key'];

	$db->query( $db->prepare( "INSERT INTO feel(userid,content,publictime) VALUES(%d,%s,NOW())", $user_id, $content ) );
}

function show_feel_items() {
	global $db;

	$user = get_logged_user();

	$feels = $db->get_results( $db->prepare( "SELECT * FROM feel WHERE userid = %d ORDER BY `publictime` DESC LIMIT 0, 15", $user->id ), ARRAY_A );
	if ( ! is_null( $feels ) ) {
		foreach ( $feels as $feel ) {
			show_feel_item( $feel ); 
		}
	}
}

function show_feel_item( $feel ) { 

	$user = get_user_by_id( $feel['userid'] ); ?>

	<div class="feel">
		<p><?php echo $user->name; ?> - <span><?php echo $feel['publictime']; ?></span></p>
		<p><?php echo $feel['content']; ?></p>
	</div> <?php

}

?>