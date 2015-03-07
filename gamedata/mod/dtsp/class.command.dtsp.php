<?php

class command_dtsp extends command_bra
{
	public function action_handler($action, $param)
	{
		global $a, $g, $cuser;
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
			case 'init':
				$a->action('rage', array('rage' => $this->player->rage));
				parent::action_handler($action, $param);
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
			
			default:
				parent::action_handler($action, $param);
				break;
		}
	}
}

?>