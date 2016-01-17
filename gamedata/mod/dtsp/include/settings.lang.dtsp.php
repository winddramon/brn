<?php

//网页标题
$page_title = '大逃杀原型';

//网页标头
$page_header = 'Battle Royale Prototype';

//币种
$currency = '元';

//武器类型的攻击方式（显示用）
$weapon_types = array(
	'p' => '殴',
	'k' => '斩',
	'g' => '射',
	'c' => '投',
	'd' => '爆',
	'sc' => '符卡',
	);
	
//数值名（仅用于显示）
$healthinfo = array(
	'hp' => '生命',
	'sp' => '体力'
	);
	
//敌方生命状态（仅用于显示）
$hp_status = array(
	'normal' => '通常',
	'attention' => '注意',
	'dangerous' => '危险',
	'dead' => '死亡'
	);

//buff名字
$buff_name = array(
	'last_stand' => '曙光将至',
	'region_jump' => '亡命天涯',

	'poison' => '中毒',
	'injured_body' => '胸部受伤',
	'injured_head' => '头部受伤',
	'injured_arm' => '腕部受伤',
	'injured_foot' => '足部受伤',
	'tolerance' => '药物耐受',
	
	'extra_package' => '背包扩容',
	'extra_hp' => '额外生命',
	'att_buff' => '攻击增益',
	'def_buff' => '防御增益',
	'att_debuff' => '攻击削减',
	'def_debuff' => '防御削减',
	'recover_hp' => '生命回复',
	'recover_sp' => '体力回复',
	'invincible' => '无敌',
	'shield' => '替身人偶',
	'infrared_moon' => '赤月下',
	'ultrashort_EEG' => '超短脳波',
	'scapegoat_dummy' => '身代わり人形',
	'control_rod' => '制御棒',
	'grand_patriots_elixir' => '国士无双',
	'fantasy_nature' => '梦想天生',
	'ridicule' => '自信受挫',
	'wandering_soul' => '彷徨幽灵',
	'ageless_dream' => '无寿の夢',
	'ageless_land' => '无寿国への約束手形',
	'horai' => '蓬莱之药',
	'lunar_incense' => '仙香玉兔',
	
	//套装
	'kedama_suit' => '毛玉套装',
	'reisen_suit' => '铃仙套装',
	'eirin_suit' => '八意套装',
	'yukari_suit' => '八云套装',
	'scarlet_suit' => '斯卡雷特套装',
	'konpaku_suit' => '魂魄套装',
	'cirno_suit' => '琪露诺套装',
	'reimu_suit' => '博丽套装',
	'marisa_suit' => '雾雨套装',
	'rin_suit' => '冴月套装',
	'aya_suit' => '射命丸套装',
	'sakuya_suit' => '十六夜套装',
	'rinnosuke_suit' => '森近套装',
	'keine_suit' => '上白泽套装',
	'yuyuko_suit' => '幽幽子套装',
	'komeiji_suit' => '古名地套装',
	'alice_suit' => '玛格特罗依德套装'
	);
	
//buff说明
$buff_help = array(
	'last_stand' => '倒计时结束时将获得游戏胜利',
	'region_jump' => '倒计时结束时自动跳转到下一个区域',

	'poison' => '生命持续流失',
	'injured_body' => '防御力下降，治疗速度减半，再次受伤会撕裂伤口',
	'injured_head' => '准确率下降，再次受伤会撕裂伤口',
	'injured_arm' => '攻击力下降，探索体力消耗增加，再次受伤会撕裂伤口',
	'injured_foot' => '移动体力消耗增加，再次受伤会撕裂伤口',
	'tolerance' => '使用回复品的效果减半',
	
	'shield' => '代替自身受到伤害',
	'infrared_moon' => '无法被远程攻击及 SpellCard 击中',
	'ultrashort_EEG' => '出现多个分身与本体共同攻击',
	'scapegoat_dummy' => '永久降低攻击并提升防御',
	'control_rod' => '永久提升攻击并降低防御',
	'grand_patriots_elixir' => '永久提升攻击并提升防御',
	'fantasy_nature' => '击中敌人一定次数后会释放「夢想天生」',
	'ridicule' => '暂时降低防御',
	'wandering_soul' => '暂时提升防御',
	'ageless_dream' => '生命持续流失，击中敌人后效果会消失',
	'ageless_land' => '击中敌人后会消失，若到时限仍未击中敌人则会造成大量伤害',
	'horai' => '死后原地满状态复活',
	'lunar_incense' => '时限到之后直接死亡',
	);

