<?php

$map_size = array(6, 6);//从0开始

$regioninfo = array(
	array(
		'_id' => 0,
		'name' => '莱缪利亚',
		'type' => 'start',			//start 起点  normal 通常  end 终点
		'destination' => 1,		//下一个区域
		'access' => false,			//区域入口: 0或者正数为地图编号，负数为该等级随机
		'duration' => 0,
		'displaysize' => array(2,2),
		'background' => 'capital.jpg',
		'group' => array(
			'static' => array(
				'num' => -1,
//				'arealist' => array(0, 1, 2, 3),
				'mapinfo' => array(
					array('_id'=>0, 'n'=>'首都广场', 'c'=>'1-1'),
					array('_id'=>1, 'n'=>'城门', 'c'=>'1-0'),
					array('_id'=>2, 'n'=>'市场', 'c'=>'2-1'),
					array('_id'=>3, 'n'=>'住宅区', 'c'=>'0-0')
				)
			)
		)
	),
	array(
		'_id' => 1,
		'name' => '77号铁路',
		'type' => 'normal',
		'destination' => 2,
		'access' => 101,
		'duration' => 300,
		'displaysize' => array(4,4),
		'background' => 'railroad.jpg',
		'group' => array(
			'static' => array(
				'num' => -1,
//				'arealist' => array(101,102),
				'mapinfo' => array(
					array('_id' => 101, 'n' => '检查站', 'c' => '1-1',),
					array('_id' => 102, 'n' => '西森林', 'c' => '0-2',),
					array('_id' => 103, 'n' => '东森林', 'c' => '4-2',),
					array('_id' => 104, 'n' => '废弃列车', 'c' => '3-3',)
				)
			),
			'random' => array(
				'num' => 2,
//				'arealist' => array(151,152),
				'randomcoors' => array('0-1','2-1','3-1','4-1','1-2','2-2','0-3','1-3','2-3','4-3','1-4','2-4','3-4'),
				'mapinfo' => array(
					array('_id' => 151, 'n' => '燃烧的阵地',),
					array('_id' => 152, 'n' => '废弃的阵地',),
					array('_id' => 153, 'n' => '毁坏的战车',),
					array('_id' => 154, 'n' => '简易工事',)
				)
			)
		)
	),
	array(
		'_id' => 2,
		'name' => '夜城',
		'type' => 'normal',
		'destination' => 3,
		'access' => 201,
		'duration' => 300,
		'displaysize' => array(4,4),
		'background' => 'nightcity.jpg',
		'group' => array(
			'static' => array(
				'num' => -1,
//				'arealist' => array(201,202)
				'mapinfo' => array(
					array('_id' => 201, 'n' => '监视塔', 'c' => '2-1',),
					array('_id' => 202, 'n' => '核反应堆', 'c' => '3-2',),
					array('_id' => 203, 'n' => '仓库', 'c' => '2-1',),
					array('_id' => 204, 'n' => '生活北区', 'c' => '1-2',),
					array('_id' => 205, 'n' => '生活南区', 'c' => '2-4',)
				)
			),
			'random' => array(
				'num' => 1,
//				'arealist' => array(251,252),
				'randomcoors' => array('0-3','1-3','2-3','3-3','4-3','1-4','3-4'),
				'mapinfo' => array(
					array('_id' => 251, 'n' => '流动集市',),
					array('_id' => 252, 'n' => '机动哨站',),
					array('_id' => 253, 'n' => '简易工事',),
					array('_id' => 254, 'n' => '秘密通道',)
				)
			)
		)
	),
	array(
		'_id' => 3,
		'name' => '「微风号」',
		'type' => 'normal',
		'destination' => 4,
		'access' => 301,
		'duration' => 300,
		'displaysize' => array(5,5),
		'background' => 'ship.jpg',
		'group' => array(
			'static' => array(
				'num' => -1,
//				'arealist' => array(301,302)
				'mapinfo' => array(
					array('_id' => 301, 'n' => '舰桥', 'c' => '2-2',),
					array('_id' => 302, 'n' => '时流引擎', 'c' => '1-2',),
					array('_id' => 303, 'n' => '传送装置', 'c' => '1-3',),
					array('_id' => 304, 'n' => '作战室', 'c' => '4-2',),
					array('_id' => 305, 'n' => '储藏室A', 'c' => '2-1',),
					array('_id' => 306, 'n' => '储藏室B', 'c' => '2-4',)
				)
			),
			'random' => array(
				'num' => 2,
//				'arealist' => array(351,352),
				'randomcoors' => array('0-0','1-0','2-0','3-0','4-0','5-0','0-5','1-5','2-5','3-5','4-5','5-5'),
				'mapinfo' => array(
					array('_id' => 351, 'n' => '绯翼骑士投送艇',),
					array('_id' => 352, 'n' => '绯翼骑士指挥艇',),
					array('_id' => 353, 'n' => '绯翼骑士支援艇',),
					array('_id' => 354, 'n' => '绯翼骑士登陆艇',)
				)
			)
		)
	),
	array(
		'_id' => 4,
		'name' => '流金岛',
		'type' => 'normal',
		'destination' => 5,
		'access' => -1,
		'duration' => 300,
		'displaysize' => array(6,6),
		'background' => 'hj.jpg',
		'group' => array(
			'static' => array(
				'num' => -1,
//				'arealist' => array(401,402)
				'mapinfo' => array(
					array('_id' => 401, 'n' => '指挥中心', 'c' => '4-2',),
					array('_id' => 402, 'n' => '北废弃村舍', 'c' => '2-1',),
					array('_id' => 403, 'n' => '南废弃村舍', 'c' => '4-5',),
					array('_id' => 404, 'n' => '灯塔', 'c' => '5-6',),
					array('_id' => 405, 'n' => '隧道', 'c' => '2-4',),
					array('_id' => 406, 'n' => '废弃校舍', 'c' => '1-3',),
					array('_id' => 407, 'n' => '清水池', 'c' => '2-2',),
					array('_id' => 408, 'n' => '诊所', 'c' => '5-4',)
				)
			),
			'random' => array(
				'num' => 2,
//				'arealist' => array(451,452),
				'randomcoors' => array('3-3','3-4','3-5','4-3','4-4'),
				'mapinfo' => array(
					array('_id' => 451, 'n' => '森林',),
					array('_id' => 452, 'n' => '沼泽',),
					array('_id' => 453, 'n' => '山丘',),
					array('_id' => 454, 'n' => '神社',)
				)
			)
		)
	),
	array(
		'_id' => 5,
		'name' => '飞行要塞「虹铸」',
		'type' => 'normal',
		'destination' => 6,
		'access' => 501,
		'duration' => 300,
		'displaysize' => array(4,4),
		'background' => 'airfortress.jpg',
		'group' => array(
			'static' => array(
				'num' => -1,
//				'arealist' => array(501,502)
				'mapinfo' => array(
					array('_id' => 501, 'n' => '要塞监控室', 'c' => '1-1',),
					array('_id' => 502, 'n' => '动力室', 'c' => '1-1',),
					array('_id' => 503, 'n' => '「蜡翼天马」设备', 'c' => '1-2',),
					array('_id' => 504, 'n' => '武器控制室', 'c' => '3-1',),
					array('_id' => 505, 'n' => '飞行控制室', 'c' => '1-3',),
					array('_id' => 506, 'n' => '彩虹原液车间', 'c' => '2-1',)
				)
			),
			'random' => array(
				'num' => 2,
//				'arealist' => array(551,552),
				'randomcoors' => array('0-1','0-2','4-2','4-3','2-3','2-4','3-3'),
				'mapinfo' => array(
					array('_id' => 551, 'n' => '武器储藏室',),
					array('_id' => 552, 'n' => '装甲储藏室',),
					array('_id' => 553, 'n' => '绯翼骑兵登陆舱',),
					array('_id' => 554, 'n' => '绯翼骑兵逃生舱',)
				)
			)
		)
	),
	array(
		'_id' => 6,
		'name' => '驿葵莎边境',
		'type' => 'end',
		'destination' => false,
		'access' => 601,
		'duration' => 300,
		'displaysize' => array(4,4),
		'background' => 'final.jpg',
		'group' => array(
			'static' => array(
				'num' => -1,
//				'arealist' => array(901,902)
				'mapinfo' => array(
					array('_id' => 601, 'n' => '飞行要塞「虹铸」', 'c' => '2-0',),
					array('_id' => 602, 'n' => '驿葵莎之门', 'c' => '2-2',),
					array('_id' => 603, 'n' => '莫洛克传送艇', 'c' => '2-4',),
					array('_id' => 604, 'n' => '西北角', 'c' => '1-1',),
					array('_id' => 605, 'n' => '东北角', 'c' => '3-1',),
					array('_id' => 606, 'n' => '正东角', 'c' => '4-2',),
					array('_id' => 607, 'n' => '东南角', 'c' => '3-3',),
					array('_id' => 608, 'n' => '西南角', 'c' => '1-3',),
					array('_id' => 609, 'n' => '正西角', 'c' => '0-2',)
				)
			)
		)
	)
);