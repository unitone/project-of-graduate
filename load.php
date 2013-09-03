<?php

require_once( 'config.php' );
require_once( 'header.php' );
require_once( 'footer.php' );
require_once( 'includes/db.php' );
require_once( 'includes/functions.php' );
require_once( 'includes/post.php' );
require_once( 'includes/comment.php' );
require_once( 'includes/friend.php' );
require_once( 'includes/message.php' );
require_once( 'includes/search.php' );

check_auth();

$user = get_logged_user();

?>