<?php

//MOD名
$mod_name = 'Battle Royale Prototype';

//MOD版本
$mod_version = '0.01 indev';

//MOD作者
$mod_author = array(
	array('name' => 'Yoshiko_G', 'url' => 'http://'),
	array('name' => 'Martin Chloride', 'url' => 'http://martincl2.me/')
	);

//MOD协议
$mod_license = array('name' => 'CC-BY-SA 协议', 'url' => 'http://creativecommons.org/licenses/by-sa/3.0/legalcode');

//MOD补充说明
$mod_extra_info = '';
	
//公告
$bulletin = '<p>内测阶段，图像资源皆为网络搜集、临时采用，如侵犯您的权益请告知我们，我们会第一时间撤除。</p><p>第一次游玩前请阅读帮助</p>';

//模板名
$template_name = 'dtsp';

//头像文件夹
$avatar_dir = 'dtsp';

//图像文件夹
$img_dir = 'dtsp';

//同IP可以注册的账号数，防止恶意注册
$ip_user_limit = 5;

$last_stand = array(
	'win' => 300//独存胜利的时间
);

//禁区时间（秒）
$round_time = 1800;

//一局结束以后的等待时间（分钟）
$game_interval = 3;

//一局开始前的准备时间（分钟）
$game_prepare = 1;

//游戏锁定时间（分钟）
$game_close = 60;

//游戏强制结束时间（分钟）
$game_timeup = 180;

//治疗速度
$heal_rate = array(
	'hp' => 0.5, //每秒生命回复速度
	'sp' => 1 //每秒体力回复速度
	);

//耐药性相关参数
$tolerance = array(
	'last' =>0.05, //持续时间参数（回复量的20分之一，单位是秒）
	'modulous' => 0.5 //回复效果削弱（回复量减半）
	);

//毒相关参数
$poison = array(
	'Hlast' => 1, //食品中毒的默认持续时间（治疗倍数）
	'Wlast' => 1, //被淬毒武器击中后的持续时间（攻击倍数）
	'Wlast_min' => 30, //被淬毒武器击中后的最小持续时间（秒）
	'Wturn' => 10, //武器淬毒的有效回合数
	'recover' => false, //中毒后是否还有自动生命回复
	'damage' => 1 //中毒后每秒受到的伤害
	);



//陷阱致伤率（各部位单独计算）
$trap_injure_rate = 25;

//NPC自动回血
$npc_recover = true;

//基础经验值等级区间
$base_exp = 9;

//基础物品寻找几率
$item_found_rate = 60;

//基础敌人遭遇几率
$enemy_found_rate = 70;

//基础尸体遭遇几率
$corpse_found_rate = 50;

//基础先发几率
$base_emptive = 50;

//射程 射程大者可以反击射程小者，此外射程为0则代表不能反击任何系但也不能被任何系反击
$base_range = array(
	'p' => 20,
	'k' => 20,
	'g' => 40,
	'c' => 30,
	'd' => 0,
	'sc' => 10
);

//命中率
$base_hit_rate = array(
	'p' => 75,
	'k' => 75,
	'g' => 70,
	'c' => 75,
	'd' => 60,
	'sc' => 75
	);

//命中率每点熟练增加
$extra_hit_rate = array(
	'p' => 0.07,
	'k' => 0.08,
	'g' => 0.04,
	'c' => 0.2,
	'd' => 0.01,
	'sc' => 0.01
	);

//反击率
$base_counter_rate = array(
	'p' => 80,
	'k' => 70,
	'g' => 80,
	'c' => 50,
	'd' => 20,
	'sc' => 100
	);

//各种攻击方式可能导致受伤的部位
$hurt_position = array(
	'p' => array('h', 'a'),
	'k' => array('b', 'h', 'a'),
	'g' => array('b', 'h', 'a', 'f'),
	'c'=> array('h', 'a'),
	'd' => array('b', 'h', 'a', 'f'),
	'sc' => array('b', 'h', 'a', 'f'),
	'fist' => array()
	);

//致伤率
$hurt_rate = array('p' => 15, 'k' => 30, 'g' => 30, 'c' => 15, 'd' => 40, 'sc' => 60, 'fist' => 0);


