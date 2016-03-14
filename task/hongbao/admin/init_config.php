<?php
defined('ADMIN_KEKE') or 	exit('Access Denied');
$init_menu = array(
	$_lang['task_manage']=>'index.php?do=model&model_id=15&view=list&status=0',
	$_lang['task_config']=>'index.php?do=model&model_id=15&view=config',
);
$init_config = array(
	'model_id'=>15,
	'model_code'=>'hongbao',
	'model_name'=>'福袋任务',
	'model_dir'=>'hongbao',
	'model_type'=>'task',
	'model_dev'=>'kekezu',
	'model_status'=>1
);