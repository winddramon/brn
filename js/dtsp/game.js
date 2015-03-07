//function request(action, param){
//	if(ajax_lock){
//		return;
//	}
//	ajax_lock = true;
//	ajax_plock = true;
//	
//	if(param == undefined){
//		param = {};
//	}
//	
//	request_msec = (new Date()).valueOf();
//	
//	$.post("command.dtsp.php", { action : action , param : param }, function(data, status){//入口修改为command.dtsp.php
//		ajax_lock = false;
//		ajax_plock = false;
//		if(!status){
//			error_msg("网络传输错误");
//		}else{
//			respond(data);
//		}
//	}, "json")
//	.error(function(e){
//		debug("Unparsable Content: " + e.responseText);
//	});
//}

function enter_change_avatar(gender, icon, avatar_dir){
	var avatar_dir = typeof(avatar_dir) == "undefined" ? "" : avatar_dir + '/';
	uri = 'img/' + avatar_dir + gender + "_" + icon + (icon == 0 ? ".gif" : ".png");
	$("#F-enter-avatar").attr("src", uri);
}

function init_join(param){
	$("#F-enter-avatar").attr("src", param["avatar"]);
	$("#F-enter-form-icon-f").val(0);
	$("#F-enter-form-icon-m").val(0);
	
	var avatar_dir = param["avatar_dir"];
	
	$("#F-enter-form .icon-selector select").change(function(){
		gender = $("#F-enter-form input[name='gender']:checked").val();
		icon = $(this).children('option:selected').val();
		enter_change_avatar(gender, icon, avatar_dir);
	});
	
	$("#F-enter-info input[type='radio']").change(function(){
		gender = $(this).val();
		icon = $("#F-enter-form .icon-selector select#F-enter-form-icon-"+gender).children('option:selected').val();
		enter_change_avatar(gender, icon, avatar_dir);
		switch(gender){
			case "f":
				$("#F-enter-form-icon-f").show();
				$("#F-enter-form-icon-m").hide();
				break;
			
			case "m":
			default:
				$("#F-enter-form-icon-f").hide();
				$("#F-enter-form-icon-m").show();
				break;
		}
	});
	
	$("#F-enter-form input[value='"+param["gender"]+"']").attr("checked", true);
	$("#F-enter-form-icon-"+param["gender"]).show();
	$("#F-enter-form-icon-"+param["gender"]).val(param["icon"]);
	$("#F-enter-form-motto").attr("value", param["motto"]);
	$("#F-enter-form-killmsg").attr("value", param["killmsg"]);
	$("#F-enter-form-lastword").attr("value", param["lastword"]);
	
	switch_frame('enter');
	
	$("#F-enter-form").submit(function(e){
		e.preventDefault();
		gender = $("#F-enter-form input[name='gender']:checked").val();
		icon = $("#F-enter-form .icon-selector select#F-enter-form-icon-"+gender).children('option:selected').val();
		request('enter_game', {
			icon : icon,
			gender : gender,
			motto : $("#F-enter-form-motto").val(),
			killmsg : $("#F-enter-form-killmsg").val(),
			lastword : $("#F-enter-form-lastword").val()
		});
	});
}

function parse_item(div, item, type){
	if(item != undefined && item['n'] != ''){
		if(typeof(item['skinfo']) !== 'undefined' && item['skinfo'].length > 0){
			itemskinfo = ' title="'+item['skinfo']+'"';
		}else{
			itemskinfo = '';
		}
		div.html('<div class="n"'+itemskinfo+'>'+
			item['n']+
			'</div><div class="k">'+
			item['k']+
			'</div><div class="detial"><div>效果：'+
			item['e']+
			'</div><div>耐久：'+
			item['s']+
			'</div></div>');
	}else{
		div.html('<div class="null">无'+type+'</div>');
	}
	return;
}

