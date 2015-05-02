<?php

class command_dtsp extends command_bra
{
	public function action_handler($action, $param)
	{
		global $a, $g, $cuser, $m,$img_dir;
		$cplayer = $this->player;
		
		//如果用户没有激活弹出激活界面
		if($cplayer === false && $action !== 'enter_game' && !($g->gameinfo['gamestate'] & GAME_STATE_COMBO)){
			$a->action('need_join', $this->get_need_join_data());
			$a->flush();
			return;
		}elseif($cplayer === false && $action !== 'enter_game'){
			$a->action('combo');
			return;
		}
		
		switch($action){
			case 'enter_game':
				$g->enter_game();
				$cplayer = $this->player = $GLOBALS['cplayer'];
			case 'init':
				$a->action('init', false, false, true); //初始化动作拥有最高优先级
				$a->action('game_settings', array('poison_damage' => $GLOBALS['poison']['damage'], 'poison_recover' => $GLOBALS['poison']['recover']));
				$a->action('name', array('name' => $cplayer->name));
				$a->action('avatar', array('src' => $cplayer->icon));
				$a->action('number', array('number' => strval($cplayer->number).' 号'));
				$a->action('gender', array('gender' => $GLOBALS['genderinfo'][$cplayer->gender]));
				$a->action('max_health', array('mhp' => $cplayer->mhp, 'msp' => $cplayer->msp));
				$a->action('health', array('hp' => $cplayer->hp, 'sp' => $cplayer->sp));
				$hr = $cplayer->get_heal_rate();
				$a->action('heal_speed', array('hpps' => $hr['hp'], 'spps' => $hr['sp']));
				$a->action('pose', array('tid' => $cplayer->pose));
				$a->action('tactic', array('tid' => $cplayer->tactic));
				$a->action('club', array('name' => $GLOBALS['clubinfo'][$cplayer->club]));
				$a->action('team', $cplayer->get_team_info());
				$a->action('battle_data', array('att' => $cplayer->att, 'def' => $cplayer->def));
				$a->action('proficiency', array('proficiency' => $cplayer->proficiency));
				$a->action('money', array('money' => $cplayer->money));
				$a->action('area_info', $GLOBALS['g']->get_areainfo());
				$a->action('location', array('name' => $m->iget($cplayer->area),'background' => 'img/'.$img_dir.'/'.$m->riiget($cplayer->region,'background'), 'shop' => in_array(intval($cplayer->area), $GLOBALS['shopmap'], true)));
				$a->action('weather', array('name' => $GLOBALS['weatherinfo'][$GLOBALS['gameinfo']['weather']]));
				$a->action('item', array('equipment' => $cplayer->parse_equipment(), 'package' => $cplayer->parse_package(), 'capacity' => intval($cplayer->capacity)));
				$a->action('buff_name', $GLOBALS['buff_name']);
				$a->action('buff', array('buff' => $cplayer->parse_buff()));
				$a->action('exp', array('current' => $cplayer->exp, 'target' => $cplayer->upexp, 'level' => $cplayer->lvl));
				$a->action('currency', array('name' => $GLOBALS['currency']));
				$a->action('rage', array('rage' => $this->player->rage));
				
				if(isset($cplayer->action['battle'])){
					//战斗状态中
					$enemy = $GLOBALS['db']->select('players', '*', array('_id' => $cplayer->action['battle']['pid']));
					$enemy = new_player($enemy[0]);
					$a->action('battle', array(
						'enemy' => $cplayer->get_enemy_info($enemy),
						'end' => false
						));
				}
				
				if(false === $cplayer->is_alive()){
					//已死亡
					$cplayer->show_death_info();
				}
				
				break;
				
			case 'pose':
				global $poseinfo;
				$chgpose = intval($param['tid']);
				$cplayer->pose($chgpose);
				$cplayer->feedback('基础姿态成功修改为'.$poseinfo[$chgpose]);
				break;
			
			case 'tactic':
				global $tacticinfo;
				$chgtactic = intval($param['tid']);
				$cplayer->tactic($chgtactic);
				$cplayer->feedback('应战方针成功修改为'.$tacticinfo[$chgtactic]);
				break;
			
			case 'wound_dressing':
				if(false === isset($param['position'])){
					$cplayer->error('请指定受伤部位');
				}
				$cplayer->wound_dressing($param['position']);
				break;
			
			case 'compose':
				include(get_mod_path('dtsp').'/include/settings.itemmix.dtsp.php');
				parent::action_handler($action, $param);
				break;
			
			default:
				parent::action_handler($action, $param);
				break;
		}
		//最终战场特殊判定：1. 增加倒计时  2. 如果最终战场的玩家数在2以上，则暂停所有玩家的倒计时
		if($cplayer){
			$g->check_all_laststand($cplayer);
		}else{
			$g->check_all_laststand();
		}
	}
}