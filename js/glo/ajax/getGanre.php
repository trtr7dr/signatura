<?php
	
    //include 'connect.php';
	//require('function.php');
	
	$listGanre = getGanre();
	//var_dump($listFacult);
	 for($i = 0; $i < count($listGanre); $i++){
	    print ('<option> '.$listGanre[$i].' </option>');
	 }	
	 
	 