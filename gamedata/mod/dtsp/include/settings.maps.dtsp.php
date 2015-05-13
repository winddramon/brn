<?php

$map_size = array(4, 4);//从0开始

$regioninfo = array(
	array(
		'_id' => 0,
		'name' => '勒缪利亚首都',
		'type' => 'start',			//start 起点  normal 通常  end 终点
		'destination' => 1,		//下一个区域
		'access' => false,			//区域入口: 0或者正数为地图编号，负数为该等级随机
		'duration' => 0,
		'displaysize' => array(2,2),
		'background' => 'region1.png',
		'group' => array(
			'static' => array(
				'num' => -1,
				'arealist' => array(0, 1)
			)
		)
	),
	array(
		'_id' => 1,
		'name' => '大逃杀岛',
		'type' => 'normal',
		'destination' => 2,
		'access' => 101,
		'duration' => 300,
		'displaysize' => array(4,4),
		'background' => 'region2.png',
		'group' => array(
			'static' => array(
				'num' => -1,
				'arealist' => array(101,102)
			),
			'random' => array(
				'num' => 1,
				'arealist' => array(151,152),
				'randomcoors' => array('1-1','1-2','2-1','2-2')
			)
		)
	),
	array(
		'_id' => 2,
		'name' => '飞行要塞「虹铸」',
		'type' => 'normal',
		'destination' => 3,
		'access' => -1,
		'duration' => 300,
		'displaysize' => array(4,4),
		'background' => 'region3.png',
		'group' => array(
			'static' => array(
				'num' => -1,
				'arealist' => array(201,202)
			),
			'random' => array(
				'num' => 1,
				'arealist' => array(251,252),
				'randomcoors' => array('3-3','3-4','4-3','4-4')
			)
		)
	),
	array(
		'_id' => 3,
		'name' => '驿葵莎边境',
		'type' => 'end',
		'destination' => false,
		'access' => 902,
		'duration' => 300,
		'displaysize' => array(2,2),
		'background' => 'region4.png',
		'group' => array(
			'static' => array(
				'num' => -1,
				'arealist' => array(901,902)
			)
		)
	)
);
$mapinfo = array(
	array(
		'_id' => 0,
		'n' => '阿玛蒂娅邸',
		'c' => '1-1',
	),
	array(
		'_id' => 1,
		'n' => '城门',
		'c' => '2-0',
	),
	array(
		'_id' => 101,
		'n' => '废弃旅馆',
		'c' => '2-2',
	),
	array(
		'_id' => 102,
		'n' => '森林',
		'c' => '0-3',
	),
	array(
		'_id' => 151,
		'n' => '村落',
	),
	array(
		'_id' => 152,
		'n' => '废弃营地',
	),
	array(
		'_id' => 201,
		'n' => '动力室',
		'c' => '2-2',
	),
	array(
		'_id' => 202,
		'n' => '「蜡翼天马」设备',
		'c' => '0-1',
	),
	array(
		'_id' => 251,
		'n' => '武器库',
	),
	array(
		'_id' => 252,
		'n' => '对空炮台',
	),
	array(
		'_id' => 901,
		'n' => '驿葵莎之门',
		'c' => '2-2',
	),
	array(
		'_id' => 902,
		'n' => '「虹铸」',
		'c' => '0-0',
	)

);