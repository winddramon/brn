<?php

class player_dtsp extends player_bra
{
	
	/**
	 * 移动
	 * 如果目的地相同会自动转换为探索
	 * 为修改地图判断而重载此函数
	 *
	 * param $destination(int) 目的地的地图编号
	 * return null
	 */
	public function move($destination, $across_regions = false, $ignore_search = false)
	{
		if(false === $this->is_alive()){
			return $this->error('你已经死了');
		}
		
		global $g, $m, $shopmap, $last_stand;
		$data = &$this->data;
		$destination = intval($destination);
		$final_region = $m->ritget('end', 'id');
		if($data['action'] && isset($data['action']['battle'])){
			$this->error('战斗中，无法移动');
		}
		
		if(isset($this->package[0])){
			$this->error('请在移动前先决定如何处理拾取到的物品');
		}
		
		if(intval($data['area']) === $destination && !$ignore_search){
			//已经在目的地
			$this->search();
		}else{
			
			$mapname = $m->iget($destination);
			if(!$mapname){
				$this->error('无效的目的地');
			}
			$dregion = $m->iget($destination, 'r');
			if($m->iget(intval($data['area']), 'r') !== $dregion && !$across_regions && $m->riiget($this->region, 'type') != 'start'){
				$this->error('无法跨区移动');
			}
			$status = $this->area_status($destination);
			
			if($status === false && !$across_regions){
				$this->error($mapname.' 为禁区，禁止进入');
			}
			
			//消耗
			$consumption = $this->get_consumption('move');
			
			$this->check_health($consumption, 'move');
			
			
			
			//开始移动
			$data['area'] = $destination;
			$data['region'] = $dregion;
			$this->feedback('移动到了 '.$mapname);
			$this->ajax('location', array('name' => $mapname, 'shop' => in_array(intval($data['area']), $shopmap, true)));
			if(!$ignore_search){
				$this->discover('move');
			}
			
			$hr = $this->get_heal_rate();
			$this->ajax('heal_speed', array('hpps' => $hr['hp'], 'spps' => $hr['sp']));
			
			//进入最终战场的时候 增加倒计时
			if($dregion == $final_region){
				$this->set_last_stand();
//				$g->check_all_laststand($this);	
			}
			$this->set_region_jump();
		}
	}
	
	public function search()
	{
		global $g, $m;
		$final_region = $m->ritget('end', 'id');
		$this->region = $m->iget($this->area,'r');
		parent::search();
		if($this->region == $final_region){
			$this->set_last_stand();
//			$g->check_all_laststand($this);	
		}
		$this->set_region_jump();
	}
	
	protected function set_last_stand(){
		global $last_stand;
		$already = false;
		foreach($this->data['buff'] as &$buff){
			switch($buff['type']){
				case 'last_stand':
					$already = true;
					break;
					
				default:
					break;
			}
		}
		if(!$already){
			$this->buff('last_stand', $last_stand['win'], array('entrytime' => time()));
		}	
		return;
	}

	protected function set_region_jump(){
		global $m;
		$already = false;
		foreach($this->data['buff'] as &$buff){
			switch($buff['type']){
				case 'region_jump':
					if($buff['param']['destination'] == $m->riiget($this->region, 'destination')){
						$already = true;
					}
					break;

				default:
					break;
			}
		}
		if(!$already && $m->riiget($this->region, 'duration') && $m->riiget($this->region, 'destination')){
			$this->buff('region_jump', $m->riiget($this->region, 'duration'), array('destination' => $m->riiget($this->region, 'destination')));
		}
	}
	protected function get_consumption($action)
	{
		$consumption = parent::get_consumption($action);
		
		if($GLOBALS['g']->gameinfo['weather'] == 5){
			$consumption['sp'] += 6;
		}
		
		return $consumption;
	}

