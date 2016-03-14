<?php
define( "IN_KEKE" ,true);
	$model_id 		= "15";
	$model_code 	= "hongbao";
	$model_name 	= "福袋任务";
	$model_intro 	= "通过发放福袋的形式，将赏金通过随机性发放给入选的威客";
	$model_desc 	= "通过发放福袋的形式，将赏金通过随机性发放给入选的威客";
	/*********Table witkey_model start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_model'");
	if ( $table_exist[0]){
		$cfg1 = array();
		$cfg1['model_code'] 		= $model_code;
		$cfg1['model_name'] 		= $model_name;
		$cfg1['model_dir'] 			= $model_code;
		$cfg1['model_type'] 		= 'task';
		$cfg1['model_dev'] 			= 'kekezu';
		$cfg1['model_status'] 		= '1';
		$cfg1['model_desc'] 		= $model_desc;
		$cfg1['on_time'] 			= time();
		$cfg1['hide_mode'] 			= 0;
		$cfg1['listorder'] 			= 11;
		$cfg1['model_intro'] 		= $model_intro;
		$cfg1['indus_bid'] 			= '';
		$cfg1['config'] 			= 'a:6:{s:10:"audit_cash";s:3:"100";s:8:"min_cash";s:2:"10";s:7:"min_num";s:1:"1";s:7:"max_num";s:1:"2";s:8:"max_time";s:1:"2";s:8:"min_time";s:1:"1";}';
		$cfg1['open_custom'] 		= 0;
		$modelcount = db_factory::get_count("SELECT count(*) FROM `".TABLEPRE."witkey_model` WHERE model_id = '{$model_id}'");
		if(!$modelcount){
			$cfg1['model_id'] 			= $model_id;
			db_factory::inserttable(TABLEPRE."witkey_model", $cfg1);
		}else{
			db_factory::updatetable(TABLEPRE."witkey_model", $cfg1,array("model_id"=>$model_id));
		}
	}
	/*********Table witkey_model end **************/
	
	/*********Table witkey_mark_config start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_mark_config'");
	if ( $table_exist[0]){
		db_factory::execute("DELETE FROM `".TABLEPRE."witkey_mark_config` WHERE obj = '{$model_code}'");
		$cfg1 = $cfg2 = array();
		$cfg1['obj'] 	= $cfg2['obj'] 		= $model_code;
		$cfg1['good'] 	= $cfg2['good'] 	= 100;
		$cfg1['normal'] = $cfg2['normal'] 	= 50;
		$cfg1['bad'] 	= $cfg2['bad'] 		= 0;
		$cfg1['type'] 	= 1;	$cfg2['type'] 	= 2;
		
		db_factory::inserttable(TABLEPRE."witkey_mark_config", $cfg1);
		db_factory::inserttable(TABLEPRE."witkey_mark_config", $cfg2);
	}
	/*********Table witkey_mark_config end **************/
	
	/*********Table witkey_priv_item start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_priv_item'");
	if ($table_exist[0]){
		db_factory::execute("DELETE FROM `".TABLEPRE."witkey_priv_item` WHERE model_id = '{$model_id}'");
		$cfg1 = $cfg2 = array();
		$cfg1['op_code'] 	= 'work_hand';		$cfg2['op_code'] 	= 'pub';
		$cfg1['op_name'] 	= '交稿';			$cfg2['op_name'] 	= '发布任务';
		$cfg1['limit_obj'] 	= '1';				$cfg2['limit_obj'] 	= '2';
		
		$cfg1['model_id'] 	= $cfg2['model_id'] 	= $model_id;
		$cfg1['allow_times']= $cfg2['allow_times'] 	= 0;
		$cfg1['condit'] 	= $cfg2['condit'] 		= '';
		
		$item_id1 = db_factory::inserttable(TABLEPRE."witkey_priv_item", $cfg1,1);
		$item_id2 = db_factory::inserttable(TABLEPRE."witkey_priv_item", $cfg2,1);
	}
	/*********Table witkey_priv_item end **************/
	
	/*********Table witkey_priv_rule start **************/
	$table_exist = db_factory::query("SHOW   TABLES   LIKE   '".TABLEPRE."witkey_priv_rule'");
	if ($table_exist[0]){
		$mark_rule_list = db_factory::query("SELECT mark_rule_id FROM `".TABLEPRE."witkey_mark_rule` ORDER BY mark_rule_id asc ");
		db_factory::execute("DELETE FROM `".TABLEPRE."witkey_priv_rule` WHERE item_id = '{$item_id1}'");
		db_factory::execute("DELETE FROM `".TABLEPRE."witkey_priv_rule` WHERE item_id = '{$item_id2}'");
		if($mark_rule_list){
			foreach ($mark_rule_list as $info){
				$cfg1 = $cfg2 = array();
				$cfg1['item_id'] 		=  $item_id1;
				$cfg1['mark_rule_id'] 	=  $info['mark_rule_id'];
				$cfg1['rule'] 			= '0';
				db_factory::inserttable(TABLEPRE."witkey_priv_rule", $cfg1,1);
				
				$cfg2['item_id'] 		=  $item_id2;
				$cfg2['mark_rule_id'] 	=  $info['mark_rule_id'];
				$cfg2['rule'] 			= '0';
				db_factory::inserttable(TABLEPRE."witkey_priv_rule", $cfg2,1);
			}
		}
	}
	
	
	$sql = "REPLACE INTO `".TABLEPRE."witkey_basic_config` VALUES ('160', 'select', 'task', '福袋任务', '当用户完成任务时会收到此通知', 'a:2:{s:8:\"send_sms\";i:1;s:10:\"send_email\";i:1;}', '1364996161', '<p>您的福袋任务{福袋任务}已完成，你得到金额{福袋金额}元</p>', '1', '您的福袋任务{福袋任务}已完成，你得到金额{福袋金额}元', '0', '{福袋任务}{福袋金额}')";
	db_factory::execute($sql);
	
	/*********Table witkey_priv_rule end **************/