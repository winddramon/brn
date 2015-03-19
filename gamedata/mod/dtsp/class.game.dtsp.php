<?php

class game_dtsp extends game_bra
{	
	/**
	 * 游戏类的初始化函数
	 * 构造函数结束后游戏会载入本地设置，载入完成后才调用此函数
	 * 会在此做出游戏开始与结束的判定（只有全灭结局会在此做出结束判定）
	 * 为了增加全局地图实例$m而继承此方法
	 */
	public function _construct()
	{
		global $m;
		
		$gameinfo = &$this->gameinfo;
		$m = new map_dtsp;
		
		if(($gameinfo['gamestate'] & GAME_STATE_START) === 0){
			if($gameinfo['starttime'] < time()){
				$this->game_start();
			}else{
				return;
			}
		}
		
		while($gameinfo['areatime'] <= time() && ($gameinfo['gamestate'] & GAME_STATE_START)){
			$areanum = $this->game_forbid_area();
			if($areanum >= sizeof($m->allget())){
				if($this->gameinfo['validnum'] == 0){
					$this->game_end('noplayer');
				}else{
					$this->game_end('timeup');
				}
			}
		}
		
		return;
	}
	
	/**
	 * 游戏结束时（所有结局）会调用的函数
	 * 进行各种初始化动作，为新一局的游戏创建全新的数据库，清空缓存与推送池
	 * 为使下一局游戏的时间变更而重载此函数
	 *
	 * @param string 游戏结局
	 * @param mixed $winner 胜利者player对象（可以是数组也可以是单个对象）
	 * @param string $mode 胜利方式（团队或个人）
	 * @return array 胜利玩家的id
	 */
	public function game_end($type = 'timeup', $winners = array(), $mode = 'team') //TODO: 发送推送消息（剧情）
	{
		global $gameinfo, $db, $p, $game_interval;
		
		if(is_array($winners) === false){
			$winners = array($winners);
		}

		$winner_ids = array();
		foreach($winners as $winner){
			$winner_ids[] = $winner->_id;
		}

		//将队伍玩家全部加入胜利者名单中 TODO: 分离各函数
		if($mode === 'team'){
			foreach($winners as $player){
				if($player->teamID != -1){
					$teammates = $db->select('players', '*', array('teamID' => $player->teamID));
					foreach($teammates as $teammate){
						if(false === in_array($teammate['_id'], $winner_ids)){
							$winners[] = new_player($teammate);
						}
					}
				}
			}
		}

		$team_names = array();
		//获取队伍名字
		foreach($winners as $player){
			if($player->teamID == -1){
				$team_names[$player->_id] = '无队伍';
			}else{
				$team = $db->select('team', array('name'), array('_id' => $player['teamID']));
				if($team){
					$team_names[$player->_id] = $team[0]['name'];
				}else{
					$team_names[$player->_id] = '无队伍'; //存储异常
				}
			}
		}
		
		//生成简略信息
		$winner_info = array();
		foreach($winners as &$player){ //此处不用引用会将所有胜利者都变成下标为0的玩家
			$winner_info[] = array(
				'name' => ($player->teamID == -1 ? '' : '['.$team_names[$player->_id].']').$player->name,
				'icon' => $player->icon,
				'motto' => $player->motto
				);
		}
		
		//生成上局胜利玩家
		$winner_name = array();
		foreach($winner_info as $info){
			$winner_name[] = $info['name'];
		}

		$this->insert_news('end_info', array('type' => $type, 'winner' => $winners));

		$gameinfo['gamestate'] = 0;
		$gameinfo['winner'] = $winner_name;
		$gameinfo['winmode'] = $type;
		
		$gameinfo['starttime'] = time() + $game_interval * 60;//$this->get_next_game_time();

		$this->insert_news('end');
		
		$GLOBALS['a']->action('end', array(), true);
		
		$news = $db->select('news', array('time', 'content'));

		$winner_data = array();
		foreach($winners as $winner){
			$winner_data[] = $winner->data;
		}
		
		$db->insert('history', array('gamenum' => $this->gameinfo['gamenum'], 'type' => $type, 'time' => time(), 'winners' => $winner_data, 'winner_info' => $winner_info, 'news' => $news));

		return $winners;
	}
	