	protected function levelup($extra_text = '')
	{
		global $g;

		if(!$this->is_alive()){
			return;
		}

		$extra_hp = $g->random(10, 20);
		$extra_att = $g->random(0, 10);
		$extra_def = $g->random(10, 20);

		$this->data['baseatt'] += $extra_att;
		$this->data['basedef'] += $extra_def;
		$this->calculate_battle_info();

		$this->data['mhp'] += $extra_hp;
		$this->heal('hp', $extra_hp);
		$this->heal('sp', 50);

		$this->ajax('max_health', array('mhp' => $this->mhp, 'msp' => $this->msp));

		$extra_text .= '，生命提升了'.$extra_hp;
		$extra_text .= '，攻击提升了'.$extra_att;
		$extra_text .= '，防御提升了'.$extra_def;

		player::levelup($extra_text);
	}
	
	protected function enemy_found_rate(player $enemy)
	{
		$rate = parent::enemy_found_rate($enemy);
		
		if($this->pose == 6){
			if(in_array('kedama', $enemy->skill)){
				$rate *= 0.2;
			}else{
				$rate *= 1.25;
			}
		}
		return $rate;
	}

	public function attack_by_weapon($weapon)
	{
		//换上武器
		$current_weapon = $this->equipment['wep'];
		$this->data['equipment']['wep'] = $weapon;
		$this->calculate_battle_info(false);

		//攻击
		$this->attack();

		//换回来
		$this->data['equipment']['wep'] = $current_weapon;
		$this->calculate_battle_info(false);

		//更新武器名
		$this->ajax('item', array('equipment' => $this->parse_equipment()));
	}

	public function calculate_battle_info($ajax = true)
	{
		parent::calculate_battle_info(false);
		
		$att_modulus = 1;
		$def_modulus = 1;
		
		foreach($this->data['buff'] as &$buff){
			switch($buff['type']){
				case 'att_buff':
					$this->data['att'] += $buff['param']['effect'];
					break;
					
				case 'def_buff':
					$this->data['def'] += $buff['param']['effect'];
					break;
					
				case 'att_debuff':
					$this->data['att'] -= $buff['param']['effect'];
					break;
					
				case 'def_debuff':
					$this->data['def'] -= $buff['param']['effect'];
					break;
				
				case 'wandering_soul':
					$def_modulus *= 2;
					break;
					
				case 'control_rod':
					$att_modulus *= 1.15;
					$def_modulus *= 0.9;
					break;
					
				case 'scapegoat_dummy':
					$att_modulus *= 1;
					$def_modulus *= 1.1;
					break;
				
				case 'grand_patriots_elixir':
					$att_modulus *= 1.1;
					$def_modulus *= 1.1;
					break;
				
				//灵梦套四件效果
				case 'reimu_suit':
					if($buff['param']['quantity'] >= 4){
						$att_modulus *= 1.2;
					}
					break;
				
				//幽幽子套四件效果
				case 'yuyuko_suit':
					if($buff['param']['quantity'] >= 4){
						$att_modulus *= 1 + log($this->killnum + 1) / 1000;
					}
					break;
				
				case 'ridicule':
					$def_modulus *= 0.75;
					break;
				
				default:
					break;
			}
		}
		
		$this->data['att'] *= $att_modulus;
		$this->data['def'] *= $def_modulus;
		
		if($ajax){
			$this->ajax('battle_data', array('att' => $this->att, 'def' => $this->def));
		}
	}
	
