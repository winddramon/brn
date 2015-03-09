<?php

$GAME_CLASS = 'game_dtsp';
$COMMAND_CLASS = 'command_dtsp';
$PLAYER_CLASS = 'player_dtsp';
$COMBAT_CLASS = 'combat_dtsp';
$ITEM_CLASS = 'item_dtsp';

include(get_mod_path('bra').'/class.player.php');
include(get_mod_path('bra').'/class.item.php');
include(get_mod_path('bra').'/class.combat.php');
include(get_mod_path('bra').'/class.game.php');
include(get_mod_path('bra').'/class.command.php');

//include(get_mod_path('thbr').'/class.player.php');
//include(get_mod_path('thbr').'/class.item.php');
//include(get_mod_path('thbr').'/class.combat.php');
//include(get_mod_path('thbr').'/class.game.php');
//include(get_mod_path('thbr').'/class.command.php');

include(get_mod_path('dtsp').'/class.player.dtsp.php');
include(get_mod_path('dtsp').'/class.item.dtsp.php');
include(get_mod_path('dtsp').'/class.combat.dtsp.php');
include(get_mod_path('dtsp').'/class.game.dtsp.php');
include(get_mod_path('dtsp').'/class.command.dtsp.php');

include(ROOT_DIR.'/gamedata/settings.bra.php');
//include(ROOT_DIR.'/gamedata/settings.thbr.php');
include(ROOT_DIR.'/gamedata/settings.dtsp.php');
include(get_mod_path('dtsp').'/include/inc.lang.dtsp.php');
include(get_mod_path('dtsp').'/include/inc.maps.dtsp.php');

//include(get_mod_path('dtsp').'/include/func.general.dtsp.php');

?>