	//为实现玩家激活时写入ip信息，重载了此函数
	public function enter_game()
	{
		global $db, $a, $c, $cuser, $gameinfo, $param;
	
		$player_probe = $db->select('players', '_id', array('uid' => $cuser['_id'], 'type' => GAME_PLAYER_USER));
		if($player_probe !== false){
			$a->action('error', array('msg' => ($cuser['username'].' 已经加入了游戏')));
			return;
		}
		
		include(get_mod_path(MOD_NAME).'/init/player.php');
		include(get_mod_path('dtsp').'/include/func.user.dtsp.php');
		
		$gameinfo['validnum'] ++;
		$gameinfo['alivenum'] ++;
		
		$player = $this->new_player();
		
		$db->insert('players', $player);
		
		do{
			$loop = false;
			$GLOBALS['cplayer'] = current_player();
			if($GLOBALS['cplayer'] === false){
				$loop = true;
				usleep(100000);
			}
		}while($loop); //防止从库延迟
		
		$this->insert_news('join', array('username' => $player['name']));
		
		$this->update_user_game_settings(array(
			'icon' => intval($param['icon']),
			'iconuri' => $player['icon'],
			'gender' => $player['gender'],
			'club' => $player['club'],
			'ip' => real_ip()
			));
			
		$c->create($cuser['_id']);
		$GLOBALS['a']->action('brief', array('html' => $this->generate_welcome_message()));
		
		return;
	}
	
	protected function new_player()
	{
		$player = parent::new_player();
		$player['region'] = 1; //避免开局就无法移动
		return $player;
	}
	
	/**
	 * 进入游戏时提交用户偏好，并更新游戏记录
	 * 如果MOD中保存了其他用户偏好，请重载此函数
	 *
	 * return boolean 数据库操作结果
	 */
	protected function update_user_game_settings($userparam)
	{
		global $db, $cuser, $param;
		
		$user['motto'] = isset($param['motto']) ? $param['motto'] : '';
		$user['killmsg'] = isset($param['killmsg']) ? $param['killmsg'] : '';
		$user['lastword'] = isset($param['lastword']) ? $param['lastword'] : '';
		$user['gender'] = $userparam['gender'];
		$user['icon'] = $userparam['icon'];
		$user['iconuri'] = $userparam['iconuri'];
		$user['club'] = $userparam['club'];
		$user['validgames'] = $cuser['validgames'] + 1;
		$user['ip'] = $userparam['ip'];
		$user['lastgame'] = $this->gameinfo['gamenum'];
		
		$condition = array('_id' => $cuser['_id']);
		
		$_SESSION['cuser'] = array_merge($_SESSION['cuser'], $user);
		
		return $db->update('users', $user, $condition);
	}
	
	protected function generate_welcome_message()
	{
		$message = '
<p class="welcome">
<img border="0" src="img/i_hayashida.gif" width="70" height="70"><br /><br />
<font color="#ff0000" face="verdana" size="6">
<span id="br" style="width:100%;filter:blur(add=1,direction=135,strength=9):glow(strength=5,color=gold); font-weight:700; text-decoration:underline">
■ BATTLE ROYALE ■</span></font><br>

任务目标：抵达最终战场并坚持5分钟！<br>
<br>
<font color="#666666">（点击任意处继续）</font>
</p>
		';
		return $message;
	}
	
	/**
	 * 生成禁区顺序
	 * 为修改地图机制而重载此函数
	 *
	 * return array 禁区顺序
	 */
	protected function generate_forbidden_sequence()
	{
		global $m;
		$arealist = $m->allget(true);
		shuffle($arealist);
		$arealist[array_search(0,$arealist)] = $arealist[0];
		$arealist[0] = 0;
		$arealist = array_values($arealist);
		return $arealist;
	}
	
	protected function new_npc(&$player)
	{
		return array_merge(parent::new_npc($player), array(
			'icon' => 'img/dtsp/n_'.$player['icon'].'.png'
			));
	}
	
	protected function np_generate_icon(&$user, $gender)
	{
		global $param, $icon_num;
		
		if(false === isset($param['icon'])){
			$param['icon'] = $user['icon'];
			return $user['iconuri'];
		}
		
		$icon = $param['icon'];
		
		if($icon === 'customed'){
			return 'img/upload/'.md5($user['username']).'.img';
		}else{
			if($icon > $icon_num[$gender]){
				throw_error("头像设置错误");
			}
			
			if($icon == 0){
				$icon = mt_rand(1, $icon_num[$gender]);
			}
		
			return 'img/dtsp/'.$gender.'_'.$icon.'.png';
		}
	}
	
