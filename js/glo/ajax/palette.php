<?php

    class Palette {

	public $r = array();
	public $g = array();
	public $b = array();
	public $ganre = array();

	function set_color_user($color, $k) {
	    for ($i = 0; $i < count($color) / 3; $i++) {
		$this->r[$i] = intval($color[$i][0]) / $k;
		$this->g[$i] = intval($color[$i][1]) / $k;
		$this->b[$i] = intval($color[$i][2]) / $k;
	    }
	}

	function set_default_color($k) {
	    $big = 256 / $k;
	    $middle = 192 / $k;
	    $small = 128 / $k;

	    $this->r = array(0, 0, 0, $big, $small, 0, 0, $small, 0, $small, $small, $big, $middle, 0, $big, $big);
	    $this->g = array($big, 0, 0, 0, $small, $small, $big, 0, 0, $small, 0, 0, $middle, $small, $big, $big);
	    $this->b = array($big, 0, $big, $big, $small, 0, 0, 0, $small, 0, $small, 0, $middle, $small, $big, 0);
	}

    }
    
