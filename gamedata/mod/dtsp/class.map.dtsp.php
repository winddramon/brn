<?php
class map_dtsp		//把gameinfo的动态地图数据和init.maps.php里的静态地图数据封装在一起
{
	public $data_by_id = array();
	public $data_by_coordinate = array();
	
	public function __construct()
	{
		global $g;
		$this->init($g->gameinfo['maplist']);
		
		return;
	}
	
	public function init($maplist){
		$this->data_by_id = $this->data_by_coordinate = array();
		foreach($maplist as $mval){
			$this->data_by_id[$mval['id']] = $mval;
			$this->data_by_coordinate[$mval['c']] = $mval;
		}
		return;
	}
	
	public function reload(){
		global $g;
		include(get_mod_path('dtsp').'/init/init.maps.dtsp.php');
		
		$maplist = $map_coordinates = array();
		foreach($mapinfo['map_static'] as $sval){
			$maplist[] = $sval;
			$map_coordinates[] = $sval['c'];
		}
		
		$rlist = $mapinfo['map_random'];
		shuffle($rlist);
		$rlist = array_slice($rlist,0,2);
		foreach($rlist as $rval){
			$i = 0;
			do{
				$rcoor = random(0,$map_size[0]).'-'.random(0,$map_size[1]);
				if($i >= 1000){throw_error('Initiating maps failed.');}
				$i++;
			}while(in_array($rcoor, $map_coordinates));
			$rval['c'] = $map_coordinates[] = $rcoor;
			$maplist[] = $rval;
		}
		$this->init($maplist);
		$this->update();
		
		return;
	}
	
	public function update(){
		global $g;
		$g->gameinfo['maplist'] = array_values($this->allget());
		return;
	}
	
	public function iget($area, $attr = 'n')
	{
		if(isset($this->data_by_id[$area][$attr])){
			return $this->data_by_id[$area][$attr];
		}else{
			return false;
		}		
	}
	
	public function cget($coor, $attr = 'n')
	{
		if(isset($this->data_by_coordinate[$coor][$attr])){
			return $this->data_by_coordinate[$coor][$attr];
		}else{
			return false;
		}		
	}
	
	public function allget($keys = false){
		return $keys ? array_keys($this->data_by_id) : $this->data_by_id;
	}
}
?>