	protected function np_generate_club(&$user)
	{
		return (isset($GLOBALS['param']['club']) && $GLOBALS['param']['club'] > 0 && $GLOBALS['param']['club'] < sizeof($GLOBALS['clubinfo'])) ? $GLOBALS['param']['club'] : random(1, sizeof($GLOBALS['clubinfo']) - 1);
	}
	
	public function game_forbid_area()
	{
		$return = game::game_forbid_area(); //不调用BRA的禁区（BRA实现了禁区死亡），直接调用BRN的禁区，然后重新实现禁区死亡
		
		global $db, $m;
		$forbidden = $this->gameinfo['forbiddenlist'];
		$safe = array();
		$all = array();
		foreach($m->allget() as $mval){
			$i = $mval['id'];
			if(false === in_array($i, $forbidden)){
				$safe[] = $i;
			}
			$all[] = $i;
		}
		
		//禁区死亡
		$players_dying = $db->select('players', '*', array('type' => GAME_PLAYER_USER, 'area' => array('$in' => $forbidden), 'hp' => array('$gt' => 0), 'tactic' => array('$ne' => 3)));
		if(is_array($players_dying)){
			foreach($players_dying as $pdata){
				$player = new_player($pdata);
				if(!in_array($player->area, $forbidden) || $player->tactic == 3){
					continue; //数据库中的数据有可能未更新，因此要在与缓存合并后再次检查
				}
				foreach($player->buff as &$buff){
					switch($buff['type']){
						//八云紫套三件效果
						case 'yukari_suit':
							if($buff['param']['quantity'] >= 3){
								$player->notice('八云紫的力量让你躲避了禁区死亡');
								$player->data['area'] = $safe[array_rand($safe)];
								$player->ajax('location', array('name' => $m->iget($player->data['area']), 'shop' => in_array(intval($player->data['area']), $GLOBALS['shopmap'], true)));
								continue 2; //自动躲避禁区
							}
							
						//毛玉套三件效果
						case 'kedama_suit':
							if($buff['param']['quantity'] >= 3){
								$player->notice('毛玉的力量让你躲避了禁区死亡');
								$player->data['area'] = $safe[array_rand($safe)];
								$player->ajax('location', array('name' => $m->iget($player->data['area']), 'shop' => in_array(intval($player->data['area']), $GLOBALS['shopmap'], true)));
								continue 2; //自动躲避禁区
							}
						
						default:
							break;
					}
				}
				$player->sacrifice(array('type' => 'forbid'));
			}
		}
		unset($players_dying);
		
		//NPC换区
		$this->moving_NPC($all, $safe);
		
		//飞空毛玉
		if($this->gameinfo['round'] == 1){
			$db->delete('players', array('name' => '飞空毛玉', 'type' => GAME_PLAYER_NPC));
		}
		
		return $return;
	}
	
