<?php

$map_size = array(4, 4);//从0开始

$regioninfo = array(
	array(
		'_id' => 0,
		'type' => 'start',			//start 起点  normal 通常  end 终点
		'destination' => 1,		//下一个区域
		'access' => false,			//区域入口: 0或者正数为地图编号，负数为该等级随机
		'duration' => 0,
		'displaysize' => array(3,3),
		'background' => 'region1.png',
		'group' => array(
			'static' => array(
				'num' => -1,
				'list' => array(0)
			)
		)
	),
	array(
		'_id' => 1,
		'type' => 'normal',
		'destination' => 2,
		'access' => 1,
		'duration' => 300,
		'displaysize' => array(1,1),
		'background' => 'region2.png',
		'group' => array(
			'static' => array(
				'num' => -1,
				'list' => array(1)
			),
			'random' => array(
				'num' => 1,
				'list' => array(11,12)
			)
		)
	),
	array(
		'_id' => 2,
		'type' => 'normal',
		'destination' => 3,
		'access' => -1,
		'duration' => 300,
		'displaysize' => array(1,1),
		'background' => 'region2.png',
		'group' => array(
			'static' => array(
				'num' => -1,
				'list' => array(2)
			),
			'random' => array(
				'num' => 1,
				'list' => array(13,14)
			)
		)
	),
	array(
		'_id' => 3,
		'type' => 'end',
		'destination' => false,
		'access' => 3,
		'duration' => 300,
		'displaysize' => array(1,1),
		'background' => 'region2.png',
		'group' => array(
			'static' => array(
				'num' => -1,
				'list' => array(3)
			)
		)
	)
);
$mapinfo = array(
	array(
		'_id' => 0,
		'n' => '传送装置',
		'c' => '2-2',
	),
	array(
		'_id' => 1,
		'n' => '固定地图1',
		'c' => '1-3',
	),
	array(
		'_id' => 2,
		'n' => '固定地图2',
		'c' => '0-4',
	),
	array(
		'_id' => 3,
		'n' => '固定地图3',
		'c' => '4-3',
	),
	array(
		'_id' => 11,
		'n' => '随机地图1',
	),
	array(
		'_id' => 12,
		'n' => '随机地图2',
	),
	array(
		'_id' => 13,
		'n' => '随机地图3',
	),
	array(
		'_id' => 14,
		'n' => '随机地图4',
	)

);