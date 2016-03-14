<?php
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$nav_active_index = 'task';
$basic_url = "index.php?do=task&id=$task_id"; 
keke_lang_class::loadlang('public','task_wbzf');
$cove_arr = kekezu::get_table_data("*","witkey_task_cash_cove","","","","","cash_rule_id");
$task_obj = wbzf_task_class::get_instance ( $task_info );
$task_info = $task_obj ->_task_info;
$task_config =$task_obj->_task_config;
$model_id = $task_info ['model_id'];
$task_status = $task_obj->_task_status;
$indus_arr = $kekezu->_indus_c_arr; 
$indus_p_arr = $kekezu->_indus_p_arr; 
$status_arr = $task_obj->_task_status_arr; 
$time_desc = $task_obj->get_task_timedesc (); 
$stage_desc = $task_obj->get_task_stage_desc (); 
$related_task = $task_obj->get_task_related ();
$process_can = $task_obj->process_can (); 
$process_desc = $task_obj->process_desc (); 
$sub_task_user_level =$g_info = $task_obj->_g_userinfo;
$task_obj->plus_view_num();
$payitem_str = $task_obj->get_payitem_str();
if(!$uid&&strstr(' '.$payitem_str,'seohide')){
	kekezu::show_msg("拒绝访问",$back_url,3,"该任务在您登录后才可以访问");
}
$time_obj =new wbzf_time_class();
$time_obj->task_hand_end();
$web_arr = keke_glob_class::get_oauth_type();
$browing_history = $task_obj->browing_history($task_id,$task_info['task_cash'].$_lang['yuan'],$task_info['task_title']);
$g_info = $task_obj->_g_userinfo;
$plat = explode(',', $task_info['wb_platform']);
$plat_count = sizeof($plat);
$check_can_work = true;
switch ($op) {
	case "reqedit" : 
        if($task_info['ext_desc']){
		$title = $_lang['edit_supply_demand'];
		}else{
		$title =$_lang['supply_demand'];
		}
		if ($sbt_edit){
			$task_obj->set_task_reqedit ( $tar_content, '', 'json' );
			die();
		}
		$ext_desc = $task_info ['ext_desc'];
		require keke_tpl_class::template ( 'task/task_reqedit' );
		die ();
	case "work_hand" : 
		if ($sbt) {
			$url = "?do=task&id={$task_info['task_id']}&view=work";
			$comment = kekezu::escape($comment);
			$repost = $comment.'||'.kekezu::escape($repost);
			$repost_img = kekezu::escape($repost_img);
			$repost_data = kekezu::escape($nickname).':'.kekezu::escape($repost_data);
			$repost_img && $repost_data .= '<br/><a href="'.$repost_img.'">'.$repost_img.'</a>';
			$task_obj->weibo_work_hand ( $repost, '',$platform, $url, $domain, $repost_data, $plat_count);
			die();
		}
		$title =$_lang['hand_work'];
		$step = max((int)$step, 1);
		if ($check_work=='check_work' && $uid){
			$check_can_work = $task_obj->check_work_times($plat_count);
			if ($check_can_work==false){
				echo 'false';die();
			}
		}
		if ($step==2){
			$strPageTitle = $_lang['wbzf_work_hand'];
			(!$platform || !in_array($platform, $plat)) && $platform=$plat['0']; 
			if ($uid && !$task_obj->check_work_times($plat_count,$platform)){
				kekezu::show_msg ( $_lang['operate_tips'], "?do=task&id={$task_info['task_id']}&view=work", 2, $_lang['you_has_been'].'<b>'.$web_arr[$platform]['name'].'</b>'.$_lang['no_need_rehand'].'<br/>', 'warning' );
			}
			$oauth_url = $kekezu->_sys_config['website_url']."/index.php?do=task&id=$task_id&platform=$platform&op=work_hand&step=2";
			if ($relog){
				$weibo_login_class = new keke_oauth_login_class ( $platform );
				$weibo_login_class->logout();
				$weibo_login_class->login($call_back, $oauth_url);
			}
			$weibo_arr = $task_obj -> get_weibo_info($platform,$call_back, $oauth_url);
			$count = db_factory::query('select count(*) as total from '.TABLEPRE.'witkey_task_wbzf_work where wb_account="'.$weibo_arr['user_info']['account'].'" and task_id="'.$task_info['task_id'].'"');
			if ($count['0']['total']>0){
				$weibo_login_class = new keke_oauth_login_class ( $platform );
				$weibo_login_class->logout();
				kekezu::show_msg ( $_lang['operate_tips'], "?do=task&id={$task_info['task_id']}&view=work", 10, $_lang['you_no_need_rehand'].'<br/>', 'warning' );
			}
		}
		require keke_tpl_class::template ( "task/" . $model_info ['model_code'] . "/tpl/" . $_K ['template'] . '/wbzf_work' );
		die();
	case "report" : 
		$transname = keke_report_class::get_transrights_name($type);
		$title=$transname.$_lang['submit'];
		if($obj == 'task'){
			$report_reason = keke_report_class::get_report_task_reason();
		}elseif ($obj == 'work'){
			$report_reason = keke_report_class::get_report_work_reason();
		}
		if($sbt_edit){
			$task_obj->set_report ( $obj, $obj_id, $to_uid,$to_username, $type, $file_url, $tar_content,$slt_reason);
		}else{
			require keke_tpl_class::template("report");
		}
		die();
	case "work_del":
		$task_obj->del_work($work_id,'','json');
		break;
	case "comment" : 
		if ($obj_type=='work' && $tar_content) {
			$task_obj->set_work_comment ( $obj_type, $obj_id, $tar_content, $p_id, '', 'json' );
		}
		break;
}
switch ($view) {
	case "work" :
		$search_condit = $task_obj->get_search_condit();
		$date_prv = date("Y-m-d",time());
		$work_status = $task_obj->get_work_status ();
		$p['page'] = max((int)$page,1);
		intval ( $page_size ) and $p ['page_size'] = intval ( $page_size ) or $p['page_size']='10';
		$p['url'] = $basic_url."&view=work&page_size=".$p ['page_size']."&page=".$p ['page'];
		if($st){
			$p['url'] .="&st=".$st;
		}
		if($ut){
			$p['url'] .="&ut=".$ut;
		}
		$p ['anchor'] = '';
		$w['work_status'] = $st;
		$w['user_type']   = $ut;
		$work_arr = $task_obj->get_work_info ($w, " work_id asc ", $p ); 
		$pages = $work_arr ['pages'];
		$work_info = $work_arr ['work_info'];
		$display_str = $task_info['is_repost']==2 ? 'style="display:none;"' : '';
		$has_new  = $task_obj->has_new_comment($p ['page'],$p ['page_size']);
		break;
	default :
		$weibo_arr = $task_obj -> _weibo_arr;
		$weibo_arr = $weibo_arr['0'];
		$weibo_plat = explode(',', $weibo_arr['wb_platform']);
		$require_str = weibo_require($weibo_arr);
		$fans_part_str = fans_part($weibo_arr['unit_price']);
        if($task_info['task_status']==8){
			$list_work = db_factory::query(' select uid,username from '.TABLEPRE.'witkey_task_work where task_id='.intval($task_id).' and work_status =6 ');
		}
		if($task_info['task_status']==2&&$task_info['uid']==$uid){
			$item_list= keke_payitem_class::get_payitem_config ( 'employer', null, null, 'item_id' );
		}
		break;
}
$font_reqiure = array('is_focus'=>$_lang['focus'].'|', 'is_comment'=>$_lang['comment'].'|', 'is_at'=>'@', 'is_repost'=>$_lang['zf_this_weibo'].',','post'=>$_lang['post_weibo'].',');
function weibo_require($weibo){
	global $_lang;
	$font_reqiure = array('is_focus'=>$_lang['focus'].'|', 'is_comment'=>$_lang['comment'].'|', 'is_at'=>'@', 'is_repost'=>$_lang['zf_this_weibo'].',','post'=>$_lang['post_weibo'].',');
	$str = '';
	$str .= $weibo['is_repost']==1 ? $font_reqiure['is_repost'] : $font_reqiure['post'];
	$weibo['is_focus']==1 && $str .= $font_reqiure['is_focus'];
	$weibo['is_comment']==1 && $str .= $font_reqiure['is_comment'];
	$weibo['is_at']==1 && $str .= $font_reqiure['is_at'].$weibo['at_num'].$_lang['ge_friends'].'|';
	return rtrim($str,',|');
}
function fans_part($fans_arr){
	global $task_config,$_lang;
	$fans_rule = $task_config['affect_rule'];
	$fans_arr = unserialize($fans_arr);
	$str = '';
	while (list($key,$value)=each($fans_arr)) {
		$str .= '<li><strong >' . $value .$_lang['yuan'].'</strong>' . keke_glob_class::num2ch($key) . $_lang['level_yxl'].'</li>';
	}
	return $str;
}
function convert_star($i){
	$star  = intval($i);
	$star=='0' or $star =strlen(intval($i));
	echo keke_user_mark_class::gen_star2($star);
}
if($task_info['r_task_id']){
require keke_tpl_class::template ( "task_info");
}else{
require keke_tpl_class::template ( "task/" . $model_info ['model_code'] . "/tpl/" . $_K ['template'] . "/task_info" );
}