	public function insert_news($type, $args = array())
	{
		
		$content = '';
		switch($type){
			case 'end_info':
				switch($args['type']){
					case 'survive':
						$winner = $args['winner'][0];
						$content = '<span class="system">'.$winner->name.' 在游戏中最后幸存，游戏结束</span>';
						break;
						
					case 'laststand':
						$winner = $args['winner'][0];
						$content = '<span class="system">'.$winner->name.' 成功逃出战场，游戏结束</span>';
						break;
				
					case 'noplayer':
						$content = '<span class="system">无人参加，游戏结束</span>';
						break;
				
					case 'timeup':
						$content = '<span class="system">游戏时限已到仍未分出胜负，所有幸存者被处决</span>';
						break;
					
					case 'restart':
						$content = '<span class="system">游戏被重设</span>';
						break;
					
					case 'stop':
					default:
						$content = '<span class="system">游戏突然停止了</span>';
						break;
				}
				break;
			case 'kill':
				$args['type'] = isset($args['type']) ? $args['type'] : 'default';
				switch($args['type']){
					case 'suicide':
						$content = '<span class="username">'.$args['deceased'].'</span>放弃了希望，自杀身亡';
						break;
						
					default:
						$content = parent::insert_news($type, $args);
						return $content;
						break;
				}
				break;
				
			case 'compose':
				$composer = '<span class="username">'.$args['composer'].'</span>';
				$item = '<span class="weapon">'.$args['item'].'</span>';
				$content = $composer.'合成了'.$item;
				break;
			
			case 'aya_ridicule':
				$attacker = '<span class="username">'.$args['attacker'].'</span>';
				$defender = '<span class="username">'.$args['defender'].'</span>';
				switch(random(0,4)){
					case 0:
						$rhetoric = '<span class="username">'.$defender.'</span>惨遭重创';
						break;
					
					case 1:
						$rhetoric = '<span class="username">'.$defender.'</span>被<span class="username">'.$attacker.'</span>打得灰头土脸';
						break;
					
					case 2:
						$rhetoric = '<span class="username">'.$defender.'</span>受到了<span class="username">'.$attacker.'</span>的打击，吓得落荒而逃';
						break;
					
					case 3:
						$rhetoric = '<span class="username">'.$attacker.'</span>在遭遇战中玩弄<span class="username">'.$defender.'</span>于鼓掌之间';
						break;
					
					default:
						$rhetoric = '由于屡战屡败，<span class="username">'.$defender.'</span>对<span class="username">'.$attacker.'</span>产生了心理阴影';
						break;
				}
				$content = '文文·新闻：'.$rhetoric;
				break;
			
			case 'horai':
				$content = '<span class="username">'.$args['name'].'</span>体内的蓬莱之药发出了光芒，<span class="username">'.$args['name'].'</span>复活了';
				break;
			
			default:
				$content =  parent::insert_news($type, $args);
				if($type != 'damage'){
					$GLOBALS['a']->action('chat_msg', array('msg' => $content, 'time' => time()), true);
				}
				return $content;
				break;
		}
		
		global $db;
		$db->insert('news', array('time' => time(), 'content' => $content));
		
		if($type != 'damage'){
			$GLOBALS['a']->action('chat_msg', array('msg' => $content, 'time' => time()), true);
		}
		
		$this->update_news_cache();
		
		return $content;
	}
	
	public function &summon_npc($nid){
		include (get_mod_path(MOD_NAME).'/init/npc.php');
		
		if(!isset($npcinfo[$nid])){
			return throw_error('NPC #'.$this->data['sk']['id'].' doesn\'t exist');
		}
		
		$npc = $npcinfo[$nid];
		
		$sub = isset($npc['sub']) ? $npc['sub'] : array(array());
		unset($npc['sub']);
		
		$npc = array_merge($npc, $sub[array_rand($sub)]);
		$npc['number'] = 1;
		$npc = $this->new_npc($npc);
		
		$GLOBALS['db']->insert('players', $npc);
		
		return $npc;
	}
	
