<?php
function general_respond($data){
	ob_start();
	echo json_encode($data);
	ob_end_flush();
}