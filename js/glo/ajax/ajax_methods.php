
<?php
       
    require_once ( 'connect.php' );
    //require_once ( 'function.php' );
    require_once ( 'palette.php' );
    require_once ( 'color_vector.php' );
    
    require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
    
    
        
    
   
     
	 if($_POST['flag'] == 'sig'){ //юзеор залил картинку
		
		$colorUser = new Palette();
		$colorUser->set_color_user($_POST['palette'], 8);
		
		$colorMain = new Palette();
		$colorMain->set_default_color(8);
		
		$user = new Color_vector();
		$user->bild_vector($colorMain, $colorUser);
		
		$ganre = new Color_vector();
		
		$db = connect();
    	$lang = $db->query('SET CHARACTER SET utf8');
    	
    	$sql = 'SELECT * FROM `vector`';
		$res = $db->query($sql);
		
		
		while($obj = $res->fetch(PDO::FETCH_ASSOC)){
	    	$ganre->set_vector(explode(';',$obj['coordinate']));
	    	$user->cosinus($ganre, $obj['ganre']);
	    	$user->dise($ganre, $obj['ganre']);
	    	$user->jakkard($ganre, $obj['ganre']);
	    	$user->overlap($ganre, $obj['ganre']);
	    }
	    
	    var_dump($user);
		
		unset($db);
		unset($sql);
		unset($sql1);
		unset($sql2);
		unset($res);
    	unset($res2);
    	exit();
    	
	 }
    
       
    
        
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    