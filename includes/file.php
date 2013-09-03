<?php

function upload_form( $id ) { ?>
<form action="?action=uploadimg&id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
	<label for="file">文件：</label>
	<input id="file" name="file" type="file" />
	<input type="hidden" name="albumid" value="<?php echo $id; ?>" />
	<input id="submit" name="submit" type="submit" value="上传" />
</form>
<?php
}

function upload_img() {
	global $db, $user;

	if ( $_FILES["file"]["type"] == "image/gif" 
	  || $_FILES["file"]["type"] == "image/jpeg" 
	  || $_FILES["file"]["type"] == "image/pjpeg" ) {
		if ( $_FILES["file"]["error"] > 0 ) {
			echo '<span>出错啦！</span><p>错误原因：' . $_FILES["file"]["error"] . '</p>';
			return;		
		}
		
		if ( ! file_exists( USERPATH . $user->id ) ) {
			mkdir( USERPATH . $user->id, 0777 );
		}
	
		$uploadpath = USERPATH . $user->id . '/';
		
		$filename = substr($_FILES["file"]["name"], 0, strlen($_FILES["file"]["name"]) - 4);
		$filetype = substr($_FILES["file"]["name"], strlen($_FILES["file"]["name"]) - 4, strlen($_FILES["file"]["name"]));
		$storefilename = time();
		if ( move_uploaded_file( $_FILES["file"]["tmp_name"], $uploadpath . $storefilename  . $filetype ) ) {
			$file = 'user/' . $user->id . '/' . $storefilename . $filetype ;

			if ( isset( $_POST["albumid"] ) ) {
				$albumid = $_POST["albumid"];
				$data = array( 'name' => $filename, 'stroename' => $storefilename, 'url' => $file, 'albumid' => $albumid );
				$db->insert( 'photo', $data );
			} else {
				$db->update( 'user', array( 'photo' => $file ), array( 'id' => $user->id ) );
				$user->photo = $file;
			}
		}
	} else {
		echo '<p>不支持该文件类型</p>';
		return;
	}
} 

?>