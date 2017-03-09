
<?php
       
    require_once ( 'connect.php' );
   // require_once ( 'function.php' );
 
    
    if($_POST['type'] == 'stuff'){
	    
	    $db = connect();
    	$lang = $db->query('SET CHARACTER SET utf8');
	    
	    $sql = 'SELECT MAX(`id`) FROM `stuff`';
		$res = $db->query($sql);
		$obj = $res->fetch(PDO::FETCH_ASSOC);
		$id = $obj['MAX(`id`)'] + 1;
		
	    
	    
	    $sql = ('INSERT INTO `stuff` VALUES('.mysql_real_escape_string($id).',"'.mysql_real_escape_string($_POST['name']).'","'.mysql_real_escape_string($_POST['text']).'",'.mysql_real_escape_string($_POST['num']).')');
	    //echo($sql);
		$db->query($sql);
		
		
		
		$img = explode('splitCodeWord',$_POST['img']);

		for($i = 0; $i < count($img) - 1; $i++){
			$image = explode(',', $img[$i]);

			
			$format = strripos($image[0],'png');
			if ($format == true) {
				$f = 'png';
			}
			$format = strripos($image[0],'jpg');
			if ($format == true) {
				$f = 'jpg';
			}
			$format = strripos($image[0],'jpeg');
			if ($format == true) {
				$f = 'jpeg';
			}	
			$result = file_put_contents('stuff/'.$id.'-'.$i.'.'.$f, base64_decode($image[1]));
		}
		$result = 'Ваша заявка принята. Ее номер: '.$id.'. Спасибо!';
		print json_encode( $result );
		
		unset($db);
		unset($res);
		unset($sql);
		unset($obj);
	    
    }
    
    if($_POST['type'] == 'getInfo'){
	    $db = connect();
    	$lang = $db->query('SET CHARACTER SET utf8');
    
    	$id = mysql_real_escape_string($_POST['id']);
    	
    	$sql = 'SELECT `name`,`text`,`num` FROM `stuff` WHERE `id` = '.$id;
		$res = $db->query($sql);
		$obj = $res->fetch(PDO::FETCH_ASSOC);
		$result['name'] = $obj['name'];
		$result['text'] = $obj['text'];
		$result['num'] = $obj['num'];
		print json_encode( $result );
    	
    	unset($db);
		unset($res);
		unset($sql);
		unset($obj);
    	
    }
       
    if($_POST['type'] == 'delete'){
	    
	    $db = connect();
    	$lang = $db->query('SET CHARACTER SET utf8');
    	$id = mysql_real_escape_string($_POST['id']);
	    $sql = 'DELETE FROM `stuff` WHERE `id` = '.$id;
	    //echo($sql);

	    unset($db);
		unset($res);
		unset($sql);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    