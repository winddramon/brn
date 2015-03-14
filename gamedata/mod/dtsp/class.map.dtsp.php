<?php
class map_dtsp		//把gameinfo的动态地图数据和init.maps.php里的静态地图数据封装在一起
{
	public $data_by_id = array();
	public $data_by_coordinate = array();
	public $mapinfo_by_id = array();
	
	public function __construct()
	{
		global $g;
		$this->parse_mapinfo_by_id();
		$this->init($g->gameinfo['maplist']);
		//file_put_contents('a.txt',$this->mapinfo_by_id[11]);
		return;
	}
	
	public function parse_mapinfo_by_id(){
		global $mapinfo;
		$mapinfo_by_id = array();
		foreach($mapinfo as $mval){
			$mapinfo_by_id[$mval['id']] = $mval;
		}
		$this->mapinfo_by_id = $mapinfo_by_id;
		return;
	}
	
	public function init($maplist){//自身初始化函数，把gameinfo动态数据和init静态数据合并，并按id和坐标手动建立索引
		$this->data_by_id = $this->data_by_coordinate = array();
		foreach($maplist as $mval){
			$mval = array_merge($this->mapinfo_by_id[$mval['id']],$mval);
			$this->data_by_id[$mval['id']] = $this->data_by_coordinate[$mval['c']] = $mval;
		}
		return;
	}	
	
	public function reload(){
		global $g, $mapinfo, $map_random_group, $map_size;
		
		$maplist = $map_coordinates = array();
		foreach($map_random_group as $rgval){//先把全部固定地图标注完毕
			if($rgval['num'] < 0){
				foreach($rgval['list'] as $lval){
					$map_coordinates[] = $this->mapinfo_by_id[$lval]['c'];
					$maplist[] = $this->mapinfo_by_id[$lval];
				}
			}		
		}
		foreach($map_random_group as $rgval){//之后分配随机地图。这个参数是0的地图完全不放置
			if($rgval['num'] > 0){
				shuffle($rgval['list']);
				$mcont = array_slice($rgval['list'],0,$rgval['num']);
				foreach($mcont as $lval){
					$mdata = $this->mapinfo_by_id[$lval];
					do{
						$mcoor = random(0,$map_size[0]).'-'.random(0,$map_size[1]);
						if($i >= 1000){throw_error('Initiating maps failed.');}
						$i++;
					}while(in_array($mcoor, $map_coordinates));
					$mdata['c'] = $mcoor;
					$map_coordinates[] = $mdata['c'];
					$maplist[] = $mdata;
				}
			}
		}

		
		$this->init($maplist);
		$this->update();
		
		return;
	}
	
	public function update(){
		global $g;
		$maplist = Array();
		foreach($this->allget() as $mval){
			$maplist[] = Array(
				'id' => $mval['id'],
				'c' => $mval['c']
			);
		}
		$g->gameinfo['maplist'] = $maplist;
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