	public function buff($name, $duration = 0, array $param = array())
	{
		switch($name){
			//新buff直接覆盖旧buff
			case 'last_stand':
			case 'region_jump':
				foreach($this->data['buff'] as &$buff){
					if($buff['type'] === $name){
						$buff['duration'] = ($duration == 0) ? 0 : time() + $duration;
						$buff['param'] = array_merge($buff['param'], $param);
						$this->ajax('buff', array('buff' => $this->parse_buff()));
						return;
					}
				}
				break;
			//不叠加新buff而是在存在buff上增加时长
			case 'last_stand':
			case 'region_jump':
			case 'tolerance':
			case 'invincible':
			case 'scarlet_moonlight':
			case 'ultrashort_EEG':
			case 'fantasy_nature':
			case 'att_buff':
			case 'def_buff':
			case 'extra_package':
			case 'extra_hp':
			case 'ridicule':
				foreach($this->data['buff'] as &$buff){
					if($buff['type'] === $name && $param == (isset($buff['param']) ? $buff['param'] : array())){
						if($duration == 0 || $buff['time'] == 0){
							$buff['time'] = 0;
						}else{
							$buff['time'] += $duration;
						}
						$this->ajax('buff', array('buff' => $this->parse_buff()));
						return;
					}
				}
				break;
			
			case 'poison':
				foreach($this->buff as $buff){
					switch($buff['type']){
						case 'scarlet_suit':
							//斯卡雷特套两件效果
							if($buff['param']['quantity'] >= 2){
								$this->feedback('发动「抗毒血清」，中毒效果被抵消了');
							}
							return;
						
						default:
							break;
					}
				}
				break;
			
			default:
				break;
		}
		
		parent::buff($name, $duration, $param);
		
		switch($name){
			case 'ageless_dream':
				$hr = $this->get_heal_rate();
				$this->ajax('heal_speed', array('hpps' => $hr['hp'], 'spps' => $hr['sp']));
				break;
			
			case 'extra_package':
				$this->data['capacity'] += intval($param['effect']);
				$this->rearrange_package();
				$this->ajax('item', array('equipment' => $this->parse_equipment(), 'package' => $this->parse_package(), 'capacity' => $this->capacity));
				break;
			
			case 'extra_hp':
				$ratio = $param['effect'] / $this->data['mhp'];
				$this->data['mhp'] += $param['effect'];
				$this->data['hp'] *= 1 + $ratio;
				$this->ajax('max_health', array('mhp' => $this->mhp, 'msp' => $this->msp));
				$this->ajax('health', array('hp' => $this->hp));
				break;
			
			case 'att_buff':
			case 'def_buff':
			case 'att_debuff':
			case 'def_debuff':
			case 'control_rod':
			case 'scapegoat_dummy':
			case 'grand_patriots_elixir':
			case 'ridicule':
			case 'wandering_soul':
				$this->calculate_battle_info();
				break;
			
			case 'recover_hp':
			case 'recover_sp':
				$hr = $this->get_heal_rate();
				$this->ajax('heal_speed', array('hpps' => $hr['hp'], 'spps' => $hr['sp']));
				break;
			
			//毛玉套两件效果
			case 'kedama_suit':
				$this->calculate_battle_info();
				break;
			
			case 'yukari_suit':
				//八云紫套三件效果
				if($param['quantity'] >= 3){
					$this->buff('extra_package', 0, array('effect' => 2, 'origin' => 'yukari'));
				}else{
					foreach($this->buff as $bid => &$buff){
						if($buff['type'] == 'extra_package' && isset($buff['param']['origin']) && $buff['param']['origin'] == 'yukari'){
							$this->remove_buff($bid);
						}
					}
				}
				break;
			
			case 'eirin_suit':
				//永琳套两件效果 永琳套四件效果
				if($param['quantity'] >= 4){
					foreach($this->buff as $bid => &$buff){
						if($buff['type'] == 'extra_hp' && isset($buff['param']['origin']) && $buff['param']['origin'] == 'eirin2'){
							$this->remove_buff($bid);
						}
					}
					$this->buff('extra_hp', 0, array('effect' => 1000, 'origin' => 'eirin4'));
				}else if($param['quantity'] >= 2){
					foreach($this->buff as $bid => &$buff){
						if($buff['type'] == 'extra_hp' && isset($buff['param']['origin']) && $buff['param']['origin'] == 'eirin4'){
							$this->remove_buff($bid);
						}
					}
					$this->buff('extra_hp', 0, array('effect' => 300, 'origin' => 'eirin2'));
				}else{
					foreach($this->buff as $bid => &$buff){
						if($buff['type'] == 'extra_hp' && isset($buff['param']['origin']) && ($buff['param']['origin'] == 'eirin2' || $buff['param']['origin'] == 'eirin4')){
							$this->remove_buff($bid);
						}
					}
				}
				break;
			
			default:
				break;
		}
	}
	
