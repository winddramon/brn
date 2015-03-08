<?php
//物品合成列表
$mixinfo = array
(
	array(
		'stuff' => array('手机','笔记本电脑'),
		'result' => array('移动PC','Y',1,1,array('immortal'=>true))),
	array(
		'stuff' => array('杂炊','松茸'),
		'result' => array('松茸御饭','HS',200,3,)),
	array(
		'stuff' => array('咖喱','面包'),
		'result' => array('咖喱面包','HB',400,1,)),
	array(
		'stuff' => array('牛奶','立顿茶包','糯米丸子'),
		'result' => array('珍珠奶茶','HB',500,4,)),
	array(
		'stuff' => array('酒精','水'),
		'result' => array('伏特加','HS',700,1,array('side-effect' => array('att' => 50, 'def' => -25, 'duration' => 180)))),
	array(
		'stuff' => array('肥料','金坷垃'),
		'result' => array('「とある科學の超肥料砲」','SW',221,10,)),
	array(
		'stuff' => array('魔理沙·迷你八卦炉','霖之助·迷你八卦炉强化图'),
		'result' => array('霖之助·迷你八卦炉×强袭','WD',200,20,array('suit' => 'rinnosuke'))),
	array(
		'stuff' => array('绯色金属','陶土','霖之助·迷你八卦炉设计图','The Grimoire of Marisa'),
		'result' => array('魔理沙·迷你八卦炉×永续','WD',50,0,array('suit' => 'marisa', 'immortal' => true))),
	array(
		'stuff' => array('绯色金属','陶土','霖之助·迷你八卦炉设计图'),
		'result' => array('魔理沙·迷你八卦炉','WD',50,20,array('suit' => 'marisa'))),
	array(
		'stuff' => array('咲夜·红魔银刃','鲜血'),
		'result' => array('「红魔血刃」','WC',85,10,array('alt' => array('k' => 'k', 'e' => '50')))),
	array(
		'stuff' => array('魂魄·楼观剑','魂魄·白楼剑'),
		'result' => array('魂魄对剑「白楼观」','WK',125,65,array('multistage' => array(0.6, 0.7), 'suit' => 'konpaku', 'single-buff' => true))),
	array(
		'stuff' => array('笔记本电脑','Linux Live CD'),
		'result' => array('码符「Matrix的苏醒」','SW',65,18,)),
	array(
		'stuff' => array('移动PC','Linux Live CD'),
		'result' => array('码符「Matrix的复生」','SW',125,15,)),
	array(
		'stuff' => array('大冰块','轻油'),
		'result' => array('火焰轻油冰块','WD',20,5,)),
	array(
		'stuff' => array('大冰块','汽油'),
		'result' => array('火焰汽油冰块','WD',50,2,)),
	array(
		'stuff' => array('大冰块','水'),
		'result' => array('纯净水冰块','HS',250,2,)),
	array(
		'stuff' => array('冰精的微型冰块','水'),
		'result' => array('矿泉水冰块','HH',275,2,)),
	array(
		'stuff' => array('大冰块','冰精的微型冰块','水'),
		'result' => array('有顶天之酒冰块','HB',300,2,)),
	array(
		'stuff' => array('大冰块','冰刃'),
		'result' => array('易碎冰块','WD',40,15,)),
	array(
		'stuff' => array('八云紫结界原理图纸','博丽大结界作用说明书'),
		'result' => array('结界干扰器','Y',1,1,array('immortal'=>true))),
	array(
		'stuff' => array('触手证明书','触手','魔法催化剂','盐','蘑菇'),
		'result' => array('码符「终极BUG·拉电闸」','SW',1000,1,)),
	array(
		'stuff' => array('水','空白的SpellCard'),
		'result' => array('「スペル増幅」','SY',100,1,)),
	array(
		'stuff' => array('矿泉水','空白的SpellCard'),
		'result' => array('伊吹瓢','SY',1000,1,)),
	array(
		'stuff' => array('面包','空白的SpellCard'),
		'result' => array('「体力回復」','SY',100,1,)),
	array(
		'stuff' => array('咖喱面包','空白的SpellCard'),
		'result' => array('病気平癒守','SY',1000,1,)),
	array(
		'stuff' => array('红色水笔','空白的SpellCard'),
		'result' => array('红符「不夜城レッド」','SW',80,5,)),
	array(
		'stuff' => array('红色水笔','红色水笔','空白的SpellCard'),
		'result' => array('長視「赤月下」','SY',120,1,)),
	array(
		'stuff' => array('红色水笔','蓝色水笔','空白的SpellCard'),
		'result' => array('短視「超短脳波」','SY',120,1,)),
	array(
		'stuff' => array('红色水笔','红色水笔','红色水笔','红色水笔','空白的SpellCard'),
		'result' => array('日符「ロイヤルフレア」','SY',1,1,)),
	array(
		'stuff' => array('蓝色水笔','蓝色水笔','蓝色水笔','空白的SpellCard'),
		'result' => array('月符「サイレントセレナ」','SY',1,1,)),
	array(
		'stuff' => array('绿色水笔','竹子','年糕','空白的SpellCard'),
		'result' => array('生薬「国士無双の薬」','SY',30,1,)),
	array(
		'stuff' => array('黄色水笔','怪力药丸','空白的SpellCard'),
		'result' => array('制御棒','SY',15,1,)),
	array(
		'stuff' => array('蓝色水笔','忍耐药丸','空白的SpellCard'),
		'result' => array('身代わり人形','SY',15,1,)),
	array(
		'stuff' => array('橙色水笔','空白的SpellCard'),
		'result' => array('龍星','SY',60,1,)),
	array(
		'stuff' => array('黑色水笔','空白的SpellCard'),
		'result' => array('足軽「スーサイドスクワッド」','SY',100,2,)),
	array(
		'stuff' => array('绿色水笔','空白的SpellCard'),
		'result' => array('断命剑「冥想斩」','SW',125,1,)),
	array(
		'stuff' => array('蓝色水笔','空白的SpellCard'),
		'result' => array('人符「现世斩」','SW',100,1,)),
	array(
		'stuff' => array('蓝色水笔','水','空白的SpellCard'),
		'result' => array('氷符「アイシクルフォール」','SW',300,1,array('range-missile' => true))),
	array(
		'stuff' => array('蓝色水笔','冰晶核','空白的SpellCard'),
		'result' => array('雪符「ダイアモンドブリザード」','SW',400,1,)),
	array(
		'stuff' => array('绿色水笔','毒药','空白的SpellCard'),
		'result' => array('毒煙幕「瓦斯織物の玉」','SY',300,1,)),
	array(
		'stuff' => array('红色水笔','橙色水笔','空白的SpellCard'),
		'result' => array('伤魂「ソウルスカルプチュア」','SW',175,2,)),
	array(
		'stuff' => array('红色水笔','黄色水笔','空白的SpellCard'),
		'result' => array('禁忌「恋の迷宫」','SW',175,3,)),
	array(
		'stuff' => array('红色水笔','黑色水笔','空白的SpellCard'),
		'result' => array('幻爆「近眼花火」','SW',100,6,)),
	array(
		'stuff' => array('红色水笔','蓝色水笔','空白的SpellCard'),
		'result' => array('霊符「夢想封印」','SW',180,4,)),
	array(
		'stuff' => array('红色水笔','蓝色水笔','空白的SpellCard'),
		'result' => array('霊符「夢想封印」','SW',180,4,)),
	array(
		'stuff' => array('霊符「夢想封印」','塞钱'),
		'result' => array('神霊「夢想封印瞬」','SW',450,1,)),
	array(
		'stuff' => array('红色水笔','绿色水笔','空白的SpellCard'),
		'result' => array('花符「幻想郷の開花」','SW',200,3,)),
	array(
		'stuff' => array('绿色水笔','橙色水笔','空白的SpellCard'),
		'result' => array('运命「ミゼラブルフェイト」','SW',150,8,)),
	array(
		'stuff' => array('绿色水笔','黄色水笔','空白的SpellCard'),
		'result' => array('境符「二次元と三次元の境界」','TN',188,2,)),
	array(
		'stuff' => array('绿色水笔','黑色水笔','空白的SpellCard'),
		'result' => array('结界「魅力的な四重结界」','TN',288,1,)),
	array(
		'stuff' => array('绿色水笔','白色水笔','空白的SpellCard'),
		'result' => array('「反魂蝶 八分咲」','SW',140,12,)),
	array(
		'stuff' => array('橙色水笔','黑色水笔','空白的SpellCard'),
		'result' => array('風符「風神一扇」','SW',150,3,)),
	array(
		'stuff' => array('風符「風神一扇」','天狗之羽'),
		'result' => array('疾風「風神少女」','SW',450,1,array('multistage' => array(0.1,0.35,0.1,0.35,0.1)))),
	array(
		'stuff' => array('橙色水笔','黄色水笔','空白的SpellCard'),
		'result' => array('御札「神社繁榮祈願札」','TN',100,1,array('steal' => 0.25))),
	array(
		'stuff' => array('橙色水笔','白色水笔','空白的SpellCard'),
		'result' => array('禁弾「过去を刻む时计」','SW',185,6,)),
	array(
		'stuff' => array('橙色水笔','蓝色水笔','空白的SpellCard'),
		'result' => array('月金符「サンシャインリフレクター」','SW',100,3,)),
	array(
		'stuff' => array('黄色水笔','黑色水笔','空白的SpellCard'),
		'result' => array('三華「崩山彩極砲」','SW',130,2,)),
	array(
		'stuff' => array('黄色水笔','蓝色水笔','空白的SpellCard'),
		'result' => array('土着神「ケロちゃん风雨に负け ず」','SW',320,1,)),
	array(
		'stuff' => array('黄色水笔','白色水笔','空白的SpellCard'),
		'result' => array('恋心「ダブルスパーク」','SW',255,2,)),
	array(
		'stuff' => array('黑色水笔','黑色水笔','空白的SpellCard'),
		'result' => array('難題「仏の御石の鉢　-砕けぬ意思-」','SW',70,5,)),
	array(
		'stuff' => array('蓝色水笔','白色水笔','空白的SpellCard'),
		'result' => array('大奇迹「八坂の神风」','SW',120,12,)),
	array(
		'stuff' => array('蓝色水笔','蓝色水笔','空白的SpellCard'),
		'result' => array(' 恋符「マスタースパーク」','SW',220,2,)),
	array(
		'stuff' => array('恋符「マスタースパーク」','恋心「ダブルスパーク」'),
		'result' => array(' 恋符「マスタースパーク」·改','SW',350,2,array('multiple' => array(0.5, 0.5)))),
	array(
		'stuff' => array('霊符「夢想妙珠」','神灵「梦想封印」','塞钱'),
		'result' => array('「夢想天生」','SY',1200,1,)),
	array(
		'stuff' => array('魔符「アーティフルサクリファス」','上海人形'),
		'result' => array('魔操「归于虚无」','SW',500,1,)),
	array(
		'stuff' => array('生薬「国士無双の薬」','蓬莱玉枝'),
		'result' => array('禁薬「蓬莱の薬」','SY',1,1,)),
	array(
		'stuff' => array('优昙钵华','蓬莱玉枝','空白的SpellCard'),
		'result' => array('秘薬「仙香玉兎」','SW',750,1,array('lunar-incense' => true))),
	array(
		'stuff' => array('花符「幻想郷の开花」','阳伞'),
		'result' => array('幻想「花鳥風月、嘯風弄月」','SW',800,2,)),
	array(
		'stuff' => array('伤魂「ソウルスカルプチュア」','月时计'),
		'result' => array('幻葬「夜雾の幻影杀人鬼」','SW',225,5,array('multistage' => array(0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1)))),
	array(
		'stuff' => array('红符「不夜城レッド」','运命「ミゼラブルフェイト」'),
		'result' => array('红魔「スカーレットデビル」','SW',250,3,)),
	array(
		'stuff' => array('禁忌「恋の迷宫」','禁弾「过去を刻む时计」'),
		'result' => array('QED「495年の波纹」','SW',350,3,)),
	array(
		'stuff' => array('华符「彩光莲华掌」','三华「崩山彩极炮」'),
		'result' => array('星气「星脉地转弹」','SW',320,2,)),
	array(
		'stuff' => array('境符「二次元与三次元的境界」','结界「魅力的な四重结界」'),
		'result' => array('「深弾幕結界　-夢幻泡影-」','TN',400,1,array('multistage' => array(0.2,0.3,0.5)))),
	array(
		'stuff' => array('断命剑「冥想斩」','人符「现世斩」'),
		'result' => array('断迷剣「迷津慈航斬」','SW',400,1,array('multistage' => array(0.25, 0.25, 0.5)))),
	array(
		'stuff' => array('断命剑「冥想斩」','人符「现世斩」','半灵碎片'),
		'result' => array('人鬼「未来永劫斩」','SW',400,1,array('multistage' => array(0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 0.1, 1)))),
	array(
		'stuff' => array('土着神「ケロちゃん风雨に负けず」','大奇迹「八坂の神风」'),
		'result' => array('「风神様の神徳」','WD',555,1,)),
	array(
		'stuff' => array('「反魂蝶 八分咲」','幽灵印花折扇'),
		'result' => array('「西行寺无余涅盘」','SW',480,1,)),
	array(
		'stuff' => array('黑色水笔','白色水笔','空白的SpellCard'),
		'result' => array('无敌连段「AAAA-ESC」','SW',400,1,)),
	array(
		'stuff' => array('盐','大冰块'),
		'result' => array('盐水','HB',50,6,)),
	array(
		'stuff' => array('黑历史全系列光盘A','黑历史全系列光盘B','黑历史全系列光盘C'),
		'result' => array('黑历史史册残页','YS',1,1,array('id' => 109))),
	array(
		'stuff' => array('云南白药','邦迪创可贴'),
		'result' => array('云南白药创可贴','HH',400,1,)),
	array(
		'stuff' => array('巫女服','裹胸布'),
		'result' => array('仿制的灵梦巫女服','DB',35,60,)),
	array(
		'stuff' => array('巫女服','裹胸布','塞钱'),
		'result' => array('定制的灵梦巫女服','DB',120,60,)),
	array(
		'stuff' => array('国符「三种の神器 剣」','国符「三种の神器 玉」','国符「三种の神器 鏡」','国体「三种の神器 郷」'),
		'result' => array('终符「幻想天皇」','SW',2000,1,)),
	);
?>