//属性名字及说明
$sk_define = array(
	'immortal' => array( 'name' => '永续', 'help' => '允许耐久为0', 'disp' => false),
	'def-buff' => array( 'name' => '防御提升', 'help' => '额外提升防御力', 'disp' => true),
	'shield' => array( 'name' => '盾牌', 'help' => '有机会完全格挡敌人的攻击', 'disp' => true),
	'anti-P' => array('name' => '防殴', 'help' => '减少殴系武器对自己的伤害', 'disp' => true),
	'anti-K' => array('name' => '防斩', 'help' => '减少斩系武器对自己的伤害', 'disp' => true),
	'anti-G' => array('name' => '防弹', 'help' => '减少射击武器对自己的伤害', 'disp' => true),
	'anti-C' => array('name' => '防投', 'help' => '减少投掷武器对自己的伤害', 'disp' => true),
	'anti-D' => array('name' => '防爆', 'help' => '减少爆炸武器对自己的伤害', 'disp' => true),
	'anti-F' => array('name' => '防符', 'help' => '减少符卡武器对自己的伤害', 'disp' => true),
	'suit-joker' => array('name' => '套装欺诈', 'help' => 'TODO', 'disp' => false),
	'ammo' => array( 'name' => '弹匣', 'help' => 'TODO', 'disp' => false),
	'ammo' => array( 'name' => '消音', 'help' => '攻击时不会发出声响', 'disp' => true),
	'alt' => array( 'name' => '形态切换', 'help' => '可以切换武器形态', 'disp' => true),
	'multiple' => array( 'name' => '多重攻击', 'help' => '一次战斗可以作出多次攻击', 'disp' => true),
	'multistage' => array( 'name' => '多段伤害', 'help' => '一次攻击可以造成多次伤害', 'disp' => true),
	'pugilism' => array( 'name' => '拳术', 'help' => 'TODO', 'disp' => false),
	'poison' => array( 'name' => '带毒', 'help' => '攻击附带毒属性', 'disp' => true),
	'atk-explode' => array('name' => '冲击', 'help' => '无视敌方一定比例的防御力', 'disp' => true),
	'atk-pierce' => array('name' => '贯穿', 'help' => '有机会无视对方的防御属性', 'disp' => true),
	'atk-fire' => array('name' => '火焰攻击', 'help' => '攻击附带火焰属性，可能造成敌人烧伤，但对有防护的敌人效果差', 'disp' => true),//TODO
	'atk-shock' => array('name' => '电击', 'help' => '攻击附带电击属性，可能造成敌人麻痹，但对有防护的敌人效果差', 'disp' => true),//TODO
	'atk-freeze' => array('name' => '冰冻攻击', 'help' => '攻击附带冰冻属性，可能造成敌人冻结，但对有防护的敌人效果差', 'disp' => true),//TODO
	'def-intercept' => array('name' => '拦截', 'help' => '有机会使敌人不带攻击属性的远程攻击无效', 'disp' => true),//TODO
	'def-anti-multiple' => array('name' => '自动防御', 'help' => '敌方多重攻击除第1次攻击以外，每一次攻击的伤害大幅减少', 'disp' => true),//TODO
	'def-anti-multistage' => array('name' => '自动防御', 'help' => '敌方多重攻击除第1次攻击以外，每一次攻击的伤害大幅减少', 'disp' => true),//TODO
	);

//社团名
$clubinfo = Array(
	0 => '无',
	1 => '棒球社',
	2 => '击剑社',
	3 => '弓道社',
	4 => '篮球社',
	5 => '化学社',
	6 => '足球社',
	7 => '电脑社',
	8 => '生物社',
	9 => '动漫社',
	10 => '侦探社'
	);

//性别
$genderinfo = array(
	'f' => '女',
	'm' => '男'
	);

//地图
$map = array(
	'迷途之家',				//0
	'血色海岸',				//1
	'红魔馆钟楼',			//2
	'红魔馆',					//3
	'妖怪之山',				//4
	'人间之里',				//5
	'观音堂',					//6		Old
	'雾之湖',					//7
	'博丽神社',				//8
	'地灵殿',					//9
	'魔法森林',				//10
	'隧道',						//11	Old
	'玛格特罗依德宅',	//12
	'西行妖',					//13
	'香霖堂',					//14
	'白玉楼',					//15
	'迷途竹林',				//16
	'源二郎池',				//17	Old
	'南村住宅区',			//18	Old
	'永远亭',					//19
	'灯塔',						//20	Old
	'南海岸'					//21	Old
	);