	public function freeze_buff($type){
		$flag = false;
		foreach($this->data['buff'] as &$bval){
			if($bval['type'] == $type){
				if($bval['time'] > 0 && $bval['time'] - time() > 0){
					$bval['param']['duration'] = $bval['time'] - time();
					$bval['param']['freezetime'] = time();
					$bval['time'] = 0;
					$this->ajax('buff', array('buff' => $this->parse_buff()));
					$flag = true;
					break;
				}
			}
		}
		return $flag;
	}
	
	public function unfreeze_buff($type){
		$flag = false;
		foreach($this->data['buff'] as &$bval){
			if($bval['type'] == $type){
				if($bval['time'] == 0 && isset($bval['param']['duration']) && isset($bval['param']['freezetime'])){
					$bval['time'] = time() + $bval['param']['duration'];
					$this->ajax('buff', array('buff' => $this->parse_buff()));
					$flag = true;
					break;
				}
			}
		}
		return $flag;
	}
	
	public function remove_buff($key)
	{
		$buff = $this->data['buff'][$key];
		
		parent::remove_buff($key);
		
		//必须放在继承函数之后，否则诸如攻击增益的效果将计算buff取消前的数值
		switch($buff['type']){
			case 'last_stand':
				global $m;
				if($this->region == $m->ritget('end', 'id')){
					$GLOBALS['g']->game_end('laststand', $this, 'individual');
				}
				break;
			case 'region_jump':
				global $m;
				if($buff['param']['destination'] == $m->riiget($this->region, 'destination')){//条件：所在地图的区域的目的地与buff记载的目的地相同
					$this->move($m->get_region_access($buff['param']['destination']), true, true);
					$this->feedback('时间到了，必须得移动了！');
				}

				break;
			case 'ageless_dream':
				$hr = $this->get_heal_rate();
				$this->ajax('heal_speed', array('hpps' => $hr['hp'], 'spps' => $hr['sp']));
				break;
			
			case 'ageless_land':
				if($buff['time'] < time()){
					//成功攻击并取消buff
				}else{
					//达到时限，造成伤害
					$damage = $this->damage(500, array('pid' => $buff['param']['source'], 'type' => 'ageless_land'));
					$this->feedback('寿命「无寿国への約束手形」 发动了，造成了 '.$damage.' 点伤害');
				}
				break;
			
			case 'lunar_incense':
				$this->sacrifice(array('pid' => $buff['param']['killer']));
				break;
			
			case 'extra_package':
				$this->data['capacity'] -= intval($buff['param']['effect']);
				$this->rearrange_package();
				$this->ajax('item', array('equipment' => $this->parse_equipment(), 'package' => $this->parse_package(), 'capacity' => $this->capacity));
				break;
			
			case 'extra_hp':
				$ratio = $buff['param']['effect'] / $this->mhp;
				$this->data['hp'] *= 1 - $ratio;
				$this->data['mhp'] -= $buff['param']['effect'];
				$this->ajax('max_health', array('mhp' => $this->mhp, 'msp' => $this->msp));
				$this->ajax('health', array('hp' => $this->hp));
				break;
			
			case 'att_buff':
			case 'def_buff':
			case 'att_debuff':
			case 'def_debuff':
			case 'control_rod':
			case 'scapegoat_dummy':
			case 'grand_patriots_elixir':
			case 'ridicule':
			case 'wandering_soul':
				$this->calculate_battle_info();
				break;
			
			//取消毛玉套两件效果
			case 'kedama_suit':
				$this->calculate_battle_info();
				break;
			
			case 'yukari_suit':
				//取消八云紫套三件效果
				foreach($this->buff as $bid => &$buff){
					if($buff['type'] == 'extra_package' && isset($buff['param']['origin']) && $buff['param']['origin'] == 'yukari'){
						$this->remove_buff($bid);
					}
				}
				break;
			
			case 'eirin_suit':
				//取消永琳套两件效果 取消永琳套四件效果
				foreach($this->buff as $bid => &$buff){
					if($buff['type'] == 'extra_hp' && isset($buff['param']['origin']) && ($buff['param']['origin'] == 'eirin2' || $buff['param']['origin'] == 'eirin4')){
						$this->remove_buff($bid);
					}
				}
				break;
			
			default:
				break;
		}
	}
	
