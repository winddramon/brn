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
		global $g, $mapinfo, $map_active_vars, $map_random_num, $map_size;
		
		$maplist = $map_coordinates = array();
		foreach($mapinfo as $mtype => $mcont){
			if($map_random_num[$mtype] < 0){//先把全部固定地图标注完毕
				foreach($mcont as $mval){
					$map_coordinates[] = $mval['c'];
					$maplist[] = $mval;
				}
			}			
		}
		foreach($mapinfo as $mtype => $mcont){
			if($map_random_num[$mtype] > 0){//之后分配随机地图。这个参数是0的地图完全不放置
				shuffle($mcont);
				$mcont = array_slice($mcont,0,$map_random_num[$mtype]);
				foreach($mcont as $mval){
					$i = 0;
					do{
						$mcoor = random(0,$map_size[0]).'-'.random(0,$map_size[1]);
						if($i >= 1000){throw_error('Initiating maps failed.');}
						$i++;
					}while(in_array($mcoor, $map_coordinates));
					$mval['c'] = $map_coordinates[] = $mcoor;
					$maplist[] = $mval;
				}
			}			
		}
		//file_put_contents('a.txt',$map_active_vars);
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