//天气名字
$weatherinfo = array(
	0 => '蒼天',
	1 => '快晴',
	2 => '曇天',
	3 => '晴嵐',
	4 => '天気雨',
	5 => '台風',
	6 => '疎雨',
	7 => '雪',
	8 => '川霧',
	9 => '<span class="noumu">濃霧</span>',
	10 => '<span class="hanagumori">花曇</span>',
	11 => '<span class="nagi">凪</span>',
	12 => '<span class="diamond-dust">钻石星辰</span>',
	13 => '<span class="scarlet-moon">血月</span>'
	);

//物品类型（仅用于显示）
$iteminfo = array(
	'WP' => '钝器',
	'WG' => '枪械',
	'WK' => '锐器',
	'WC' => '投掷武器',
	'WD' => '爆炸物',
	'W' => '未知武器',//各大类的名称放在最后
	'DB' => '身体防具',
	'DH' => '头部防具',
	'DA' => '手臂防具',
	'DF' => '足部防具',
	'D' => '未知防具',
	'A'  => '饰品',
	'HH' => '生命恢复',
	'HS' => '体力恢复',
	'HB' => '命体恢复',
	'PH' => '生命恢复',
	'PS' => '体力恢复',
	'PB' => '命体恢复',
	'R' => '雷达',
	'TO' => '已设陷阱',
	'TN' => '陷阱',
	'Y' => '特殊',
	'GB' => '弹药',
	'YS' => '召唤',
	'SW' => 'SpellCard(进攻)',
	'SY' => 'SpellCard(特效)',
	'M' => '合成素材',
	'XT' => '位置传送',
	'default' => '物品'
	);
//行动名（仅用于显示）
$actioninfo = array(
	'move' => '移动',
	'search' => '搜索',
	'create_team' => '创建队伍',
	'join_team' => '加入队伍'
	);
	
//基础姿态名
$poseinfo = Array(
	'通常',
	'攻击',
	'防守',
	'探索',
	'隐藏',
	'治疗',
	'狙击'
	);
	
//应战方针名（仅用于显示）
$tacticinfo = Array(
	'通常',
	'重视防御',
	'重视反击',
	'重视躲避'
	);

//阵营
$sidetype = array(
	0 => '无阵营',
	1 => '帝国',
	2 => '朝圣者',
	3 => '驿葵莎',
	4 => '夜族',
	5 => '时空跳跃'
);

//种族
$speciestype = array(
	0 => '未指定',
	1 => '守护精灵',
	11 => '陆马',
	12 => '天马',
	13 => '独角兽',
	14 => '斑马',
	15 => '夜骐',
	21 => '蜡翼天马',
	22 => '蜡翼角兽',
	31 => '莫洛克',
	41 => '狮鹫'
);

//NPC类型头衔
$npctype = array(
	0 => '逃难这',
	1 => '帝国代理将军',
	2 => '朝圣者的狂战士',
	3 => '朝圣者的药剂师'

);
//致死原因（仅用于显示）
$deathreasoninfo = array(
	'forbid' => '禁区停留',
	'suicide' => '自杀身亡',
	'ageless_land' => 'Ageless Land',
	'weapon_p' => '殴打致死',
	'weapon_k' => '利器击杀',
	'weapon_g' => '远程射杀',
	'weapon_c' => '致命投掷',
	'weapon_d' => '剧烈爆炸',
	'poison' => '中毒身亡',
	'injure' => '旧伤复发',
	'trap' => '触发陷阱',
	'default' => '神秘死亡'
	);

//结局
$ending_type = array(
	'error' => '<span class="error">游戏故障</span>',
	'noplayer' => '<span class="error">无人参加</span>',
	'timeup' => '<span class="dieout">全部死亡</span>',
	'survive' => '<span class="survive">最后幸存</span>',
	'eliminate' => '<span class="eliminate">游戏紧急结束</span>',
	'laststand' => '<span class="survive">逃出生天</span>',
	'restart' => '<span class="restart">游戏重设</span>'
	);