	protected function buff_handler(&$buff, &$param)
	{
		switch($buff['type']){
			//霖之助套三件效果
			case 'rinnosuke_suit':
				if($buff['param']['quantity'] >= 3){
					$this->data['money'] += $param['lasttime'];
					$this->ajax('money', array('money' => $this->money));
				}
				break;
			
			default:
				parent::buff_handler($buff, $param);
				break;
		}
	}
	
	public function get_heal_rate()
	{
		$heal_rate = $this->get_base_heal_rate();
		
		$hpr = $heal_rate['hp'];
		$spr = $heal_rate['sp'];
		
		
		global $poison;
		$damager = 0;
		foreach($this->buff as $buff){
			switch($buff['type']){
				case 'poison':
					$damager += $poison['damage'];
					if(false === $poison['recover']){
						$hpr = 0;
					}
					break;
				
				case 'ageless_dream':
					$damager += $this->lvl;
					$hpr = 0;
					break;
				
				default:
					break;
			}
		}
		
		return array('hp' => $hpr - $damager, 'sp' => $spr);
	}
	
	public function get_base_heal_rate()
	{
		$hr = parent::get_base_heal_rate();
		$hp_modulus = 1;
		$sp_modulus = 1;
		
		//凪
		if($GLOBALS['g']->gameinfo['weather'] == 11){
			$hp_modulus *= 2.5;
		}
		
		foreach($this->data['buff'] as &$buff){
			switch($buff['type']){
				case 'recover_hp':
					$hr['hp'] += $buff['param']['effect'];
					break;
					
				case 'recover_sp':
					$hr['sp'] += $buff['param']['effect'];
					break;
				
				//永琳套四件效果
				case 'eirin_suit':
					if($buff['param']['quantity'] >= 4){
						$hp_modulus *= 3;
					}
					break;
				
				case 'scarlet_suit':
					//斯卡雷特套四件效果
					if($buff['param']['quantity'] >= 4){
						$hp_modulus *= 0;
					}
					break;
				
				default:
					break;
			}
		}
		
		if(in_array('Roach', $this->skill)){
			$hp_modulus *= 2;
		}
		
		if(in_array('Shin-Roach', $this->skill)){
			$hp_modulus *= 10;
		}
		
		$hr['hp'] *= $hp_modulus;
		$hr['sp'] *= $sp_modulus;
		
		return $hr;
	}
	
	public function get_potion_effect()
	{
		$modulus = parent::get_potion_effect();
		
		/*foreach($this->buff as &$buff){
			switch($buff['type']){
				//永琳套五件效果
				case 'eirin_suit':
					if($buff['param']['quantity'] >= 5){
						$modulus['hp'] *= 2;
					}
					break;
				
				default:
					break;
			}
		}*/
		
		return $modulus;
	}
	
