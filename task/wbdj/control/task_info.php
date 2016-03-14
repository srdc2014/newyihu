<?php
defined ( 'IN_KEKE' ) or exit ( 'Access Denied' );
$nav_active_index = 'task';
$basic_url = "index.php?do=task&id=$task_id"; 
$task_obj = wbdj_task_class::get_instance ( $task_info );
$task_info = $task_obj->_task_info;
$task_config = $task_obj->_task_config; 
$cover_cash = kekezu::get_cash_cove('',true);
$single_cash = $task_obj->_single_cash; 
$model_id = $task_info ['model_id']; 
$task_status = $task_obj->_task_status; 
$indus_arr = $kekezu->_indus_c_arr; 
$indus_p_arr = $kekezu->_indus_p_arr; 
$status_arr = $task_obj->_task_status_arr; 
$time_desc = $task_obj->get_task_timedesc (); 
$stage_desc = $task_obj->get_task_stage_desc (); 
$related_task = $task_obj->get_task_related (); 
$delay_rule = $task_obj->_delay_rule; 
$delay_total = sizeof ( $delay_rule ); 
$delay_count = intval ( $task_info ['is_delay'] ); 
$process_can = $task_obj->process_can (); 
$process_desc = $task_obj->process_desc (); 
$web_arr = keke_glob_class::get_oauth_type (); 
$task_obj->plus_view_num (); 
$payitem_str = $task_obj->get_payitem_str();
if(!$uid&&strstr(' '.$payitem_str,'seohide')){
	kekezu::show_msg("拒绝访问",$back_url,3,"该任务在您登录后才可以访问");
}
$time_obj = new wbdj_time_class ();
$time_obj->task_hand_end (); 
$sub_task_user_level =$g_info = $task_obj->_g_userinfo;
$g_info = $task_obj->_g_userinfo;
$show_payitem = $task_obj->show_payitem (); 
$browing_history = $task_obj->browing_history ( $task_id, $task_info ['task_cash'] . "元", $task_info ['task_title'] ); 
switch ($op) {
	case "reqedit" : 
       if($task_info['ext_desc']){
		$title = $_lang['edit_supply_demand'];
		}else{
		$title =$_lang['supply_demand'];
		}
		if ($sbt_edit) {
			$task_obj->set_task_reqedit ( $tar_content, '', 'json' );
		} else {
			$ext_desc = $task_info ['ext_desc'];
			require keke_tpl_class::template ( 'task/task_reqedit' );
		}
		die ();
		break;
	case "taskdelay" : 
		$title = $_lang ['task_delay'];
		if ($sbt_edit) {
			$task_obj->set_task_delay ( $delay_day, $delay_cash, '', 'json' );
		} else {
			$min_cash = intval ( $task_config ['min_delay_cash'] ); 
			$max_day = intval ( $task_config ['max_delay'] ); 
			$this_min_cash = intval ( $delay_rule [$delay_count] ['defer_rate'] * $task_info ['task_cash'] / 100 ); 
			$min_cash > $this_min_cash and $real_min = $min_cash or $real_min = $this_min_cash; 
			$credit_allow = intval ( $kekezu->_sys_config ['credit_is_allow'] ); 
			require keke_tpl_class::template ( "task/task_delay" );
		}
		die ();
		break;
	case "work_hand" : 
		if ($sbt) {
			$task_obj->weibo_work_hand ( $platform );
		} else {
			$title = "选择分享到哪里";
			$strPageTitle = "微博点击交稿";
			$plat = explode ( ',', $task_info ['wb_platform'] ); 
			! isset ( $step ) && $step = 1;
			$step = intval ( $step );
			if ($step == 2) {
				! $platform && $platform = $plat ['0']; 
				! in_array ( $platform, $plat ) && $platform = $plat ['0']; 
				$oauth_url = $kekezu->_sys_config ['website_url'] . "/index.php?do=task&id=$task_id&platform=$platform&op=work_hand&step=2";
				$weibo_arr = $task_obj->get_weibo_info ( $platform, $call_back, $oauth_url ); 
			}
			require keke_tpl_class::template ( "task/{$model_info ['model_code']}/tpl/default/wbdj_work" );
		}
		die ();
		break;
	case "wb_cl" : 
		$task_obj->wb_click ( $w_id );
		break;
	case "report" : 
		$transname = keke_report_class::get_transrights_name ( $type );
		$title = $transname . $_lang ['submit'];
		if($obj == 'task'){
			$report_reason = keke_report_class::get_report_task_reason();
		}elseif ($obj == 'work'){
			$report_reason = keke_report_class::get_report_work_reason();
		}
		if($sbt_edit){
			$task_obj->set_report ( $obj, $obj_id, $to_uid,$to_username, $type, $file_url, $tar_content,$slt_reason);
		} else {
			require keke_tpl_class::template ( "report" );
		}
		die ();
		break;
}
switch ($view) {
	case "work" :
		$search_condit = $task_obj->get_search_condit ();
		$date_prv = date ( "Y-m-d", time () ); 
		$work_status = $task_obj->get_work_status (); 
		intval ( $page ) and $p ['page'] = intval ( $page ) or $p ['page'] = '1';
		intval ( $page_size ) and $p ['page_size'] = intval ( $page_size ) or $p ['page_size'] = '10';
		$p ['url'] = $basic_url . "&view=work&page_size=" . $p ['page_size'] . "&page=" . $p ['page'];
		if($st){
			$p['url'] .="&st=".$st;
		}
		if($ut){
			$p['url'] .="&ut=".$ut;
		}
		$p ['anchor'] = '';
		$w ['work_status'] = $st; 
		$w ['user_type'] = $ut; 
		$work_arr = $task_obj->get_work_info ( $w, " work_id asc ", $p ); 
		$pages = $work_arr ['pages'];
		$work_info = $work_arr ['work_info'];
		break;
	case "base" :
	default :
		$weibo_plat = explode ( ',', $task_info ['wb_platform'] );
		$task_file = $task_obj->get_task_file (); 
		$objProm = keke_prom_class::get_instance ();
		$can_prom = $objProm->is_meet_requirement ( "bid_task", $task_id );
        if($task_info['task_status']==8){
			$list_work = db_factory::query(' select uid,username from '.TABLEPRE.'witkey_task_work where task_id='.intval($task_id).' and work_status =6 ');
		}
		if($task_info['task_status']==2&&$task_info['uid']==$uid){
			$item_list= keke_payitem_class::get_payitem_config ( 'employer', null, null, 'item_id' );
		}
}
function click_corve($work_id) {
	global $task_info, $task_id, $_lang;
	$day_arr = $cl_info = array ();
	$cl_tmp = db_factory::query ( sprintf ( " select count(view_id) count,click_time time,dayofyear(from_unixtime(click_time)) cl_day from %switkey_task_wbdj_views where task_id='%d' and work_id='%d' group by cl_day order by cl_day desc limit 0,10", TABLEPRE, $task_id, $work_id ) );
	if ($cl_tmp) {
		$s = sizeof ( $cl_tmp );
		$end_time = $cl_tmp [0] ['time'];
		$start_time = $cl_tmp [$s - 1] ['time'];
		$day_count = min ( ceil ( ($end_time - $start_time) / (24 * 3600) ), 10 );
		$day_arr = array_reverse ( range ( 0, $day_count - 1 ) );
		$zone = date ( 'Y-m-d', $end_time - ($day_count - 1) * 24 * 3600 ) . $_lang ['zhi'] . date ( 'Y-m-d', $end_time );
	} else {
		$zone = $_lang ['was_null'];
	}
	$t_caption = '<table class="chart_line"><caption>' . $_lang ['click_chart'] . '(' . $zone . ')</caption>';
	$t_head = '<thead><tr><td></td>';
	$t_body = '</thead><tbody><tr><th scope="row">' . $_lang ['click_num'] . '</th>';
	if ($cl_tmp [0] ['time']) {
		foreach ( $cl_tmp as $v ) {
			$time = date ( 'd', $v ['time'] );
			$cl_info [$time] = $v ['count'];
		}
	}
	foreach ( $day_arr as $v ) {
		$time = date ( 'd', $end_time - $v * 24 * 3600 );
		$t_head .= '<th scope="col">' . $time . '</th>';
		$click = intval ( $cl_info [$time] );
		$t_body .= '<td>' . $click . '</td>';
	}
	$t_head .= '</tr></thead>';
	$t_body .= '</tr></tbody>';
	$t_info = $t_caption . $t_head . $t_body . '</table>';
	return $t_info;
}
if($task_info['r_task_id']){
require keke_tpl_class::template ( "task_info");
}else{
require keke_tpl_class::template ( "task/" . $model_info ['model_code'] . "/tpl/" . $_K ['template'] . "/task_info" );
}
