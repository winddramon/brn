<?php

$map_size = array(4, 4);//下限是0
$map_active_vars = array('c');//决定在id之外还把哪些参数写入gameinfo
$map_random_num = array(
	'static' => -1,
	'random' => 2
);
$mapinfo = array(
	'static' => array(
		array(
			'id' => 0,
			'n' => '传送装置',
			'c' => '2-2'
		),
		array(
			'id' => 1,
			'n' => '固定地图1',
			'c' => '1-3'
		),
		array(
			'id' => 2,
			'n' => '固定地图2',
			'c' => '0-4'
		)
	),
	'random' => array(
		array(
			'id' => 11,
			'n' => '随机地图1'
		),
		array(
			'id' => 12,
			'n' => '随机地图2'
		),
		array(
			'id' => 13,
			'n' => '随机地图3'
		),
		array(
			'id' => 14,
			'n' => '随机地图4'
		)
	)
);
?>