	public function damage($damage, array $source = array(), array $except_buff = array())
	{
		foreach($this->data['buff'] as $key => &$buff){
			if(in_array($key, $except_buff)){
				continue;
			}
			switch($buff['type']){
				case 'invincible':
					$this->feedback('无敌中，伤害免疫');
					return 0;
					break;
				
				case 'shield':
					$neutralize = $damage * $buff['param']['effect'];
					if($neutralize >= $buff['param']['amount']){
						$offset = $buff['param']['amount'];
						$this->remove_buff($key);
						$this->feedback('身代抵消掉了 '.(intval($offset * 10) / 10).' 点伤害');
						return $offset + $this->damage($damage - $offset, $source, $except_buff);
					}else{
						$buff['param']['amount'] -= $neutralize;
						$this->feedback('身代抵消掉了 '.(intval($neutralize * 10) / 10).' 点伤害');
						$except_buff[] = $key;
						return $neutralize + $this->damage($damage - $neutralize, $source, $except_buff);
					}
					break;
				
				default:
					break;
			}
		}
		
		return parent::damage($damage, $source);
	}
	
	/**
	 * 物品合成
	 * bra和thbr的加熟已经合并
	 *
	 * return boolean 合成成功或失败
	 */
	public function item_compose($iids)
	{
		if(false === $this->is_alive()){
			return $this->error('你已经死了');
		}
		
		$items = array();
		$check_repeat = array();
		foreach($iids as $iid){
			if(!isset($this->package[$iid])){
				return $this->error('物品不存在');
			}
			if(isset($check_repeat[$iid])){
				return $this->error('请不要选择重复的物品');
			}
			$check_repeat[$iid] = true;
			$items[] = $this->package[$iid]['n'];
		}
		$items_bak = $items;
		
		global $mixinfo;
		foreach($mixinfo as $mix){
			$items = $items_bak;
			foreach($items as $ikey => $item){
				foreach($mix['stuff'] as $skey => $stuff){
					if($stuff === $item){
						unset($items[$ikey]);
						unset($mix['stuff'][$skey]);
						break;
					}
				}
			}
			if(sizeof($items) === 0 && sizeof($mix['stuff']) === 0){
				//合成成功
				foreach($iids as $iid){
					if($this->item_compose_entire($this->package[$iid])){
						$this->item_consume($iid, $this->package[$iid]['s'], false);
						//$this->notice($this->package[$iid]['n'].' 用光了');
						//unset($this->package[$iid]);
					}else{
						$this->item_consume($iid, 1, false);
					}
				}
				$this->rearrange_package();
				$result = array(
					'n' => $mix['result'][0],
					'k' => $mix['result'][1],
					'e' => $mix['result'][2],
					's' => $mix['result'][3],
					'sk' => (isset($mix['result'][4]) && is_array($mix['result'][4])) ? $mix['result'][4] : array()
					);
				$GLOBALS['g']->convert_item($result);
				$GLOBALS['g']->insert_news('compose', array('composer' => $this->name, 'item' => $result['n']));
				$this->data['proficiency']['d'] ++;
				$this->data['package'][0] = $result;
				$this->rearrange_package();
				$this->feedback(implode('、', $items_bak).'合成出了'.$result['n']);
				$this->ajax('item', array('package' => $this->parse_package()));
				return true;
			}
		}
		$this->error(implode('、', $items_bak).'不能合成');
	}
	
	protected function area_status($area)
	{
		foreach($this->buff as &$buff){
			switch($buff['type']){
				//八云紫套四件效果
				case 'yukari':
					if($buff['param']['quantity'] >= 4){
						return true;
					}
					break;
				
				default:
					break;
			}
		}
		
		return parent::area_status($area);
	}
	
	public function get_envenomable_items()
	{
		$result = array();
		
		if(in_array('Glutton', $this->skill) || in_array('Toxicology', $this->skill)){
			if($this->equipment['wep']['n'] !== ''){
				$result['wep'] = $this->equipment['wep'];
			}
		}
		
		foreach($this->package as $iid => $item){
			if(substr($item['k'], 0, 1) === 'H'){
				$result[$iid] = $item;
			}
		}
		
		return $result;
	}
	
