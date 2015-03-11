<?php
class map_dtsp		//把gameinfo的动态地图数据和init.maps.php里的静态地图数据封装在一起
{
	public $data_by_id = array();
	public $data_by_coordinate = array();
	
	public function __construct()
	{
		global $g;
		foreach($g->gameinfo['maplist'] as $mval){
			$this->data_by_id[$mval['id']] = $mval;
			$this->data_by_coordinate[$mval['c']] = $mval;
		}
		
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