	/**
	 * 游戏开始时会调用的函数
	 * 为可变地图的储存而重载了此函数。地图数据储存在gameinfo中。
	 *
	 * return null
	 */	 
	public function game_start()
	{
		global $db, $c, $m, $gameinfo, $map, $round_area;
		$round = -1;
		$gameinfo['gamestate'] = 0;
		
		/*===================Maps Initialization==================*/
		$m->reload();	
		
		/*==================Shops Initialization==================*/
		$column = file_get_contents('gamedata/sql/shop.sql');
		$db->create_table('shop', $column);
		unset($column);
		
		/*==================Items Initialization==================*/
		$column = file_get_contents('gamedata/sql/items.sql');
		$db->create_table('items', $column);
		unset($column);
		
		/*=================Players Initialization=================*/
		$column = file_get_contents(get_mod_path('dtsp').'/sql/players.dtsp.sql');
		$db->create_table('players', $column);
		unset($column);
		
		/*===================Team Initialization==================*/
		$column = file_get_contents('gamedata/sql/team.sql');
		$db->create_table('team', $column);
		unset($column);
		
		/*===================News Initialization==================*/
		$column = file_get_contents('gamedata/sql/news.sql');
		$db->create_table('news', $column);
		unset($column);
		
		/*========Generate a sequence of forbidden regions========*/
		$arealist = $this->generate_forbidden_sequence();
		
		/*==================Clean up comet pool===================*/
		$c->clear_all();
		
		/*=====================Save Gameinfo======================*/
		$gameinfo['gamenum'] += 1;
		$gameinfo['round'] = $round;
		$gameinfo['forbiddenlist'] = array();
		$gameinfo['alivenum'] = 0;
		$gameinfo['validnum'] = 0;
		$gameinfo['deathnum'] = 0;
		$gameinfo['arealist'] = $arealist;
		$gameinfo['areatime'] = $gameinfo['starttime'];
		$gameinfo['gamestate'] |= GAME_STATE_START;
		$gameinfo['hdamage'] = 0;
		$gameinfo['hplayer'] = '';
		
		/*======================Insert News=======================*/
		$this->insert_news('start', $gameinfo['gamenum']);
		
		return;
	}
	

	
	/**
	 * 添加N禁时新增的地图物品
	 * 因为重写地图逻辑而重载此方法
	 *
	 * param $round(int) 禁区次数（开局是0）
	 * return null
	 */
	protected function update_mapitem($round)
	{
		global $db, $m;

		$fp = fopen(get_mod_path(MOD_NAME).'/init/mapitem.php', 'r');
		if(!$fp){
			throw_error('Failed to open data file.');
		}
		$alllist = $m->allget(true);//TODO:编号为0的区域应该排除
		
		$data = array();
		while(!feof($fp)){
			$line = fgets($fp, 4096);
			
			if(!$line || substr($line, 0, 2) == '//' || substr($line, 0, 1) == '#' || substr($line, 0, 1) == ';'){
				continue;
			}
			
			//$item = explode(',', $line);
			//array_pop($item);
			
			//由于子属性（json）中可能含有逗号，因此不能使用explode
			$item = array();
			$offset = 0;
			$next_offset = 0;
			for($index = 0; $index < 7; $index++){
				$next_offset = strpos($line, ',', $offset);
				if(false === $next_offset){
					continue 2;
				}
				array_push($item, substr($line, $offset, $next_offset - $offset));
				$offset = $next_offset + 1;
			}
			array_push($item, substr($line, $offset, strrpos($line, ',') - $offset));
			
			if(!$item){
				continue;
			}
			
			if(intval($item[0]) !== intval($round) && $item[0] != 99){
				continue;
			}
			
			for($i = 0; $i < $item[2]; $i++){
				$item[1] = intval($item[1]);
				if(in_array($item[1], $alllist) || intval($item[1]) == 99){//游戏中已有的地点才刷新物品
					$itemdata = array(
						'area' => $item[1] == 99 ? $alllist[array_rand($alllist)] : $item[1],
						'itm' => $item[3],
						'itmk' => $item[4],
						'itme' => intval($item[5]),
						'itms' => intval($item[6]),
						'itmsk' => isset($item[7]) ? $item[7] : ''
						);
					$this->convert_item($itemdata);
					$data[] = $itemdata;
				}				
			}
		}
		
		return $db->batch_insert('items', $data, true);
	}
	
	function check_all_laststand($cplayer = false){
		global $db, $g, $m;
		$final_region = $m->ritget('end', 'id');
		if($cplayer){//如果传参则单独判定$cplayer防止忽略缓存
			$llist = $db->select('players', '_id', array('_id' =>array('$ne' => $cplayer->_id), 'type' => GAME_PLAYER_USER, 'region' => $final_region, 'hp' => array('$gt' => 0)));
			if($cplayer->region == $final_region){
				if(!is_array($llist)){$llist = array();}
				$llist[] = array('_id' => $cplayer->_id);
			}
		}else{
			$llist = $db->select('players', '_id', array('type' => GAME_PLAYER_USER, 'region' => $final_region, 'hp' => array('$gt' => 0)));	
		}
		
		
		if(!$llist){return;}
		$lnum = sizeof($llist);
		
		foreach($llist as $lval){
			if($cplayer && $lval['_id'] == $cplayer->_id){
				$lplayer = &$cplayer;
			}else{
				$lpdata = $this->get_player_by_id($lval['_id']);
				$lplayer = new_player($lpdata);
			}
			if($lnum >= 2){//冻结全部
				if($lplayer->freeze_buff('last_stand')){
					$lplayer->feedback('由于有敌人存在，你的倒计时停止了！');
				}	
			}else{//解冻全部（只有一个）
				if($lplayer->unfreeze_buff('last_stand')){
					$lplayer->feedback('由于敌人消失，你的倒计时重启了！');
				}	
			}							
		}
		
		return;
	}
	
	public function __destruct()
	{
//		if(isset($GLOBALS['cplayer'])){
//			$this->check_all_laststand($GLOBALS['cplayer']);
//			file_put_contents('a.txt','1');
//		}else{
//			$this->check_all_laststand();
//			file_put_contents('a.txt','2');
//		}
		return parent::__destruct();
	}
}