	public function get_poison_power($kind, $item_e)
	{
		$power = parent::get_poison_power($kind, $item_e);
		if(substr($kind, 0, 1) === 'H'){
			$power *= isset($this->skill['Glutton']) ? 1.5 : 1;
		}
		
		foreach($this->buff as &$buff){
			switch($buff['type']){
				//魔理沙套两件效果
				case 'marisa_suit':
					if($buff['param']['quantity'] >= 2){
						if(substr($kind, 0, 1) === 'H'){
							$power *= 1.25;
						}
					}
					break;
				
				default:
					break;
			}
		}
		
		return $power;
	}

	public function get_enemy_info(player $enemy, $end = false)
	{
		$info = parent::get_enemy_info($enemy, $end);

		if(strval($enemy->teamID) !== '-1' && $enemy->teamID === $this->teamID){

		}else if($enemy->is_alive()){
			foreach($this->package as $iid => &$item){
				if($item['n'] === '生薬「国士無双の薬」'){
					$GPE_num = 0;
					foreach($this->buff as $bid => &$buff){
						if($buff['type'] === 'grand_patriots_elixir'){
							$GPE_num ++;
						}
					}
					if($GPE_num >= 3){
						array_unshift($info['action'], 'item'.$iid);
					}
					break;
				}

				if($item['k'] === 'SW'){
					array_unshift($info['action'], 'item'.$iid);
				}
			}
		}

		return $info;
	}

	protected function found_item($item)
	{
		global $g, $trap_injure_rate;
		if($item['k'] === 'TO'){
			foreach($this->buff as &$buff){
				switch($buff['type']){
					//琪露诺套五件效果
					case 'cirno_suit':
						if($buff['param']['quantity'] >= 5){
							if(!isset($this->package[0]) && $g->determine(50)){
								$this->feedback('遭遇了埋伏好的 '.$item['n'].' ，发动「液氮排爆」回收陷阱');
								$trap = array(
									'n' => $item['n'],
									'k' => 'TN',
									'e' => isset($item['sk']['effect']) ? $item['sk']['effect'] : $item['e'],
									's' => 1,
									'sk' => $item['sk']
								);
								$this->data['package'][0] = $trap;
								$this->ajax('item', array('package' => $this->parse_package()));
								return;
							}
						}
						break;

					default:
						break;
				}
			}
		}

		parent::found_item($item);

		if($item['k'] === 'TO'){
			if(isset($item['sk']['steal'])){
				$pid = isset($item['sk']['owner']) ? $item['sk']['owner'] : false;
				if(!$pid){
					return;
				}

				$player_data = $g->get_player_by_id($item['sk']['owner']);
				if(!$player_data){
					return;
				}
				$thief = new_player($player_data);

				$lost_money = intval($this->money * $item['sk']['steal']);
				$this->data['money'] -= $lost_money;
				$thief->data['money'] += $lost_money;
				$this->feedback($item['n'].' 闪耀着一道金光，身上的'.$GLOBALS['currency'].'消失了'.$lost_money);
				$thief->notice($item['n'].' 被触发了，你获得了'.$lost_money.$GLOBALS['currency']);
				$this->ajax('money', array('money' => $this->money));
				$thief->ajax('money', array('money' => $thief->money));
			}

			//受伤
			if($g->determine($trap_injure_rate)){
				$this->buff('injured_body');
				$this->feedback('你的胸部受伤了');
			}
			if($g->determine($trap_injure_rate)){
				$this->buff('injured_arm');
				$this->feedback('你的碗部受伤了');
			}
			if($g->determine($trap_injure_rate)){
				$this->buff('injured_head');
				$this->feedback('你的头部受伤了');
			}
			if($g->determine($trap_injure_rate)){
				$this->buff('injured_foot');
				$this->feedback('你的腿部受伤了');
			}
		}
	}

