<?php

    
	//include('function.php');
	
	$listArtist = getArtistName();
	//var_dump($listFacult);
	 for($i = 0; $i < count($listArtist); $i++){
	    print ('<option> '.$listArtist[$i].' </option>');
	 }	
	 