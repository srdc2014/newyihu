<?php
$id=intval($id);
$orderId=intval($orderId);
$arrMemer=db_factory::get_one("select * from ".TABLEPRE."witkey_member where uid=".$gUid);
$twoPassword = keke_user_class::get_password ( $arrMemer['password'], $arrMemer['rand_code'] );
 if (isset($formhash)&&kekezu::submitcheck($formhash)) {
 	$sec_code=kekezu::escape(trim($zfpwd));
 	$strMd5Pwd = keke_user_class::get_password ( $sec_code, $gUserInfo ['rand_code'] );
 	$arrUserInfo=db_factory::get_one(sprintf("select * from %switkey_space where uid=%d and sec_code='%s'",TABLEPRE,intval($gUid),$strMd5Pwd));
 	switch ($type){
 		case 'task':
 			$fina_type="pub_".$type;
 			$tips='你已经支付成功了，不需要再次支付！';
 			$stryzfUrl='index.php?do=task&id='.intval($id);
 			$strwzfUrl='index.php?do=yepay&type='.$type.'&id='.intval($id).'&t='.$t;
 			$strSql="select * from ".TABLEPRE."witkey_finance where obj_id=".intval($id)." and fina_action='.$fina_type.'";
 			break;
 		case 'goods':
 			$fina_type="buy_service";
 			$tips='你已经支付成功了，不需要再次支付！';
 			$stryzfUrl='index.php?do=goods&id='.intval($id);
 			$strwzfUrl='index.php?do=order&sid='.$id.'&step=step2&orderId='.$orderId.'&action=confirm_pay';
 			$strSql="select * from ".TABLEPRE."witkey_finance where order_id=".intval($orderId)." and fina_action='.$fina_type.'";
 			break;	
 		case 'service':
 			$fina_type="buy_service";
 			$tips='你已经支付成功了，不需要再次支付！';
 			$stryzfUrl='index.php?do=goods&id='.intval($id);
 			$strwzfUrl='index.php?do=order&sid='.$id.'&step=step3&orderId='.$orderId.'&action=pay';
 			$strSql="select * from ".TABLEPRE."witkey_finance where order_id=".intval($orderId)." and fina_action='.$fina_type.'";
 			break;
 		case 'pubservice':
 			$tips='你已经支付成功了，不需要再次支付！';
 			$stryzfUrl='index.php?do=goods&id='.intval($id);
 			$strwzfUrl='index.php?do=yepay&type=service&id='.intval($id)."&orderId=".$orderId;
 			$strSql="select * from ".TABLEPRE."witkey_order where order_id=".intval($orderId)." and order_status='ok'";
 			break;		
 		case 'gy':
 			$fina_type="buy_gy";
 			$tips['errors']['zfpwd'] = '你已经支付成功了，不需要再次支付！';
 			$stryzfUrl=NULL;
 			$strwzfUrl='index.php?do=gy&id='.$id.'&step=step3&orderId='.$orderId.'&action=pay';
 			$strSql="select * from ".TABLEPRE."witkey_order where order_id=".intval($orderId)." and order_status='ok'";
 			break;
 		case 'taskCash':
 			$fina_type="hosted_reward";
 			$tips='你已经支付成功了，不需要再次支付！';
 			$stryzfUrl='index.php?do=task&id='.intval($id);
 			$strwzfUrl="index.php?do=taskhandle&op=consign&taskId=".$id;
 			$strSql="select * from ".TABLEPRE."witkey_finance where obj_id=".intval($id)." and fina_action='.$fina_type.'";
 			break;	
 	}
 	$people = array("task", "goods", "service", "pubservice","gy","taskCash");
 	if($arrUserInfo && $type && in_array($type,$people)){
 		$arrFinance=db_factory::get_one($strSql);
 		if($arrFinance){
 			kekezu::show_msg($tips,$stryzfUrl,'','','success');
 		}else{ 			
 			$userIp=kekezu::get_ip ();
 			$_SESSION['UserBalance']=$gUid.'|'.$userIp.'|'.$sec_code;
 			kekezu::show_msg('密码验证成功',$strwzfUrl,'','','success');
 		}
 	}else{ 	
 		$tip['errors']['zfpwd'] = '密码验证失败';
 		kekezu::show_msg($tip,NULL,NULL,NULL,'error');
 	}
}