//头像数量
$icon_num = array(
	'f' => 5,
	'm' => 5
	);


//每点熟练度所增加的攻击系数
$proficiency_modulus = array(
	'p' => 0.5,
	'k' => 0.5,
	'g' => 0.6,
	'c' => 0.4,
	'd' => 0.5,
	'sc' => 0.2
	);

//每种熟练度的基础攻击系数
$proficiency_intercept = array(
	'p' => 0,
	'k' => 10,
	'g' => 20,
	'c' => 2,
	'd' => 6,
	'sc' => 100
	);

//可随机到的天气数量
$normal_weather = 9;
	
//商店位置
$shopmap = array(14, 19);
	
//诊所位置
$clinicmap = array(
	//MapID => Multiple
	19 => 2
	);

//各行动的消耗
$consumption = array(
	'search' => array('sp' => 15),
	'move' => array('sp' => 15),
	'create_team' => array('sp' => 25),
	'join_team' => array('sp' => 25),
	'wound_dressing' => array('sp' => 25)
	);

//默认弹夹容量
$clip = 12;

//武器损耗率（有限耐物品）
$attrit_rate = array(
	'WP' => 10,
	'WK' => 10,
	'default' => 100
	);

//武器损耗率（无限耐物品）
$mar_rate = array(
	'WP' => 20,
	'WK' => 20,
	'default' => 20
	);

//攻击系数
$modulus_attack = array(
	'weather' => array(1.5, 1.2, 1, 0.9, 0.8, 0.73, 0.75, 0.93, 1, 1.05, 1, 1.2, 1, 0.95),
	'area' => array(6 => 1.1, 9 => 0.9, 14 => 0.9, 18 => 1.1),
	'pose' => array(1, 1.2, 0.8, 0.95, 0.8, 0.7, 1),
	'tactic' => array(1, 0.8, 1.05, 0.9, 0.7)
	);

//防御系数
$modulus_defend = array(
	'weather' => array(1.1, 1.1, 1, 1.5, 0.8, 0.73, 0.8, 1, 0.8, 0.8, 1, 1.1, 0.5, 0.97),
	'area' => array(1 => 0.9, 2 => 1.1, 11 => 0.9, 12 => 1.1, 20 => 1.1),
	'pose' => array(1, 0.8, 1.2, 0.9, 0.95, 0.85, 1),
	'tactic' => array(1, 1.2, 0.9, 1.1, 0.95)
	);

//命中率系数
$modulus_hit_rate = array(
	'weather' => array(1.25, 1.3, 1, 0.8, 1.07, 0.7, 0.8, 0.97, 0.8, 0.8, 0.9, 1.1, 0.95, 0.85),
	'area' => array(),
	'pose' => array(),
	'tactic' => array()
	);

//遇敌率系数
$modulus_find = array(
	'weather' => array(1.1, 1.2, 1, 0.98, 0.97, 0.85, 0.94, 0.88, 0.9, 0.9, 1, 1.05, 0.95, 0.8),
	'area' => array(1.1, 1, 1, 1.1, 0.9, 1.1, 1, 1.1, 0.9, 1, 1.1, 1, 1, 0.9, 1, 0.9, 0.9, 0.9, 1, 1.1, 1, 1.1),
	'pose' => array(3 => 1.2, 4 => 0.9, 5 => 0.8, 6 => 1.2),
	'tactic' => array()
	);

//躲避系数
$modulus_hide = array(
	'weather' => array(),
	'area' => array(),
	'pose' => array(6 => 0.8),
	'tactic' => array(1 => 1.05, 3 => 1.2, 4 => 0.9)
	);

//先发系数
$modulus_emptive = array(
	'weather' => array(1.3, 1.1, 1, 0.95, 1, 1, 1, 1.05, 0.88, 0.85, 0.95, 1.1, 1.1, 1.2),
	'area' => array(),
	'pose' => array(2 => 0.9, 3 => 1.1, 4 => 1.2, 5 => 0.8, 6 => 0.7),
	'tactic' => array()
	);

//反击系数
$modulus_counter = array(
	'weather' => array(),
	'area' => array(),
	'pose' => array(6 => 1.1),
	'tactic' => array(2 => 1.2, 3 => 0)
	);