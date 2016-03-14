<?php
defined ( 'ADMIN_KEKE' ) or exit ( 'Access Denied' );
kekezu::admin_check_role ('m1020');
$task_config = unserialize ( $model_info ['config'] );
$model_list = $kekezu->_model_list;
$table_obj = keke_table_class::get_instance('witkey_task');
$task_status = taobao_task_class::get_task_status ();
$page and $page=intval ( $page ) or $page = 1;
$page_size and $page_size = intval($page_size) or $page_size=10;
$wh = " a.model_id=10";
if ($w['task_id']) {
	$wh .= " and a.task_id = " . intval($w['task_id']);
}
$wb_platform_arr = array(
			'sina' => '新浪',
			'ten' => '腾讯',
		'sina,ten' => '新浪,腾讯',
		);
$w ['wb_platform'] and
	$wh .= " and b.wb_platform like '%$w[wb_platform]%'";
if ($w['task_status']) {
	$wh .= " and a.task_status = " .$w['task_status'];
}
$w['task_status']==='0' and $wh .= " and task_status = 0" ;
if ($sbt_search) {
	$wh .= " order by a.$ord[0] $ord[1]";
}else{
	$wh .= " order by a.task_id desc ";
}
$url_str = "index.php?do=model&model_id=10&view=list&w[task_id]={$w['task_id']}&w[task_status]={$w['task_status']}&w[wb_platform]={$w['wb_platform']}&ord[0]=$ord[0]&ord[1]=$ord[1]&page=$page&page_size=$page_size";
$sql_count = "select count(a.task_id) from ".TABLEPRE."witkey_task_taobao b left join ".TABLEPRE."witkey_task a on a.task_id = b.task_id where ".$wh;
$count = db_factory::get_count($sql_count);
$pages = $page_obj->getPages($count, $page_size, $page, $url_str);
$wh .= $pages['where'];
$sql = "select a.*,b.* from ".TABLEPRE."witkey_task_taobao b left join ".TABLEPRE."witkey_task a on a.task_id = b.task_id where".$wh;
$task_arr = db_factory::query($sql);
if($task_id){ 
	$task_audit_arr = get_task_info($task_id);
	$start_time = date("Y-m-d H:i:s",$task_audit_arr['start_time']);
	$end_time = date("Y-m-d H:i:s",$task_audit_arr['end_time']);
	$url = "<a href =\"{$_K['siteurl']}/index.php?do=task&id={$task_audit_arr['task_id']}\" target=\"_blank\" >" . $task_audit_arr['task_title']. "</a>";
}
switch ($ac) {
	case "del" : 
		$res = keke_task_config::task_del($task_id);
		kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['delete_success'],'success');
		break;
	case "pass" : 
		$res =keke_task_config::task_audit_pass ( $task_id );
		kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['examine_successfully'],'success');
		break;
	case "nopass" : 
		$res =keke_task_config::task_audit_nopass ( $task_id );	
		kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['operate_success'],'success');
		break;
	case "freeze" : 
		$res =keke_task_config::task_freeze ( $task_id );
		kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_frooze_successfully'],'success');
		break;
	case "unfreeze" : 
		$res =keke_task_config::task_unfreeze ( $task_id );
		kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_unfrooze_successfully'],'success');
		break;
	case "recommend":
		$res = keke_task_config::task_recommend($task_id);
		kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_recommend_successfully'],'success');
		break;
	case "unrecommend":
		$res = keke_task_config::task_unrecommend($task_id);
		kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['task_unrecommend_successfully'],'success');
		break;
}
if ($sbt_action==$_lang['mulit_delete']&&!empty($ckb)) {
	keke_task_config::task_del($ckb);
	kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_delete_success'],'success');
}
if ($sbt_action==$_lang['mulit_pass']&&!empty($ckb)) {
	keke_task_config::task_audit_pass($ckb);
	kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_pass_success'],'success');
}
if ($sbt_action==$_lang['mulit_nopass']&&!empty($ckb)) {
	keke_task_config::task_audit_nopass($ckb);
	kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_nopass_success'],'success');
}
if ($sbt_action==$_lang['mulit_freeze']&&!empty($ckb)) {
	keke_task_config::task_freeze($ckb);
	kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_freeze_success'],'success');
}
if ($sbt_action==$_lang['mulit_unfreeze']&&!empty($ckb)) {
	keke_task_config::task_unfreeze($ckb);
	kekezu::admin_show_msg($_lang['operate_notice'],$url_str,2,$_lang['mulit_unfreeze_success'],'success');
}
function get_task_info($task_id){
	$task_obj = new Keke_witkey_task_class();
	$task_obj->setWhere("task_id = $task_id");
	$task_info = $task_obj->query_keke_witkey_task();
	$task_info = $task_info['0'];
	return $task_info;
}
require $kekezu->_tpl_obj->template ( 'task/' . $model_info ['model_dir'] . '/admin/tpl/task_' . $view );