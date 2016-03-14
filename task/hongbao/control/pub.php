<?php
$arrTimeInfo = getdate();
$arrConfig = $objRelease->_task_config;
$objRelease->get_task_obj($stdCacheName); 
$objRelease->pub_mode_init($stdCacheName, $init_info); 
$arrPubInfo = $objRelease->_std_obj->_release_info; 
$arrPubInfo['indus_pid'] and $arrAllIndustrys = CommonClass::getIndustryByPid($arrPubInfo['indus_pid'], 'indus_id,indus_pid,indus_name');
switch ($step) {
	case 'step1':
		$strMinDate = date('Y-m-d', time() + $arrConfig['min_time'] * 3600 * 24);
		$strDefaultMaxDay = $objRelease->_default_max_day;
		$floatBudget = $arrPubInfo['txt_task_cash'] ? $arrPubInfo['txt_task_cash'] : $arrConfig['min_cash'];
		$strDate = $arrPubInfo['txt_task_day'] ? $arrPubInfo['txt_task_day'] : $strDefaultMaxDay;
		$intMaxDay = date('Y-m-d H:i:s', time() + $arrConfig['max_time'] * 3600 * 24);
		if (isset($formhash) && kekezu::submitcheck($formhash)) {
			$floatTaskCash = floatval($txt_task_cash);
			$floatMinCash = floatval($arrConfig['min_cash']);
			$intMinDay = strtotime($strMinDate) + $IntDifftime;
			$strMaxDay = $objRelease->getMaxDay($floatTaskCash);
			$intMaxDay = strtotime($strMaxDay) + $IntDifftime;
			$intEndDate = strtotime(strval(trim($txt_task_day)));
			$floatMaxCash = floatval($arrConfig['max_cash']);
			if($floatMaxCash && $floatTaskCash > $floatMaxCash){
				$tips['errors']['txt_task_cash'] = "您的预算金额不能超过￥{$floatMaxCash}元";
				kekezu::show_msg($tips,NULL,NULL,NULL,'error');
			}
			if ($floatTaskCash < $floatMinCash) {
				$tips['errors']['txt_task_cash'] = '你的预算不能少于￥' . $floatMinCash . '元';
				kekezu::show_msg($tips, NULL, NULL, NULL, 'error');
			}
			if ($floatTaskCash <= 0) {
				$tips['errors']['txt_task_cash'] = '您的预算金额必须大于￥0元';
				kekezu::show_msg($tips, NULL, NULL, NULL, 'error');
			}
			$intMaxDay = date('Y-m-d H:i:s', time() + $arrConfig['max_time'] * 60 * 60 * 24);
			if (($intEndDate < time()) || ($intEndDate > (time() + $arrConfig['max_time'] * 60 * 60 * 24))) {
				$tips['errors']['txt_task_day'] = '当前预算允许最小天数为:' . intval($arrConfig['min_time']) . '天,最大截止时间：' . $intMaxDay;
				kekezu::show_msg($tips, NULL, NULL, NULL, 'error');
			}
			$arrPubInfo and $_POST = array_merge($arrPubInfo, $_POST);
			$_POST['txt_task_cash'] = keke_curren_class::convert($_POST['txt_task_cash'], 0, true);
			$objRelease->save_task_obj($_POST, $stdCacheName); 	
			kekezu::show_msg($tips, $strUrl . '&step=step2', NULL, NULL, 'ok');
		}
		break;
	case 'step2':
		if (isset($formhash) && kekezu::submitcheck($formhash)) {
			if (strtoupper(CHARSET) == 'GBK') {
				$_POST = kekezu::utftogbk($_POST);
			}
			if($_POST['province'] == 'p'){
				$tips['errors']['province'] = '请选择省份';
				kekezu::show_msg($tips,null,null,null,'error');
			}
			$_POST['tar_content'] = kekezu::escape($_POST['tar_content']);
			if (!$_POST['tar_content']) {
				$tips['errors']['tar_content'] = '请输入需求描述';
				kekezu::show_msg($tips, NULL, NULL, NULL, 'error');
			}
			$arrPubInfo and $_POST = array_merge($arrPubInfo, $_POST);
			$objRelease->save_task_obj($_POST, $stdCacheName); 	
			kekezu::show_msg($tips, $strUrl . '&step=step3', NULL, NULL, 'ok');
		} else {
			!$_SESSION[$stdCacheName] and kekezu::show_msg($_lang['friendly_notice'], "index.php?do=pubtask&id=$id", 2, "任务已提交，不可再返回修改！", "warning");
			$objRelease->check_access($step, $id, $arrPubInfo);
			$strExtTypes = kekezu::get_ext_type(); 	
			$indus_p_arr = $kekezu->_indus_task_arr; 	
			$indus_arr = $kekezu->get_classify_indus('task', 'child'); 	
		}
		$arrFileLists = CommonClass::getFileArray('|', $arrPubInfo['file_ids']);
		break;
	case 'step3':
		$intFileCount = 0;
		if ($arrPubInfo['file_ids']) {
			$intFileCount = count(explode('|', $arrPubInfo['file_ids']));
		}
		if (isset($formhash) && kekezu::submitcheck($formhash)) {
			$arrPayitems = array(
				'urgent' => intval($txt_urgent),
				'tasktop' => intval($txt_tasktop) && intval($text_tasktop) ? intval($text_tasktop) : 0,
				'workhide' => intval($txt_workhide),
				'seohide' => intval($txt_seohide),
			);
			$arrPayitems = array_filter($arrPayitems);
			PayitemClass::validPayitemCount($arrPayitems, $arrPubInfo['txt_task_day']);
			$_POST['payitem'] = $arrPayitems;
			$arrPubInfo and $_POST = array_merge($arrPubInfo, $_POST);
			$objRelease->save_task_obj($_POST, $stdCacheName); 	
			$intTaskId = $objRelease->pub_task(); 	
			$objRelease->update_task_info($intTaskId, $stdCacheName); 	
			kekezu::show_msg($tips, $strUrl . '&step=step4&taskId=' . $intTaskId, NULL, NULL, 'ok');
		} else {
			!$_SESSION[$stdCacheName] and kekezu::show_msg($_lang['friendly_notice'], "index.php?do=pubtask&id=$id", 2, "任务已提交，不可再返回修改！", "warning");
			$objRelease->check_access($step, $id, $arrPubInfo);
			$strTarComment = htmlspecialchars_decode($arrPubInfo['tar_content']);
			$strCommentLen = strlen($strTarComment);
			if ($strCommentLen > 1000) {
				$strPartComment = kekezu::cutstr($strTarComment, 1000);
			}
		}
		break;
	case 'step4':
		$taskId = intval($taskId);
		if (0 === $taskId) {
			kekezu::show_msg('无权访问', $strUrl, 3, NULL, 'warning');
		}
		$arrTaskInfo = $objRelease->check_access($step, $id, $arrPubInfo, $taskId);
		$arrPayInfo = $objRelease->checkWhetherToPay($taskId);
		$boolValue = $arrPayInfo['balance_pay'];
		$floatPayCash = $arrPayInfo['total_cash'];
		break;
}
require keke_tpl_class::template('pubtask');
die();