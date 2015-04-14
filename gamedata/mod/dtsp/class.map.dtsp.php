<?php
class map_dtsp		//把gameinfo的动态地图数据和init.maps.php里的静态地图数据封装在一起
{
	protected $data_by_id = array();
	protected $data_by_coordinate = array();
	protected $mapinfo_by_id = array();
	protected $regioninfo_by_id = array();
	protected $game;

	public function __construct($g)
	{
		//global $g;
		$this->game = $g;
		$this->parse_regioninfo_by_id();
		$this->parse_mapinfo_by_id();
		$this->init($this->game->gameinfo['maplist']);
		//file_put_contents('a.txt',$this->mapinfo_by_id[11]);
		return;
	}

	protected function parse_mapinfo_by_id(){
		global $mapinfo;
		$mapinfo_by_id = array();
		foreach($mapinfo as $mval){
			$mapinfo_by_id[$mval['id']] = $mval;
		}
		foreach($this->regioninfo_by_id as $rval){
			foreach($rval['group'] as $gval){
				foreach($gval['list'] as $lval){
					$mapinfo_by_id[$lval] = array_merge($mapinfo_by_id[$lval], array('r' => $rval['id']));
				}
			}
		}

		$this->mapinfo_by_id = $mapinfo_by_id;

		return;
	}

	protected function parse_regioninfo_by_id(){
		global $regioninfo;
		$regioninfo_by_id = array();
		foreach($regioninfo as $rval){
			$regioninfo_by_id[$rval['id']] = $rval;
		}
		$this->regioninfo_by_id = $regioninfo_by_id;
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
		global $map_size;
		
		$maplist = $map_coordinates = array();
		foreach($this->regioninfo_by_id as $rval){//先把全部固定地图标注完毕
			foreach($rval['group'] as $gval){
				if($gval['num'] < 0){
					foreach($gval['list'] as $lval){
						$map_coordinates[] = $this->mapinfo_by_id[$lval]['c'];
						$maplist[] = $this->mapinfo_by_id[$lval];
					}
				}
			}
		}
		foreach($this->regioninfo_by_id as $rval){//之后分配随机地图。这个参数是0的地图完全不放置
			foreach($rval['group'] as $gval){
				if($gval['num'] > 0){
					shuffle($gval['list']);
					$mlist = array_slice($gval['list'],0,$gval['num']);
					foreach($mlist as $lval){
						$mdata = $this->mapinfo_by_id[$lval];
						$i = 0;
						do{
							$mcoor = $this->game->random(0,$map_size[0]).'-'.$this->game->random(0,$map_size[1]);
							if($i >= 1000){throw_error('Initiating maps failed.');}
							$i++;
						}while(in_array($mcoor, $map_coordinates));
						$mdata['c'] = $mcoor;
						$map_coordinates[] = $mdata['c'];
						$maplist[] = $mdata;
					}
				}
			}
		}


		
		$this->init($maplist);
		$this->update();
		
		return;
	}
	
	public function update(){
		$maplist = Array();
		foreach($this->allget() as $mval){
			$maplist[] = Array(
				'id' => $mval['id'],
				'c' => $mval['c']
			);
		}
		$this->game->gameinfo['maplist'] = $maplist;
		return;
	}

	/**
	 * 按id获取地图数据值
	 *
	 * @param $area 传入的id
	 * @param string $attr 要获取的键名
	 * @return bool
	 */
	public function iget($area, $attr = 'n')
	{
		if(isset($this->data_by_id[$area][$attr])){
			return $this->data_by_id[$area][$attr];
		}else{
			return false;
		}		
	}

	/**
	 * 按坐标获取地图数据值
	 *
	 * @param $coor 传入的坐标
	 * @param string $attr 要获取的键名
	 * @return bool
	 */
	public function cget($coor, $attr = 'n')
	{
		if(isset($this->data_by_coordinate[$coor][$attr])){
			return $this->data_by_coordinate[$coor][$attr];
		}else{
			return false;
		}		
	}

	/**
	 * 获得所有地图数据组成的数组
	 *
	 * @param bool $keys 参数为true表示只获取键名即地图编号
	 * @return array
	 */
	public function allget($keys = false){
		return $keys ? array_keys($this->data_by_id) : $this->data_by_id;
	}

	/**
	 * 计算特定区域的入口地图编号，如果该地图设定上是随机入口那么返回该区域的一个随机的地图编号
	 *
	 * @param $region 传入的区域编号
	 */
	public function get_region_access($region)
	{
		global $g, $m, $shopmap;
		$destination = $this->riiget($region, 'access');
		if(!$destination || ($destination >= 0 && !$m->iget($destination))){
			$cplayer = $g->current_player();
			$cplayer->error('destination:' .$destination. ' 跨区移动参数错误2');
			return;
		}
		if($destination < 0){//该等级随机
			$dlist = array();
			foreach($m->allget() as $dval){
				if($dval['r'] == $region){
					$dlist[] = $dval;
				}
			}
			shuffle($dlist);
			$destination = $dlist[0]['id'];
		}
		
		return $destination;
	}

	/**
	 * 获得特定区域的静态数据
	 *
	 * @param $region 传入的区域编号
	 * @param string $attr 要获得的区域数据
	 * @return bool
	 */
	public function riiget($region, $attr = 'id'){
		if(isset($this->regioninfo_by_id[$region][$attr])){
			return $this->regioninfo_by_id[$region][$attr];
		}else{
			return false;
		}
	}

	public function ritget($type, $attr = 'id'){
		$regions = array();
		foreach($this->regioninfo_by_id as $rkey => $rval){
			if($rval['type'] == $type){
				$regions[] = $rkey;
			}
		}
		if(sizeof($regions) == 0){return false;}
		elseif(sizeof($regions) == 1){return $regions[0];}
		else{return $regions;}
	}
}