<?php

class game_dtsp extends game_bra
{
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
你是转校生？我是班主任林田。<br />嘿嘿，你很懂挑学校嘛！ (露出邪恶的笑容)<br />

转校手续刚办完，明天就是毕业旅行。<br>
你可真幸运，千万记着不要迟到！<br><br>

张开眼睛后，发现自己在一个像教室的地方。我不是应该去了修学旅行吗···？<br>
「对了，在去修学旅行的巴士中忽然睡意袭来···」<br>
纵览四周，看见其他的学生好像也在。用心地看的话，发现了大家的颈上套上了银色项圈，<br>
用手碰自己的颈，也感觉到冷冷的金属触感。<br>
正在疑惑大家为什么都套上同样的那个银色项圈的时候...<br><br>
突然，从前面的门，一个男人全副武装装备的军人走了进来···。<br><br>
<img border="0" src="./img/n_1.gif" width="70" height="70"><br><br>
「大家好，一年前的时候我也是这次计划的担当者。很荣幸能再担任此次计划的任务。很好！<br>
随着时间日子人民越来越安于现状，过着幸福日子的时候，相信各位已经忘记了国家曾多努力多辛苦才能建成今天的社会地位，<br>
如今国家开始衰退，想再振兴，但人们已经再没有自信，这是很危险的。因此，伟大的人们商量制定了这个计划。<br><br>

<font color="#ff0000" face="verdana" size="6">
<span id="br" style="width:100%;filter:blur(add=1,direction=135,strength=9):glow(strength=5,color=gold); font-weight:700; text-decoration:underline">
■ BATTLE ROYALE ■</span></font><br>

今天起开始，在这里诸位要开始互相杀害对方。<br>
如果你想取下那个项圈，尝试打算逃走的话，你将会立即被杀。<br><br>
直到剩下一人生存为止，乖乖遵守别犯规。<br>
哎呀，老师都忘记了说，这里是一个四面环海的荒岛。<br><br>
而这里是这个岛的分校。<br>
老师会一直在这里看着各位努力。<br><br>
那么，开始说这个计划如何执行。你从这里出去后去哪里也可以。<br>
每天8小时 (0点和8点和6点)，做全岛广播。一日三回。<br><br>
在那里，大家会看到地图，这个区域什么时候危险老师会告知。<br>
好好地了解地图，离开那一个区域，<br>
要很快地从那个区域出来喔。<br><br>
为何会这样说呢，不逃离广播危险区域的范围，那个项圈是会爆炸的。<br><br>
因此呀，潜伏在该区域中的建筑物中也是不行。<br>
就算挖洞隐藏无线电波也会找到你引爆喔。<br>
对了，建筑物平常是可以让你任意隐藏的。<br><br>
但还是你要知道。计划有时间限制。你只有<b><font color="red">一天</font></b>时间去完成。<br><br>
时间够如果还留下不止一人，剩下的那些人的项圈一样会爆炸。因为冠军只能够存活<u>—人</u>。<br><br>
既然参加了游戏就要全力以赴，老师可不想看到没胜利者呢！<br>
你们每个人将被派发到一个物品包，里面有食物和水，指南针，以及一件武器。<br><br>
下面开始，按照学号，拿好你们的东西，一个个离开这里！<br><br>
<br>
<font color="#666666">（点击任意处继续）</font>
</p>
		';
		return $message;
	}
	
	protected function generate_forbidden_sequence()
	{
		$arealist = parent::generate_forbidden_sequence();
		
		//香霖堂不会一禁
		for($i = 0; $i <= $GLOBALS['round_area']; $i++){
			if($arealist[$i] == 14){
				$temp = $arealist[$i];
				$arealist[$i] = $arealist[$GLOBALS['round_area'] + 1];
				$arealist[$GLOBALS['round_area'] + 1] = $temp;
				break;
			}
		}
		
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
		
		global $db, $map;
		$forbidden = $this->gameinfo['forbiddenlist'];
		$safe = array();
		$all = array();
		for($i = 0; $i < sizeof($map); $i ++){
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
				foreach($player->buff as &$buff){
					switch($buff['type']){
						//八云紫套三件效果
						case 'yukari_suit':
							if($buff['param']['quantity'] >= 3){
								$player->notice('八云紫的力量让你躲避了禁区死亡');
								$player->data['area'] = $safe[array_rand($safe)];
								$player->ajax('location', array('name' => $GLOBALS['map'][$player->data['area']], 'shop' => in_array(intval($player->data['area']), $GLOBALS['shopmap'], true)));
								continue 2; //自动躲避禁区
							}
							
						//毛玉套三件效果
						case 'kedama_suit':
							if($buff['param']['quantity'] >= 3){
								$player->notice('毛玉的力量让你躲避了禁区死亡');
								$player->data['area'] = $safe[array_rand($safe)];
								$player->ajax('location', array('name' => $GLOBALS['map'][$player->data['area']], 'shop' => in_array(intval($player->data['area']), $GLOBALS['shopmap'], true)));
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
	 * 游戏结束时（所有结局）会调用的函数
	 * 进行各种初始化动作，为新一局的游戏创建全新的数据库，清空缓存与推送池
	 * 重载了引擎game类的同名方法
	 *
	 * param $type(int) 游戏结局
	 * param $winner(mixed) 胜利者id
	 * param $mode(string) 胜利方式（团队或个人）
	 * return array 胜利玩家的id
	 */
	public function game_end($type = 'timeup', $winner = array(), $mode = 'team') //TODO: 发送推送消息（剧情）
	{
		global $gameinfo, $db, $p, $game_interval;
		
		if(is_array($winner) === false){
			$winner = array($winner);
		}
		
		$winners = $db->select('players', '*', array('_id' => array('$in' => $winner)));
		if(!$winners){
			$winners = array();
		}
		//将队伍玩家全部加入胜利者名单中 TODO: 分离各函数
		if($mode === 'team'){
			foreach($winners as $player){
				if($player['teamID'] != -1){
					$teammates = $db->select('players', '*', array('teamID' => $player['teamID']));
					foreach($teammates as $teammate){
						if(false === in_array($teammate['_id'], $winner)){
							$winner[] = $teammate['_id'];
							$winners[] = $teammate;
						}
					}
				}
			}
		}
		
		//获取队伍名字
		foreach($winners as &$player){
			if($player['teamID'] == -1){
				$player['team'] = '无队伍';
			}else{
				$team = $db->select('team', array('name'), array('_id' => $player['teamID']));
				if($team){
					$player['team'] = $team[0]['name'];
				}else{
					$player['team'] = '无队伍'; //存储异常
				}
			}
		}
		
		//生成简略信息
		$winner_info = array();
		foreach($winners as &$player){ //此处不用引用会将所有胜利者都变成下标为0的玩家
			$winner_info[] = array(
				'name' => ($player['teamID'] == -1 ? '' : '['.$player['team'].']').$player['name'],
				'icon' => $player['icon'],
				'motto' => $player['motto']
				);
		}
		
		//生成上局胜利玩家
		$winner_name = array();
		foreach($winner_info as $info){
			$winner_name[] = $info['name'];
		}
		
		$this->insert_news('end_info', array('type' => $type, 'winner' => $winner));
		
		$gameinfo['gamestate'] = 0;
		$gameinfo['winner'] = $winner_name;
		$gameinfo['winmode'] = $type;
		
		$gameinfo['starttime'] = (ceil(time() / 60)+ $game_interval)*60; //下一局游戏开始时间顺延
		
		$this->insert_news('end');
		
		$GLOBALS['a']->action('end', array(), true);
		
		$news = $db->select('news', array('time', 'content'));
		
		$db->insert('history', array('gamenum' => $this->gameinfo['gamenum'], 'type' => $type, 'time' => time(), 'winners' => $winners, 'winner_info' => $winner_info, 'news' => $news));
		
		return $winner;
	}
}

?>