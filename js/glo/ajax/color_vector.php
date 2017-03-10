<?php
class Color_vector{
	    public $coord = array();
	    public $similarity = array();
	    
	    
	    
	    public function bild_vector($main, $color){
			$f = 0;
			$pattern = 18;
			for($i = 0; $i < count($main->r); $i++){
		    	$this->coord[$f] = 0;
		    	for($j = 0; $j < count($color->r); $j++){
			    	$this->coord[$f] = $this->color_proximity($main, $i, $color, $j, $pattern, 1);
		    	}
		    	$f++;
	    	}
		}
		
		public function set_vector($v){
			$this->coord = $v;
		}
		
		public function cosinus($b, $ganre){
			$this->similarity[$ganre]['cos'] = $this->skolarMulti($this->coord, $b->coord) / ($this->longVector($this->coord) * $this->longVector($b->coord));
		}
		
		public function dise($b, $ganre){
			$this->similarity[$ganre]['dise'] = ( 2 * $this->summ_multi($this->coord, $b->coord) ) / ($this->summ_sqrt($this->coord) + $this->summ_sqrt($b->coord));
		}
		
		public function jakkard($b, $ganre){
			$this->similarity[$ganre]['jakk'] = $this->summ_multi($this->coord, $b->coord) / ($this->summ_sqrt($this->coord) + $this->summ_sqrt($b->coord) - $this->summ_multi($this->coord, $b->coord) );
		}
		
		
		public function overlap($b, $ganre){
			$this->similarity[$ganre]['over'] = ( $this->summ_multi($this->coord, $b->coord) ) / min($this->summ_sqrt($this->coord), $this->summ_sqrt($b->coord));
		}	
		
		//private
		
		private function color_proximity($mainColor, $i, $color, $j, $pattern, $p){
			if( (abs($color->r[$j] - $mainColor->r[$i]) <= $pattern/$p)  && (abs($color->g[$j] - $mainColor->g[$i]) <= $pattern/$p) &&(abs($color->b[$j] - $mainColor->b[$i]) <= $pattern/$p) ){
					return $this->color_proximity($mainColor, $i, $color, $j, $pattern, $p+0.1);		
			}else{
				return $p/10;
			}
		}
		
		
		private function summ_multi($x,$y){ //сумма квадратов элементов
			$res = 0;
	
			for($i = 0; $i < count($x); $i++){
				$res += $x[$i]*$y[$i];
	
			}
			return $res;
		}
		
		private function summ_sqrt($x){ //сумма квадратов элементов
			$res = 0;
			foreach($x as $i){
				$res += $i*$i;
			}
			return $res;
		}
		
		private function skolarMulti($v1, $v2){ //сколярное произведение
			$res = 0;
			if(count($v1) == count($v2)){
				for($i = 0; $i < count($v1); $i++){
					$p = $v1[$i] * $v2[$i];
					$res += $p;
				}
			}else{
				$res = FALSE;
			}
			return $res;
		}
		
		private function longVector($v){ //длина вектора
			$res = 0;
			for($i = 0; $i < count($v); $i++){
				$res += $v[$i] * $v[$i];
			}
			return sqrt($res);
		}

    }