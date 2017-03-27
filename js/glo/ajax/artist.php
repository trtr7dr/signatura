<?php
class Artist{
	
	public $artList = array();
	public $db;
	   
	function __construct($dase) {
       $this->db = $dase;
    }
	   
	public function getList($h, $color){
		
		$j = 0;
		
		for ($i = 0; $i < count($color->r); $i++) {
		    $sql1 = 'SELECT `artist` FROM `color` WHERE `r` > ' . mysql_real_escape_string(round($color->r[$i]) - $h) . ' AND `r` < ' . mysql_real_escape_string(round($color->r[$i]) + $h) . ' AND `g` > ' . mysql_real_escape_string(round($color->g[$i]) - $h) . ' AND `g` < ' . mysql_real_escape_string(round($color->g[$i]) + $h) . ' AND `b` > ' . mysql_real_escape_string(round($color->b[$i]) - $h) . ' AND `b` < ' . mysql_real_escape_string(round($color->b[$i]) + $h);
			
		    $res = $this->db->query($sql1);
	
		    if ($obj = $res->fetch(PDO::FETCH_ASSOC)) {
				if ($obj['artist'] != '') {
				    $this->artList[$j] = $obj['artist'];
				    $j++;
				}
		    }
		}
		$this->artList = array_unique($this->artList);
		
		$sql = 'DELETE FROM `color` WHERE `artist` = ""';
		$res = $this->db->query($sql);
		unset($res);
	}
	
	
	
	public function artString(){
		$res = '';
		if (count($this->artList) >= 1) {
		    if (count($this->artList) != 1){
				$res = ('Создавали работы с аналогичными цветами: ');
			}else{
				$res = ('Создавал работы с аналогичными цветами: ');
			}
	
		    for ($i = 0; $i < count($this->artList); $i++) {
				if ($this->artList[$i] != ' ') {
				    $res .= ('<a href="https://artrue.ru/?s=' . $this->artList[$i] . '">' . $this->artList[$i] . '</a>');
				    if ($i + 1 != count($this->artList)){
						$res .= (', ');
					}
				    else{
						$res .= ('.');
					}
				}
		    }
		}else {
		    $res .= ('Цветовых аналогий не найдено');
		}
		return $res;
	}
	
	public function mla(){
		$res = '<div>';
		for ($i = 0; $i < count($this->artList); $i++) {
		    if ($this->artList[$i] != ' ' && $this->artList[$i] != '') {
			$res .= do_shortcode('[mla_gallery s="' . $this->artList[$i] . '" link=file columns=4 thumbnail="medium" numberposts=8] ' . '<span style="font-size: 400%">...</span>');
		    }
		}
		$res .= '</div>';
		return $res;
	}
}