	protected function get_emptive_rate(player $enemy)
	{
		$rate = parent::get_emptive_rate($enemy);

		foreach($this->buff as &$buff){
			switch($buff['type']){
				//十六夜套两件效果
				case 'sakuya_suit':
					if($buff['param']['quantity'] >= 2){
						$rate *= 1.5;
					}
					break;

				//古明地套两件效果
				case 'komeiji_suit':
					if($buff['param']['quantity'] >= 2){
						$rate *= 1.1;
					}
					break;

				default:
					break;
			}
		}

		if(isset($this->equipment['wep']['sk']['emptive-buff'])){
			$rate *= $this->equipment['wep']['sk']['emptive-buff'];
		}

		return $rate;
	}

	protected function get_shop_items($condition)
	{
		$items = parent::get_shop_items($condition);
		
		if(!$items){
			return $items;
		}
		
		foreach($this->buff as &$buff){
			switch($buff['type']){
				//霖之助套装两件效果
				case 'rinnosuke_suit':
					if($buff['param']['quantity'] >= 2){
						foreach($items as &$item){
							$item['price'] = intval($item['price'] * 0.7);
						}
					}
					break;
				
				default:
					break;
			}
		}
		
		return $items;
	}

	public function experience($add = 1)
	{
		foreach($this->buff as &$buff){
			switch($buff['type']){
				//上白泽套装四件效果
				case 'keine_suit':
					if($buff['param']['quantity'] >= 4){
						$add *= 2;
					}
					break;

				default:
					break;
			}
		}

		parent::experience($add);
	}

	public function sacrifice($source = array())
	{
		global $g;

		foreach($this->buff as $bid => $buff){
			switch($buff['type']){
				case 'horai':
					$this->heal('hp', $this->mhp);
					$this->heal('sp', $this->msp);
					$this->remove_buff($bid);
					$this->feedback('你死亡了');
					$this->feedback('潜伏在体内的蓬莱之药从全身各处爆发出来，发出了耀眼的光芒，你复活了！');
					$g->insert_news('horai', array('name' => $this->name));
					return;

				default:
					break;
			}
		}

		if(isset($source['pid']) && false !== $source['pid']){
			$killer_data = $g->get_player_by_id($source['pid']);

			if(!$killer_data){
				parent::sacrifice($source);
				return;
			}

			$killer = new_player($killer_data);

			foreach($killer->buff as &$buff){
				switch($buff['type']){
					//幽幽子套两件效果
					case 'yuyuko_suit':
						if($buff['param']['quantity'] >= 2){
							$killer->notice('发动 亡郷「亡我郷 -さまよえる魂-」');
							$killer->buff('wandering_soul', 60);
						}
						break;

					default:
						break;
				}
			}
		}

		parent::sacrifice($source);
	}

	protected function parse_item_text(&$items)
	{
		global $iteminfo, $sk_define;
		
		foreach($items as &$item){
			$k = $iteminfo['default'];
			foreach($iteminfo as $iikey => $iival){
				if(strpos($item['k'],$iikey)===0){
					$k = $iival;
					break;
				}
			}
			
//			if(isset($iteminfo[$item['k']])){
//				$k = $iteminfo[$item['k']];
//			}else{
//				$k = $iteminfo['default'];
//			}
			
			if(isset($item['sk']['alt']) && (substr($item['k'], 0, 1) === 'W')){
				if(isset($iteminfo['W'.$item['sk']['alt']['k']])){
					$k .= ' <span class="alt">'.$iteminfo['W'.$item['sk']['alt']['k']].'</span>';
				}else{
					$k .= ' <span class="alt">'.$iteminfo['default'].'</span>';
				}
			}
			
			$item['k'] = $k;
			
			if($item['s'] == 0){
				$item['s'] = '∞';
			}
			$item['skinfo'] = '';
			if($item['sk']){
				foreach($item['sk'] as $key => $val){
					if(isset($sk_define[$key])){
						$item['skinfo'] .= $sk_define[$key]['name'].'+';
					}
				}
				if(!empty($item['skinfo'])){$item['skinfo'] = substr($item['skinfo'],0,-1);}
			}
			unset($item['sk']);
		}
	}
}