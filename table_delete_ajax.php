<?php
require_once("config/db.php");

if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) )
{
	require_once("functions.php");
	if(isset($_POST["table_name"]) && $_POST["table_name"] != 'null' )
	{
		$statement = $pdo->prepare(
			"SELECT * FROM sscsr_db_table_master WHERE table_name = :table_name"
		);
		$result = $statement->execute(
			array(
				':table_name'	=>	$_POST["table_name"]
			)
		);
		$result = $statement->fetch();
		
		
			if($result->asset_path !=""){

			$dir = 	$result->asset_path;
			$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
			$files = new RecursiveIteratorIterator($it,
						 RecursiveIteratorIterator::CHILD_FIRST);
			foreach($files as $file) {
				if ($file->isDir()){
					rmdir($file->getRealPath());
				} else {
					unlink($file->getRealPath());
				}
			}
			rmdir($dir); 
			}
		
		
		 $statement = $pdo->prepare(
			"DELETE FROM sscsr_db_table_master WHERE table_name = :table_name"
		);
		$result = $statement->execute(
			array(
				':table_name'	=>	$_POST["table_name"]
			)
		);
		$table_name = $_POST['table_name'];
		$statement = $pdo->prepare(
			"DROP TABLE $table_name"
		);
		$result = $statement->execute(); 
		

		
		
		
		if(!empty($result))
		{
		  $message = array(
				'response' => array(
					'status' => 'success',
					'code' => '1',
					'message' => 'Table Deleted Successfully.',
					'title'=> $title
				)
			);
			
			echo json_encode($message);
			
		}
	}

}
else{
	
	header("Location: index.php"); 
	exit();
}

?>