function respond(data){
	
	var respond_msec = (new Date()).valueOf();
	var show_performance = false;
	var feedback = [];
	
	for(var aid in data){
		
		action = data[aid]["action"];
		param = data[aid]["param"];
		
		switch(action){
			
			case 'battle':
				$("#F-console-battle .name").html(param["enemy"]["name"]);
				$("#F-console-battle .gender").html(param["enemy"]["gender"]);
				$("#F-console-battle .number").html(param["enemy"]["number"]);
				$("#F-console-battle .avatar").attr("src", param["enemy"]["avatar"]);
				$("#F-console-battle .status").html(param["enemy"]["status"]);
				$("#F-console-center .wrapper[content='battle']").fadeIn(200);
				$("#F-console-center .wrapper[content='battle']").unbind("click");
				
				if(param["end"]){
					$("#F-console-battle .action").fadeOut(200);
					$("#F-console-battle .back").fadeIn(200);
					$("#F-console-center .wrapper[content='battle']").click(function(){
						battle_action("back");
					});
				}else{
					$("#F-console-battle .action").fadeIn(200);
					$("#F-console-battle .back").fadeOut(200);
				}
				
				if(param["enemy"]["action"].length == 0){
					$("#F-console-battle .action").empty();
				}else{
					$("#F-console-battle .action").html(parse_battle_action(param["enemy"]));
				}
				
				$("#F-console-battle button").unbind("click");
				$("#F-console-battle button").click(function(e){
					battle_action($(this));
				});
				
				break;
			
			case 'radar':
				$("#F-console-map-table .map_block").not("td[mid=-1]").each(function(){
					$(this).attr("name", $(this).html());
					$(this).empty();
				});
				$("#F-console-map-table .map_block").addClass("radar");
				
				var map_blocks;
				for(var mid in param['result']){
					map_blocks = $("#F-console-map-table .map_block[mid="+mid+"]");
					map_blocks.eq(Math.floor(Math.random() * map_blocks.length)).html(param['result'][mid]);
					console.debug(mid);
				}
				
				$("#F-console-map-table .mask").show();
				break;
			
			case 'item_param':
				show_item_param(param);
				break;
			
			case 'item':
				update_item(param);
				break;
			
			case 'buff':
				update_buff(param['buff']);
				break;
			
			case 'pose':
				$("#F-console-panel .block[acceptor='pose'] .tactic").removeClass("selected");
				$("#F-console-panel .block[acceptor='pose'] .tactic[tid="+param['tid']+"]").addClass("selected");
				break;
			
			case 'tactic':
				$("#F-console-panel .block[acceptor='tactic'] .tactic").removeClass("selected");
				$("#F-console-panel .block[acceptor='tactic'] .tactic[tid="+param['tid']+"]").addClass("selected");
				break;
			
			case 'club':
				$("#F-console-club").html(param['name']);
				break;
				
			case 'team':
				$("#F-console-team").html(param['name']);
				$("#F-console-teaminfo .in_team .name").html(param['name']);
				if(param['joined']){
					$("#F-console-teaminfo .no_team").fadeOut(200, function(e){
						$("#F-console-teaminfo .in_team").fadeIn(200);
					});
				}else{
					$("#F-console-teaminfo .in_team").fadeOut(200, function(e){
						$("#F-console-teaminfo .no_team").fadeIn(200);
					});
				}
				break;
				
			case 'battle_data':
				$("#F-console-att").html(parseInt(parseFloat(param['att']) * 10) / 10);
				$("#F-console-def").html(parseInt(parseFloat(param['def']) * 10) / 10);
				break;
				
			case 'money':
				$("#F-console-money").html(param['money']);
				break;
				
			case 'name':
				$("#F-console-name").html(param['name']);
				break;
			
			case 'avatar':
				$("#F-console-avatar").attr("src", param['src']);
				$("#nav-cuser-name img").attr("src", param['src']);
				break;
				
			case 'number':
				$("#F-console-number").html(param['number']);
				break;
				
			case 'gender':
				$("#F-console-gender").html(param['gender']);
				break;
				
			case 'weather':
				$("#F-console-weather").html(param['name']);
				break;
				
			case 'location':
				$("#F-console-area").html(param['name']);
				if(param['shop']){
					$("#F-console-panel-shop").slideDown(200);
				}else{
					$("#F-console-panel-shop").slideUp(200);
				}
				break;
			
			case 'rage':
				$("#F-console-rage").html(param['rage']);
				break;
			
			case 'exp':
				if(param['target'] != undefined){
					UIconfig['texp'] = parseInt(param['target']);
				}
				if(param['current'] != undefined){
					UIconfig['cexp'] = parseInt(param['current']);
				}
				
				if(param['target'] == 0){
					var result = "100%";
				}else if(param['current'] == 0){
					var result = "0%";
				}else{
					var result = 100 * UIconfig['cexp'] / UIconfig['texp'];
					if(result > 100){
						result = "100%";
					}else{
						result = result + "%";
					}
				}
				
				$("#F-console-exp .indicator").width(result);
				$("#F-console-exp .label").html("经验 "+UIconfig['cexp']+" / "+UIconfig['texp']);
				
				if(param['level'] != undefined){
					$("#F-console-level").html("等级 "+param['level']);
				}
				
				break;
			
			case 'max_health':
				UIconfig['mhp'] = parseInt(param['mhp']);
				UIconfig['msp'] = parseInt(param['msp']);
				break;
			
			case 'health':
				if(param['hp'] != undefined){
					UIconfig['hp'] = parseInt(param['hp']);
				}
				if(param['sp'] != undefined){
					UIconfig['sp'] = parseInt(param['sp']);
				}
				update_health();
				break;
				
			case 'heal_speed':
				UIconfig['hpps'] = parseFloat(param['hpps']);
				UIconfig['spps'] = parseFloat(param['spps']);
				break;
			
			case 'area_info':
				$("#F-console-map .map_block[mid]").removeClass("forbidden");
				$("#F-console-map .map_block[mid]").removeClass("dangerous");
				for(var areaid in param['dangerous']){
					$("#F-console-map .map_block[mid='"+param['dangerous'][areaid]+"']").addClass("dangerous");
				}
				for(var areaid in param['forbidden']){
					$("#F-console-map .map_block[mid='"+param['forbidden'][areaid]+"']").addClass("forbidden");
				}
				break;
				
			case 'proficiency':
				for(var type in param['proficiency']){
					$("#F-console-playerinfo .proficiency div[type='"+type+"']").html(param['proficiency'][type]);
				}
				break;
			
			case 'shop':
				$("#F-console-shop .counter div").removeClass("selected");
				$("#F-console-shop .counter div[cid='"+param['kind']+"']").addClass("selected");
				$("#F-console-shop .goods").html(parse_goods(param['goods']));
				update_price();
				
				$("#F-console-shop .goods button[action='add']").click(function(e){
					input = $(this).parent().find("input");
					if(parseInt(input.val()) < parseInt(input.attr("max"))){
						input.val(parseInt(input.val()) + 1);
					}
					update_price();
				});
				
				$("#F-console-shop .goods button[action='cut']").click(function(e){
					input = $(this).parent().find("input");
					if(parseInt(input.val()) > 0){
						input.val(parseInt(input.val()) - 1);
					}
					update_price();
				});
				
				$("#F-console-shop .goods .item").mousewheel(function(e, delta){
					e.preventDefault();
					input = $(this).find("input");
					if(delta > 0){
						if(parseInt(input.val()) < parseInt(input.attr("max"))){
							input.val(parseInt(input.val()) + 1);
						}
					}else if(delta < 0){
						if(parseInt(input.val()) > 0){
							input.val(parseInt(input.val()) - 1);
						}
					}
					update_price();
				});
				
				break;
			
			case 'buff_name':
				UIvar['buff_name'] = param;
				break;
			
			case 'buff_help':
				UIvar['buff_help'] = param;
				break;
			
			case 'chat_msg':
				insert_chat_msg(param['time'], param['msg']);
				break;
			
			case 'notice':
				notice_msg(param['msg'], param['time']);
				break;
			
			case 'feedback':
				feedback.push({type:"feedback", msg:param['msg'], time:param['time']});
				break;
			
			case 'error':
				feedback.push({type:"error", msg:param['msg'], time:param['time']});
				break;
			
			case 'die':
				show_brief('die');
				UIvar['alive'] = false;
				break;
			
			case 'end':
				show_brief('end');
				break;
			
			case 'brief':
				show_brief('brief', 0);
				$("#F-console-brief .brief").html(param['html']);
				$("#F-console-brief .brief").click(function(){
					$("#F-console-brief").fadeOut(400);
				});
				break;
			
			case 'init':
				init_gameUI();
				break;
			
			case 'game_settings':
				UIconfig['poison_damage'] = param['poison_damage'];
				UIconfig['poison_recover'] = param['poison_recover'];
				break;
			
			case 'currency':
				UIconfig['currency'] = param['name'];
				break;
			
			case 'need_login':
				alert("请先登录");
				break;
			
			case 'game_over':
				alert("游戏尚未开始");
				break;
			
			case 'need_join':
				init_join(param);
				break;
				
			case 'combo':
				alert("连斗以后不允许加入游戏");
				break;
			
			case 'performance':
				show_performance = true;
				process_msec = parseInt(param['process_sec'] * 1000);
				break;
				
			default:
				debug("unexpected action: "+action);
				break;
		}
	
		debug(data[aid]);
	}
	
	if(feedback.length > 0){
		var current_time = Date.parse(new Date());
		$("#F-console-feedback .feedback").each(function(){
			if(current_time - parseInt($(this).attr("time")) >= 1500) {
				$(this).slideUp(200, function () {
					$(this).remove();
				})
			}
		});
		$("#F-console-feedback .error").each(function(){
			if(current_time - parseInt($(this).attr("time")) >= 1500) {
				$(this).slideUp(200, function () {
					$(this).remove();
				})
			}
		});
		
		for(var i in feedback){
			if(feedback[i]['type'] == 'feedback'){
				feedback_msg(feedback[i]['msg'], feedback[i]['time']);
			}else{
				error_msg(feedback[i]['msg'], feedback[i]['time']);
			}
		}
	}
	
	if(show_performance){
		var result1 = '';
		var result2 = '';
		if(param['process_sec'] != undefined){
			result1 += '<div class="key">网络延迟：</div><div class="value">'+(respond_msec - request_msec - process_msec)+'毫秒</div>';
			result1 += '<div class="key">处理时间：</div><div class="value">'+process_msec+'毫秒</div>';
			result2 += '<div class="key">渲染时间：</div><div class="value">'+((new Date()).valueOf() - respond_msec)+'毫秒</div>';
		}
		if(param['db_query_times']){
			result2 += '<div class="key">数据库操作量：</div><div class="value">'+param['db_query_times']+'次</div>';
		}
		$("#footer .performance .frame[fid='0']").html(result1);
		$("#footer .performance .frame[fid='1']").html(result2);
	}
}