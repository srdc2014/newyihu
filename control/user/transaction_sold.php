<?php
$intModelId and $intModelId and $intModelId = intval($intModelId) or $intModelId =0;
$arrServiceModels = ServiceClass::getEnabledServiceModelList();
$strUrl ="index.php?do=user&view=transaction&op=sold";
$intModelId and $strUrl.='&intModelId='.intval($intModelId);
$strStatus and $strUrl .="&strStatus=".strval($strStatus);
$strOrder and $strUrl .="&strOrder=".strval($strOrder);
$strTitle and $strUrl .="&strTitle=".strval($strTitle);
$arrListOrder = array(
		'b.order_id desc' =>'编号降序',
		'b.order_id asc' =>'编号升序'
);
if($intModelId){
    $arrStatus = call_user_func(array($arrServiceModels[$intModelId]['model_code'].'_shop_class','get_order_status'));
}else{
    $arrStatus1 = call_user_func(array($arrServiceModels[6]['model_code'].'_shop_class','get_order_status'));
    $arrStatus2 = call_user_func(array($arrServiceModels[7]['model_code'].'_shop_class','get_order_status'));
    $arrStatus = array_merge($arrStatus1,$arrStatus2);
}
if (isset($action)){
	if($intModelId){
		$className = $arrServiceModels[$intModelId]['model_code'].'_shop_class';
	}else{
		$orderInfo = db_factory::get_one("select * from ".TABLEPRE."witkey_order where order_id = ".intval($orderId));
		if(!in_array($orderInfo['model_id'], array(6,7))){
			kekezu::show_msg("订单不存在",null,null,null,'fail');
		}
		$className = $arrServiceModels[$orderInfo['model_id']]['model_code'].'_shop_class';
	}
	$objShop = new $className();
	$resText = $objShop->dispose_order($orderId, $action);
	unset($objShop);
	if(true === $resText){
		kekezu::show_msg('操作成功',$strUrl,3,null,'ok');
	}else{
		kekezu::show_msg($resText,null,null,null,'fail');
	}
}
$strWhere  = ' 1=1 ';
$strWhere .= ' and a.seller_uid = '.$gUid;
$intModelId and $strWhere .= ' and a.model_id = '.$intModelId;
$strWhere .= ' and b.obj_type = '."'service'";
$page and $intPage = intval($page);
$intPage = intval ( $intPage ) ? $intPage : 1;
$intPagesize =  10;
$intId and $strWhere .= " and b.order_id=".intval($intId);
$strTitle and $strWhere .= " and a.order_name like '%".trim($strTitle)."%' ";
if(isset($strStatus)&&$strStatus!=''&&$strStatus>-1&&in_array($strStatus, array_keys($arrStatus))){
	$strWhere .= " and a.order_status ='".strval($strStatus)."'";
}else{
	$strStatus = -1;
}
$strOrder&&in_array($strOrder, array_keys($arrListOrder)) and $strWhere .= " order by ".$strOrder or  $strWhere .= " order by a.order_time desc";
if($intModelId === 6){
	$strOrderSql = ' SELECT a.order_name,a.model_id, a.seller_uid, a.seller_username,a.order_uid, a.order_username, a.order_amount, a.order_status, a.order_time, '
			.' b.*, c.service_id, c.title , c.price '
			.' FROM `'.TABLEPRE.'witkey_order` AS a '
					.' LEFT JOIN '.TABLEPRE.'witkey_order_detail AS b ON a.order_id = b.order_id '
							.' LEFT JOIN '.TABLEPRE.'witkey_service AS c ON b.obj_id = c.service_id '
									.' WHERE '.$strWhere;
}elseif($intModelId == 7){
	$strOrderSql = ' SELECT  a.order_name,a.model_id, a.seller_uid, a.seller_username,a.order_uid, a.order_username, a.order_amount, a.order_status, a.order_time, '
			.' b.*, c.service_id,  c.title , c.price '
			.' FROM `'.TABLEPRE.'witkey_order` AS a '
					.' LEFT JOIN '.TABLEPRE.'witkey_order_detail AS b ON a.order_id = b.order_id '
							.' LEFT JOIN '.TABLEPRE.'witkey_service_order AS c ON b.order_id = c.order_id '
									.' WHERE '.$strWhere;
}else{
	    $strOrderSql = ' SELECT a.order_name,a.model_id, a.seller_uid, a.seller_username,a.order_uid, a.order_username, a.order_amount, a.order_status, a.order_time, '
	        .' b.*, c.service_id,  c.title , c.price ,d.service_id as oservice_id,  d.title as otitle, d.price as oprice'
	            .' FROM `'.TABLEPRE.'witkey_order` AS a '
	                .' LEFT JOIN '.TABLEPRE.'witkey_order_detail AS b ON a.order_id = b.order_id '
	                    .' LEFT JOIN '.TABLEPRE.'witkey_service AS c ON b.obj_id = c.service_id '
	                        .' LEFT JOIN '.TABLEPRE.'witkey_service_order AS d ON b.order_id = d.order_id '
	                          .' WHERE '.$strWhere;
}
$arrDatas  = db_factory::query($strOrderSql);
$arrPageArr = $kekezu->_page_obj->page_by_arr($arrDatas, $intPagesize, $intPage, $strUrl);
$arrLists = $arrPageArr ['data'];
foreach ($arrLists as $k =>$v){
	$arrMark = keke_user_mark_class::get_mark_info ( array ('model_code' => $arrServiceModels[$intModelId]['model_code'], 'obj_id' => $v['order_id'],'by_uid'=>$gUid,'uid'=>$v['order_uid']) );
	$markInfo = $arrMark ['mark_info'] ['0'];
	$arrLists[$k]['mark_status'] = $markInfo['mark_status'];
}
$strPages = $arrPageArr ['page'];
