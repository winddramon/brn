<?php

$map_size = array(4, 4);//从0开始
$map_random_group = array(
	'static' => array(
		'num' => -1,
		'list' => array(0,1,2,3)
	),
	'random' => array(
		'num' => 2,
		'list' => array(11,12,13,14)
	),
);
$map_final_region = 3;
$map_region_access = array(//区域入口，0或者正数为地图编号，负数为该等级随机
	0 => 0,
	1 => 1,
	2 => -1,
	3 => 3
);
$mapinfo = array(
	array(
		'id' => 0,
		'n' => '传送装置',
		'c' => '2-2',
		'r' => 1
	),
	array(
		'id' => 1,
		'n' => '固定地图1',
		'c' => '1-3',
		'r' => 1
	),
	array(
		'id' => 2,
		'n' => '固定地图2',
		'c' => '0-4',
		'r' => 1
	),
	array(
		'id' => 3,
		'n' => '固定地图3',
		'c' => '4-3',
		'r' => 3
	),
	array(
		'id' => 11,
		'n' => '随机地图1',
		'r' => 2
	),
	array(
		'id' => 12,
		'n' => '随机地图2',
		'r' => 2
	),
	array(
		'id' => 13,
		'n' => '随机地图3',
		'r' => 2
	),
	array(
		'id' => 14,
		'n' => '随机地图4',
		'r' => 2
	)

);
?>