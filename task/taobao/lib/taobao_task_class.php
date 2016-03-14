<?php
keke_lang_class::load_lang_class ( 'taobao_task_class' );
class taobao_task_class extends keke_task_class {
	public $_task_status_arr; 
	public $_work_status_arr; 
	public $_assign; 
	public $_tao_id;
	public $_task_url; 
	protected $_inited = false;
	public static function get_instance($task_info) {
		static $obj = null;
		if ($obj == null) {
			$obj = new taobao_task_class ( $task_info );
		}
		return $obj;
	}
	public function __construct($task_info) {
		global $kekezu, $_K;
		parent::__construct ( $task_info );
		$siteurl = preg_replace ( "/localhost/i", "127.0.0.1", $_K ['siteurl'], 1 );
		$this->_task_url = $siteurl . '/index.php?do=task&id=' . $this->_task_id;
		$this->init ();
	}
	public function init() {
		if (! $this->_inited) {
			$this->status_init ();
			$this->wiki_priv_init ();
			$this->get_tao_init ();
		}
		$this->_inited = true;
	}
	public function status_init() {
		$this->_task_status_arr = $this->get_task_status ();
		$this->_work_status_arr = $this->get_work_status ();
	}
	private function get_tao_init() {
		$task_id = $this->_task_id;
		$sql = 'select * from ' . TABLEPRE . 'witkey_task_taobao where task_id=' . $task_id;
		$tao_info = db_factory::get_one ( $sql );
		$this->_tao_id = $tao_info ['taobao_id'];
		$this->_assign = $tao_info ['assign'];
		$this->_task_info = array_merge ( $tao_info, $this->_task_info );
	}
	public function wiki_priv_init() {
		$arr = taobao_priv_class::get_priv ( $this->_task_id, $this->_model_id, $this->_userinfo );
		$this->_priv = $this->user_priv_format ( $arr );
	}
	public function get_task_timedesc() {
		global $_lang;
		$status_arr = $this->_task_status_arr;
		$task_status = $this->_task_status;
		$task_info = $this->_task_info;
		$time_desc = array ();
		switch ($task_status) {
			case "0":
				$time_desc ['ext_desc'] =  $_lang['task_nopay_can_not_look']; 
				break;
			case "1":  
				$time_desc ['ext_desc'] = $_lang['wait_patient_to_audit']; 
				break;
			case "2" : 
				$time_desc ['time_desc'] = $_lang ['from_hand_work_deadline']; 
				$time_desc ['time'] = $task_info ['sub_time']; 
				$time_desc ['ext_desc'] = $_lang['hand_work_and_reward_trust']; 
				if ($this->_task_config ['open_select'] == 'open') { 
					$time_desc ['g_action'] = $_lang ['now_employer_can_choose_work']; 
				}
				break;
			case "3":
				$time_desc ['ext_desc'] = $_lang['work_choosing_and_wait_employer_choose']; 
				break;
			case "7" : 
				$time_desc ['ext_desc'] =$_lang['task_frozen_can_not_operate'];
				break;
			case "8" : 
				$time_desc ['ext_desc'] = $_lang['task_over_congra_witkey']; 
				break;
			case "9" : 
				$time_desc ['ext_desc'] = $_lang['pity_task_fail']; 
				break;
			case "10":
				$time_desc ['ext_desc'] =$_lang['fail_audit_please_repub']; 
				break;
			case "11" : 
				$time_desc ['ext_desc'] = $_lang['wait_for_task_arbitrate'];
				break;
		}
		return $time_desc;
	}
	public function get_work_info($w = array(), $order = null, $p = array()) {
		global $kekezu, $_K, $uid;
		$work_arr = array ();
		$sql = " select a.*,c.*,b.seller_credit,b.seller_good_num,b.residency,b.seller_total_num,b.seller_level
				 from " . TABLEPRE . "witkey_task_work a left join " . TABLEPRE . "witkey_space b
				  on a.uid=b.uid left join " . TABLEPRE . "witkey_task_taobao_work c on a.work_id=c.work_id";
		$count_sql = " select count(a.work_id) from " . TABLEPRE . "witkey_task_work a left join " . TABLEPRE . "witkey_space b on a.uid=b.uid";
		$where = " where a.task_id = '$this->_task_id' ";
		if (! empty ( $w )) {
			$w ['user_type'] == 'my' and $where .= " and a.uid = '$this->_uid'";
			isset ( $w ['work_status'] ) and $where .= " and a.work_status = '" . intval ( $w ['work_status'] ) . "'";
		}
		$where .= "   order by (CASE WHEN  a.work_status!=0 THEN 100 ELSE 0 END) desc,a.work_id asc ";
		if (!empty($p)){
			$page_obj = $kekezu->_page_obj;
			$page_obj->setAjax ( 1 );
			$page_obj->setAjaxDom ( "gj_summery" );
			$count = intval ( db_factory::get_count ( $count_sql . $where ) );
			$pages = $page_obj->getPages ( $count, $p ['page_size'], $p ['page'], $p ['url'], $p ['anchor'] );
			$where .= $pages ['where'];
			$pages ['count'] = $count;
		}
		$work_info = db_factory::query ( $sql . $where );
		$work_info = kekezu::get_arr_by_key($work_info,'work_id');
		$work_arr ['work_info'] = $work_info;
		$work_arr ['pages'] = $pages;
		$work_ids = implode ( ',', array_keys ( $work_info ) );
		$work_ids && $uid == $this->_task_info ['uid'] and db_factory::execute ( 'update ' . TABLEPRE . 'witkey_task_work set is_view=1 where work_id in (' . $work_ids . ') and is_view=0' );
		return $work_arr;
	}
	public function get_weibo_info($platform, $call_back, $oauth_url) {
		global $_lang;
		$weibo_info_arr = array ();
		$weibo_info_arr ['reqiure'] = '';
		$weibo_login_class = new keke_oauth_login_class ( $platform );
		if ($platform && ! $_SESSION ['auth_' . $platform] ['last_key']) {
			$weibo_login_class->login ( $call_back, $oauth_url );
		} else {
			$weibo_info_arr ['user_info'] = $weibo_login_class->get_login_user_info (); 
		}
		$weibo_class = new keke_weibo_class ( $platform );
		$weibo_info_arr ['tips'] = $_lang ['public'];
		$weibo_info_arr ['content'] = $this->_task_info ['wb_content'];
		$weibo_info_arr ['content_img'] = $this->_task_info ['wb_img'];
		$weibo_info_arr ['reqiure'] = $_lang ['pub_zd_wb_'];
		if ($this->_task_info ['is_at'] == 1) {
			$weibo_info_arr ['user_fans'] = $weibo_class->get_followers_by_uid ( $weibo_info_arr ['user_info'] ['account'] ); 
			$weibo_info_arr ['reqiure'] .= '@' . $this->_task_info ['at_num'] . $_lang ['ge_friend_'];
		}
		$weibo_info_arr ['reqiure'] = rtrim ( $weibo_info_arr ['reqiure'], ',' );
		return $weibo_info_arr;
	}
	static function get_affect($user_info, $platform, &$idata = array(), $create_days = '') {
		if ($platform == 'ten') {
			return self::get_ten_affect ( $user_info, $idata );
		}
		$follows = $user_info ['fans_count'];
		$bi_follows = $user_info ['hf_count'];
		$focus = $user_info ['gz_count'];
		$weibo = $user_info ['wb_count'];
		$favour = $user_info ['faver_count'];
		if ($create_days == '') {
			$days = floor ( time () - $user_info ['create_at'] ) / (60 * 60 * 24); 
		}
		$days = max ( ( int ) $days, ( int ) $create_days );
		$coef_init = array (array (1.01, 10.02, 10.04, 10.95 ), 
array (1.01, 8.12, 8.04, 8.95 ), 
array (2.01, 4.04, 3.04, 1.95 ) ); 
		if ($days <= 30) {
			$coef = $coef_init ['0'];
		} else if ($days > 30 && $days <= 100) {
			$coef = $coef_init ['1'];
		} else if ($days > 100) {
			$coef = $coef_init ['2'];
		} else {
			return false;
		}
		$fugai = round ( ((( int ) $follows / 8289) + ( int ) $bi_follows * 1.8 + ( int ) $weibo * 1.46) / $coef ['1'] ); 
		$huoyue = round ( ((( int ) $bi_follows / 1.48) + ( int ) $focus / ( int ) $days * 0.26 + ( int ) $favour * 1.26) / $coef ['2'] ); 
		$chuanbo = round ( ((( int ) $follows / 7748) + ( int ) $bi_follows * 1.46 + ( int ) $weibo / ( int ) $days * 1.85) / $coef ['3'] ); 
		$idata = array ($fugai, $huoyue, $chuanbo );
		$affect = round ( (($fugai / 8.4) + $huoyue * 1.13 + $chuanbo * 0.894) / $coef ['0'] ); 
		return $affect;
	}
	static function get_ten_affect($user_info, &$idata) {
		$follows = $user_info ['fans_count'];
		$focus = $user_info ['gz_count'];
		$weibo = $user_info ['wb_count'];
		$isvip = ( int ) $user_info ['isvip'];
		$fans_interval = array (100, 50, 10, 1, 0 ); 
		$fans_coef_init = array (6223, 1428, 628, 58, 38 ); 
		while ( list ( $k, $v ) = each ( $fans_interval ) ) {
			if ($follows > ($v * 1000)) {
				$fans_coef = $fans_coef_init [$k];
				break;
			}
		}
		$weibo_interval = array (3000, 1000, 300, 100, 50, 0 ); 
		$weibo_coef_init = array (1.21, 1.14, 1.02, 0.93, 0.62, 0.33 ); 
		while ( list ( $key, $value ) = each ( $weibo_interval ) ) {
			if ($weibo > $value) {
				$weibo_coef = $weibo_coef_init [$key];
				break;
			}
		}
		$affect = round ( ($weibo * 1.03 + $focus * 0.86 + $follows / $fans_coef + $isvip * 100) / 3.8 * $weibo_coef );
		$fugai = round ( $affect * 0.188 ); 
		$huoyue = round ( $affect * 0.524 ); 
		$chuanbo = round ( $affect * 0.288 ); 
		$idata = array ($fugai, $huoyue, $chuanbo );
		return $affect;
	}
	static function get_affect_level($affect, $config) {
		if (! isset ( $affect ) || ! $config) { 
			return false;
		}
		if ($affect <= $config ['1'] ['max']) {
			return ( int ) 1;
		}
		while ( list ( $k, $v ) = each ( $config ) ) {
			if ($v ['min'] <= $affect && $affect <= $v ['max']) {
				return ( int ) $k;
			}
		}
		if ($affect > $config [sizeof ( $config )] ['max']) {
			return sizeof ( $config );
		}
	}
	public function post_wb($platform, $at = array(), $jump_url = '') {
		global $_lang, $_K;
		$t_info = $this->_task_info;
		$content = $t_info ['wb_content'] . '。';
		$url = $this->_task_url . "&op=work_hand&platform=" . $platform . "&step=2";
		if ($t_info ['is_at'] == 1) { 
			sizeof ( $at ) >= $t_info ['at_num'] or kekezu::show_msg ( '操作提示', $url, 3, '您必须@' . $t_info ['at_num'] . '人', 'warning' );
			foreach ( $at as $v ) {
				$content .= '@' . $v . ' /';
			}
			$content = rtrim ( $content, '/' );
		}
		$content .= ' ' . $jump_url;
		$wl_obj = new keke_oauth_login_class ( $platform );
		$info = $wl_obj->get_login_user_info (); 
		$wb_obj = new keke_weibo_class ( $platform );
		$wb_sid = $wb_obj->post_wb ( $content, $t_info ['wb_img'] );
		$wb_sid or kekezu::show_msg ( $_lang ['operate_notice'], $jump_url, 2, $_lang ['wb_pub_error_repeat_pub_again'], 'warning' );
		return array ('wb_sid' => $wb_sid, 'info' => $info, 'con' => $content );
	}
	public function work_hand($work_desc, $hdn_att_file, $hidework = '2', $url = '', $output = 'normal') {
		global $_K, $_lang;
		$platform = $work_desc; 
		$at = $hdn_att_file; 
		kekezu::check_login ();
		$this->check_if_can_hand (); 
		$res = $this->check_work_times ( $platform ); 
		switch ($this->_assign) { 
			case "1" : 
				$work_id = $this->affect_work_hand ( $platform, $at );
				break;
			case "2" : 
				$work_id = $this->click_work_hand ( $platform, $at );
				break;
		}
		if ($work_id) {
			$this->plus_work_num (); 
			kekezu::show_msg ( $_lang ['operate_notice'], $this->_task_url . '&view=work&work_id=' . $work_id, 2, $_lang ['congratulate_you_hand_work_success'], 'success' ); 
		} else {
			kekezu::show_msg ( $_lang ['operate_notice'], $this->_task_url, 2, $_lang ['pity_hand_work_fail'], 'warning' );
		}
	}
	public function format_wb_data($platform, $wb_info) {
		$wb_sid = $wb_info ['wb_sid']; 
		$wb_content = $wb_info ['con']; 
		$u_info = $wb_info ['info']; 
		$exist_days = floor ( time () - $u_info ['create_at'] ) / (60 * 60 * 24); 
		$params = array ();
		$affect = self::get_affect ( $u_info, $platform, $params, $exist_days );
		$wb_leve = self::get_affect_level ( $affect, $this->_task_config [$platform . '_affect_rule'] );
		$wb_url = keke_weibo_class::build_wb_url ( $platform, $u_info ['account'], $wb_sid );
		$wb_data = serialize ( $u_info );
		return array ('fans' => $u_info ['fans_count'], 'hf_num' => $u_info ['fans_count'], 'focus_num' => $u_info ['gz_count'], 'wb_num' => $u_info ['wb_count'], 'faver_num' => $u_info ['faver_count'], 'create_day' => $exist_days, 'fgd_num' => $params ['0'], 'hyd_num' => $params ['1'], 'cbd_num' => $params ['2'], 'yxl_num' => $affect, 'wb_leve' => $wb_leve, 'wb_url' => $wb_url, 'wb_account' => $u_info ['account'], 'wb_sid' => $wb_sid, 'wb_data' => $wb_data );
	}
	public function affect_work_hand($platform, $at = array()) {
		global $_K, $_lang;
		$wb_info = $this->post_wb ( $platform, $at );
		$work_obj = keke_table_class::get_instance ( "witkey_task_work" );
		$data_1 = array ('task_id' => $this->_task_id, 'uid' => $this->_uid, 'username' => $this->_username, 'work_title' => $this->_task_title . $_lang ['de_work'], 'work_desc' => $wb_info ['con'], 'work_time' => time () );
		$work_id = $work_obj->save ( $data_1 );
		$tao_obj = keke_table_class::get_instance ( "witkey_task_taobao_work" );
		$data_2 = array ('task_id' => $this->_task_id, 'work_id' => $work_id, 'wb_type' => $platform );
		$wb_data = $this->format_wb_data ( $platform, $wb_info );
		$wb_data and $data_2 = array_merge ( $data_2, $wb_data );
		$tao_id = $tao_obj->save ( $data_2 );
		if (! $work_id || ! $tao_id ){
			return false;
		}
		if ($this->work_choose ( $work_id, '6' )) { 
			return $work_id;
		}
		return false;
	}
	public function check_work_times($platform) {
		global $_lang;
		$sql = " select count(a.tbwk_id) count from %switkey_task_taobao_work a left join
				%switkey_task_work b on a.work_id=b.work_id where b.uid='%d' and a.task_id='%d' and a.wb_type='%s'";
		$count = db_factory::get_count ( sprintf ( $sql, TABLEPRE, TABLEPRE, $this->_uid, $this->_task_id, $platform ) );
		intval ( $count ) and kekezu::show_msg ( $_lang ['operate_notice'], $this->_task_url, 2, '您已在此平台提交过稿件，无法再次交稿', 'warning' );
		return true;
	}
	private function according_affect_get_cash($affect_level) {
		$unit_price = unserialize ( $this->_task_info ['unit_price'] );
		return ( float ) $unit_price [( int ) $affect_level];
	}
	public function work_choose($work_id, $to_status, $url = '', $output = 'normal', $trust_response = false) {
		global $kekezu, $_K, $_lang;
		if (! $this->check_if_operated ( $work_id, $to_status, $url, $output ) || ! $this->check_if_can_choose ()) { 
			return false;
		}
		$status_arr = $this->get_work_status ();
		$sql = "select a.*,b.wb_leve,b.get_cash from %switkey_task_work a left join
				%switkey_task_taobao_work b on a.work_id=b.work_id where a.work_id='%d'";
		$work_info = db_factory::get_one ( sprintf ( $sql, TABLEPRE, TABLEPRE, $work_id ) );
		if ($work_info ['work_status'] != 0) { 
			return false;
		}
		if (! $this->set_work_status ( $work_id, $to_status )) { 
			return false;
		}
		$url = '<a href ="' . $this->_task_url . '">' . $this->_task_title . '</a>';
		if ($to_status == 6) { 
			$single_cash = $this->according_affect_get_cash ( $work_info ['wb_leve'] ); 
			$syje = floatval ( $this->_task_info ['task_cash'] - $this->_task_info ['pay_amount'] );
			$syje <= $single_cash and $single_cash = $syje; 
			$profit_cash = $single_cash * floatval ( $this->_task_info ['profit_rate'] / 100 ); 
			$real_cash = $single_cash - $profit_cash; 
			$data = array (':task_id' => $this->_task_id, ':task_title' => $this->_task_title );
			keke_finance_class::init_mem ( 'task_bid', $data );
			keke_finance_class::cash_in ( $work_info ['uid'], $real_cash, 0, 'task_bid', '', 'task', $this->_task_id, $profit_cash );
			db_factory::execute ( sprintf ( "update %switkey_task_taobao set pay_amount=pay_amount+'%.3f',sy_amount=sy_amount-'%.3f' where task_id='%d'", TABLEPRE, $single_cash, $single_cash, $this->_task_id ) );
			db_factory::execute ( sprintf ( "update %switkey_task_taobao_work set get_cash='%.3f' where work_id='%d'", TABLEPRE, $real_cash, $work_id ) );
			db_factory::execute ( sprintf ( "update %switkey_task_work set work_price='%.3f' where work_id='%d'", TABLEPRE, $real_cash, $work_id ) );
			$pay_amount = floatval ( $this->_task_info ['pay_amount'] ) + $single_cash;
			if ($pay_amount >= $this->_task_info ['task_cash']) {
				if ($this->set_task_status ( 8 )) {
					if ($this->_task_info ['task_union'] == 2) {
						$bid_uid = array ();
						$ids = db_factory::query ( 'select uid from ' . TABLEPRE . 'witkey_task_work where work_status=6 and task_id=' . $this->_task_id );
						foreach ( $ids as $v ) {
							$bid_uid [] = $v ['uid'];
						}
					}
					$v_arr = array ($_lang ['username'] => $this->_gusername, $_lang ['model_name'] => $this->_model_name, $_lang ['task_id'] => $this->_task_id, $_lang ['task_title'] => $this->_task_title );
					keke_msg_class::notify_user ( $this->_guid, $this->_gusername, 'task_over', $_lang ['task_over_notice'], $v_arr );
				}
			}
			$feed_arr = array ("feed_username" => array ("content" => $work_info ['username'], "url" => "index.php?do=seller&id={$work_info['uid']}" ), "action" => array ("content" => $_lang ['success_bid_haved'], "url" => "" ), "event" => array ("content" => "$this->_task_title", "url" => "index.php?do=task&id=$this->_task_id", 'cash' => $real_cash ) );
			kekezu::save_feed ( $feed_arr, $work_info ['uid'], $work_info ['username'], 'work_accept', $this->_task_id );
			$this->plus_accepted_num ( $work_info ['uid'] );
			$v = array ($_lang['work_status']=>$status_arr[$to_status],$_lang ['username'] => $work_info ['username'], $_lang ['website_name'] => $kekezu->_sys_config ['website_name'], $_lang ['task_id'] => "#" . $this->_task_id, $_lang ['task_title'] => $url, $_lang ['bid_cash'] => $single_cash );
			$this->notify_user ( "task_bid", $_lang ['work_bid'], $v, '1', $work_info ['uid'] );
			return true;
		}
	}
	public function click_work_hand($platform, $at) {
		global $_K, $_lang;
		$pass = false;
		$work_obj = keke_table_class::get_instance ( "witkey_task_work" );
		$data_1 = array ('task_id' => $this->_task_id, 'uid' => $this->_uid, 'username' => $this->_username, 'work_title' => $this->_task_title . $_lang ['de_work'], 'work_status' => 6, 'work_time' => time () );
		$work_id = $work_obj->save ( $data_1 );
		$tao_obj = keke_table_class::get_instance ( "witkey_task_taobao_work" );
		$data_2 = array ('task_id' => $this->_task_id, 'work_id' => $work_id, 'wb_type' => $platform, 'ip' => kekezu::get_ip () );
		$tao_id = $tao_obj->save ( $data_2 );
		if ($work_id && $tao_id) {
			$j_url = $this->_task_url . "&op=wb_cl&w_id={$work_id}";
			$wb_info = $this->post_wb ( $platform, $at, $j_url );
			if ($wb_info) { 
				$work_obj->save ( array ('work_desc' => $wb_info ['con'] ), array ('work_id' => $work_id ) ); 
				$wb_data = $this->format_wb_data ( $platform, $wb_info );
				$wb_data ['jump_url'] = $j_url;
				$tao_obj->save ( $wb_data, array ('tbwk_id' => $tao_id ) );
				$this->plus_accepted_num ( $this->_uid ); 
				$pass = $work_id;
			} else { 
				$work_obj->del ( 'work_id', $work_id );
				$tao_obj->del ( 'tbwk_id', $tao_id );
			}
		}
		return $pass;
	}
	public function wb_click($work_id) {
		global $uid;
		$task_info = $this->_task_info; 
		$ip = kekezu::get_ip (); 
		$sql = " select a.work_id,a.uid,a.username,b.tbwk_id,b.wb_type,b.ip,c.prom_url,c.taobao_id
				,c.pay_amount,c.sy_amount,c.unit_price from %switkey_task_work a left join %switkey_task_taobao_work b on
				a.work_id=b.work_id left join %switkey_task_taobao c on a.task_id=c.task_id
				 where a.work_id='%d'";
		$tao_info = db_factory::get_one ( sprintf ( $sql, TABLEPRE, TABLEPRE, TABLEPRE, $work_id ) );
		if ($tao_info) {
			if ($uid != $tao_info ['uid'] && $ip != $tao_info ['ip'] && $tao_info ['pay_amount'] < $task_info ['task_cash']) {
				$refer_url = $_SERVER ['HTTP_REFERER'];
				$user_agent = $_SERVER ['HTTP_USER_AGENT'];
				switch ($tao_info ['wb_type']) {
					case "sina" :
						$refer_true = strpos ( $refer_url, "weibo.com" );
						break;
					case "ten" :
						$refer_true = strpos ( $refer_url, "t.qq.com" );
						break;
				}
				if ($refer_true !== FALSE && $user_agent) { 
					$dj_info = db_factory::get_one ( sprintf ( " select refer_url,user_ip,user_agent,click_time from %switkey_task_taobao_views where work_id='%d' and user_ip='%s' order by click_time desc limit 0,1", TABLEPRE, $work_id, $ip ) );
					if ($dj_info) { 
						$this->check_ip_and_agent ( $dj_info ['click_time'], $dj_info ['user_agent'], $user_agent, false ) and $this->create_tao_views ( $tao_info, $refer_url, $ip, $user_agent );
					} else {
						$dj_info = db_factory::get_one ( sprintf ( " select refre_url,user_ip,user_agent,click_time from %switkey_task_wbdj_views where work_id='%d' and user_agent='%s' order by click_time desc limit 0,1", TABLEPRE, $work_id, $user_agent ) );
						if ($dj_info) { 
							$this->check_ip_and_agent ( $dj_info ['click_time'], $dj_info ['user_ip'], $ip ) and $this->create_tao_views ( $tao_info, $refer_url, $ip, $user_agent );
						} else { 
							$this->create_tao_views ( $tao_info, $refer_url, $ip, $user_agent );
						}
					}
				} else { 
					header ( "Location:" . $tao_info ['prom_url'] );
				}
			} else {
				header ( "Location:" . $task_info ['prom_url'] );
			}
		} else {
			header ( "Location:" . $task_info ['prom_url'] );
		}
	}
	public function verify_click() {
		$enough = true;
		$task_info = $this->_task_info;
		$ver1 = $task_info ['task_cash'] - $task_info ['pay_amount']; 
		$ver2 = $ver1 - $task_info ['unit_price']; 
		$ver1 > 0 && $ver2 >= 0 or $enough = false; 
		return $enough;
	}
	public function check_ip_and_agent($click_time, $ip, $compare_ip, $is_ip = true) {
		$time_diff = time () - $click_time - 24 * 3600;
		if ($time_diff > 0) {
			switch ($is_ip) {
				case true :
					if ($ip != $compare_ip) { 
						return true;
					} else {
						header ( "Location:" . $this->_task_info ['prom_url'] );
					}
					break;
				case false :
					if ($ip != $compare_ip) { 
						return true;
					} else {
						header ( "Location:" . $this->_task_info ['prom_url'] );
					}
					break;
			}
		} else {
			header ( "Location:" . $this->_task_info ['prom_url'] );
		}
	}
	public function create_tao_views($tao_info, $refer_url, $ip, $user_agent) {
		if ($this->verify_click ()) {
			$price = $tao_info ['unit_price']; 
			$real_price = $price * (1 - $this->_task_info ['profit_rate'] / 100); 
			db_factory::execute ( sprintf ( " update %switkey_task_taobao set pay_amount=pay_amount+'%s',sy_amount=sy_amount-'%s',click_count=click_count+1 where taobao_id='%d'", TABLEPRE, $price, $price, $tao_info ['taobao_id'] ) );
			db_factory::execute ( sprintf ( " update %switkey_task_taobao_work set click_num=click_num+1,get_cash=get_cash+'%s' where tbwk_id='%d'", TABLEPRE, $real_price, $tao_info ['tbwk_id'] ) );
			$views_obj = keke_table_class::get_instance ( "witkey_task_taobao_views" );
			$data = array ('task_id' => $this->_task_id, 'work_id' => $tao_info ['work_id'], 'tbwk_id' => $tao_info ['tbwk_id'], 'refer_url' => $refer_url, 'user_ip' => $ip, 'user_agent' => $user_agent, 'click_time' => time () );
			$res = $views_obj->save ( $data );
			$dispose = false;
		} else {
			$res = $dispose = true;
		}
		if ($res) { 
			$dispose and $this->dispose_task (); 
			header ( "Location:" . $tao_info ['prom_url'] );
		}
	}
	public function dispose_task() {
		global $kekezu, $_K, $_lang;
		$t_info = $this->_task_info;
		$prom_obj = 	$objProm = keke_prom_class::get_instance ();
		$profit_rate = $this->_profit_rate; 
		$url = '<a href ="' . $this->_task_url . '" target="_blank" >' . $this->_task_title . '</a>';
		if ($t_info ['task_status'] == '2') {
			$work_list = $this->get_task_work ( 6 ); 
			if (! empty ( $work_list )) {
				$price = $t_info ['unit_price'];
				$s = sizeof ( $work_list );
				$bid_uid = array ();
				for($i = 0; $i < $s; $i ++) {
					$v = $work_list [$i];
					if ($v ['click_num']) { 
						$profit = $profit_rate * $price / 100; 
						$get_cash = ($price - $profit) * $v ['click_num']; 
						$data = array (':task_id' => $this->_task_id, ':task_title' => $this->_task_title );
						keke_finance_class::init_mem ( 'task_bid', $data );
						keke_finance_class::cash_in ( $v ['uid'], $get_cash, 0, 'task_bid', '', 'task', $this->_task_id, $profit );
						$v = array ($_lang ['task_id'] => $this->_task_id, $_lang ['task_title'] => $url, $_lang ['task_status'] => $_lang ['task_over'], $_lang ['task_link'] => $url );
						$this->notify_user ( "dispose_task", $_lang ['work_cash_js_notice'], $v, '1', $v ['uid'] ); 
						$bid_uid [] = $v ['uid'];
					}
				}
			}
			$t_info ['sy_amount'] and $this->dispose_task_return (); 
			$this->set_task_status ( 8 ); 
			$objProm = keke_prom_class::get_instance ();
			$objProm->dispose_prom_event ( "pub_task", $this->_guid, $this->_task_id );
			$v = array ($_lang ['task_id'] => $this->_task_id, $_lang ['task_title'] => $url, $_lang ['task_status'] => $_lang ['over'], $_lang ['task_link'] => $url );
			$this->notify_user ( "dispose_task", $_lang ['task_over_notice'], $v, 2, $this->_guid ); 
		}
	}
	public function get_task_work($work_status = '', $work_id = '') {
		$sql = " select a.*,b.tbwk_id,b.click_num from %switkey_task_work a left join %switkey_task_taobao_work b
				on a.work_id=b.work_id where a.task_id = '%d' and a.work_status='%d'";
		return db_factory::query ( sprintf ( $sql, TABLEPRE, TABLEPRE, $this->_task_id, $work_status ) );
	}
	public function process_can() {
		$wiki_priv = $this->_priv; 
		$process_arr = array ();
		$status = intval ( $this->_task_status );
		$task_info = $this->_task_info;
		$config = $this->_task_config;
		$g_uid = $this->_guid;
		$uid = $this->_uid;
		$user_info = $this->_userinfo;
		switch ($status) {
			case "2" : 
				switch ($g_uid == $uid) { 
					case "1" :
						$process_arr ['tools'] = true; 
						$process_arr ['reqedit'] = true; 
						$process_arr ['work_comment'] = true; 
						break;
					case "0" : 
						$process_arr ['work_choose'] = true; 
						$process_arr ['work_hand'] = true; 
						$process_arr ['task_report'] = true; 
						break;
				}
				$process_arr ['work_report'] = true; 
				break;
			case "8" : 
				break;
		}
		$uid != $g_uid and $process_arr ['task_complaint'] = true; 
		$process_arr ['work_complaint'] = true; 
		if ($user_info ['group_id']) { 
			switch ($status) {
				case 1 : 
					$process_arr ['task_audit'] = true;
					break;
				case 2 : 
					$task_info['is_top'] or $process_arr ['task_recommend'] = true;
					$process_arr ['task_freeze'] = true;
					break;
				default :
					if ($status > 1 && $status < 8) {
						$process_arr ['task_freeze'] = true;
					}
			}
		}
		$this->_process_can = $process_arr;
		return $process_arr;
	}
	public function set_work_status($work_id, $to_status) {
		return db_factory::execute ( sprintf ( " update %switkey_task_work set work_status='%d' where work_id='%d'", TABLEPRE, $to_status, $work_id ) );
	}
	public function dispose_task_return() {
		global $kekezu, $_K;
		global $_lang;
		$config = $this->_task_config;
		$task_info = $this->_task_info;
		$task_cash = $task_info ['task_cash']; 
		$fail_rate = $this->_fail_rate; 
		$pay_amount = $task_info ['pay_amount']; 
		$remain_cash = $task_cash - $pay_amount; 
		$site_profit = $remain_cash * $fail_rate / 100; 
		if (intval($pay_amount)) { 
			$action = 'task_remain_return';
			switch ($config ['defeated']) {
				case "2" : 
					$return_cash = '0';
					$return_credit = $remain_cash - $site_profit; 
					break;
				case "1" : 
					$cash_cost = $task_info ['cash_cost']; 
					$credit_cost = $task_info ['credit_cost']; 
					if ($cash_cost >= $remain_cash) { 
						$return_cash = $remain_cash - $site_profit;
						$return_credit = '0';
					} else {
						if ($cash_cost) {
							$return_cash = $cash_cost * (1 - $fail_rate / 100); 
							$return_credit = ($remain_cash - $cash_cost) * (1 - $fail_rate / 100);
						} else {
							$return_cash = 0;
							$return_credit = $remain_cash * (1 - $fail_rate / 100);
						}
					}
					break;
			}
		} else { 
			$action = 'task_fail';
			switch ($config ['defeated']) {
				case "2" : 
					$return_cash = '0';
					$return_credit = $task_cash - $site_profit; 
					break;
				case "1" : 
					$cash_cost = $task_info ['cash_cost']; 
					$credit_cost = $task_info ['credit_cost']; 
					if ($cash_cost == $task_cash) { 
						$return_cash = $task_cash - $site_profit;
						$return_credit = '0';
					} elseif ($credit_cost == $task_cash) { 
						$return_cash = '0';
						$return_credit = $task_cash - $site_profit;
					} else {
						$return_cash = $cash_cost * (1 - $fail_rate / 100); 
						$return_credit = $credit_cost * (1 - $fail_rate / 100);
					}
					break;
			}
		}
		$data = array (':model_name' => $this->_model_name, ':task_id' => $this->_task_id, ':task_title' => $this->_task_title );
		keke_finance_class::init_mem ( $action, $data );
		$res = keke_finance_class::cash_in ( $this->_task_info['uid'], $return_cash, floatval ( $return_credit ) + 0, $action, '', 'task', $this->_task_id, $site_profit );
		if ($res) {
			$url = '<a href ="' . $this->_task_url . '" target="_blank" >' . $this->_task_title . '</a>';
			if (! intval($pay_amount)) {
				$this->set_task_status ( 9 ); 
				$v = array ($_lang ['task_id'] => $this->_task_id, $_lang ['task_title'] => $url, $_lang ['task_status'] => $_lang ['fail'], $_lang ['task_link'] => $url );
				$this->notify_user ( "dispose_task", $_lang ['task_fail_notice'], $v, 2, $this->_guid ); 
			} else {
				$this->set_task_status ( 8 ); 
				$v = array ($_lang ['task_id'] => $this->_task_id, $_lang ['task_title'] => $url, $_lang ['task_status'] => $_lang ['task_over'], $_lang ['task_link'] => $url );
				$this->notify_user ( "dispose_task", $_lang ['task_remain_cash_return'], $v, 2, $this->_guid ); 
			}
		}
	}
	public function time_hand_end() {
		if ($this->_task_status == 2 && $this->_task_info ['end_time'] < time ()) 
			if ($this->_task_info ['work_num']) {
				$this->dispose_task (); 
			} else {
				$this->dispose_task_return (); 
			}
	}
	public function check_if_operated($work_id, $to_status, $url = '', $output = 'normal') {
		$can_select = false; 
		if (! $this->check_if_can_choose ( $url, $output )) { 
			return false;
		}
		$work_status = db_factory::get_count ( sprintf ( " select work_status from %switkey_task_work where work_id='%d'
					 and uid='%d'", TABLEPRE, $work_id, $this->_uid ) );
		if ($work_status != 0) { 
			return false;
		}
		if (! $to_status) {
			return false;
		}
		if (! in_array ( $to_status, array (6, 7, 8 ) )) {
			return false;
		} 
		return true;
	}
	public static function get_task_status() {
		global $_lang;
		return array ("0" => $_lang ['task_no_pay'], "1" => $_lang ['task_wait_audit'], "2" => $_lang ['task_vote_choose'], "3" => $_lang ['task_choose_work'], "7" => $_lang ['freeze'], "8" => $_lang ['task_over'], "9" => $_lang ['fail'], "10" => $_lang ['task_audit_fail']);
	}
	public static function get_work_status() {
		global $_lang;
		return array ("0" => $_lang ['default'], '6' => $_lang ['hg'], '7' => $_lang ['not_recept'], '8' => $_lang ['task_can_not_choose_bid'] );
	}
	public function dispose_order($order_id) {
		global $kekezu, $_K, $_lang;
		$task_config = $this->_task_config;
		$task_info = $this->_task_info; 
		$url = $_K ['siteurl'] . '/index.php?do=task&id=' . $this->_task_id;
		$task_status = $this->_task_status;
		$order_info = db_factory::get_one ( sprintf ( "select order_amount,order_status from %switkey_order where order_id='%d'", TABLEPRE, intval ( $order_id ) ) );
		$order_amount = $order_info ['order_amount'];
		if ($order_info ['order_status'] == 'ok') {
			$task_status == 1 && $notice = $_lang ['task_pay_success_and_wait_admin_audit'];
			$task_status == 2 && $notice = $_lang ['task_pay_success_and_task_pub_success'];
			return pay_return_fac_class::struct_response ( $_lang ['operate_notice'], $notice, $url, 'success' );
		} else {
			$data = array (':model_name' => $this->_model_name, ':task_id' => $this->_task_id, ':task_title' => $this->_task_title );
			keke_finance_class::init_mem ( 'pub_task', $data );
			$order_amount>0 and $res = keke_finance_class::cash_out ( $task_info ['uid'], $order_amount, 'pub_task' ); 
			kekezu::save_weibo_data($task_info['task_id'],'task',$kekezu->_model_list[$task_info[model_id]]['model_code'],$task_info['task_title'],$task_info['uid'],$task_info['username'],0,0,0,'pub',$task_info['task_cash'],$uid,$username);
			switch ($res == true) {
				case "1" : 
					$feed_arr = array ("feed_username" => array ("content" => $task_info ['username'], "url" => "index.php?do=seller&id={$task_info['uid']}" ), "action" => array ("content" => $_lang ['pub_task'], "url" => "" ), "event" => array ("content" => "{$task_info['task_title']}", "url" => "index.php?do=task&id={$task_info['task_id']}" ) );
					kekezu::save_feed ( $feed_arr, $task_info ['uid'], $task_info ['username'], 'pub_task', $task_info ['task_id'] );
					$consume = kekezu::get_cash_consume ( $task_info ['task_cash'] );
					db_factory::execute ( sprintf ( " update %switkey_task set cash_cost='%s',credit_cost='%s' where task_id='%d'", TABLEPRE, $consume ['cash'], $consume ['credit'], $this->_task_id ) );
					db_factory::updatetable ( TABLEPRE . "witkey_order", array ("order_status" => "ok" ), array ("order_id" => "$order_id" ) );
					if ($order_amount < $task_config ['audit_cash']) { 
						$this->set_task_status ( 1 ); 
						return pay_return_fac_class::struct_response ( $_lang ['operate_notice'], $_lang ['task_pay_success_and_wait_admin_audit'], $url, 'success' );
					} else {
						$this->set_task_status ( 2 ); 
						return pay_return_fac_class::struct_response ( $_lang ['operate_notice'], $_lang ['task_pay_success_and_task_pub_success'], $url, 'success' );
					}
					break;
				case "0" : 
					$pay_url = $_K ['siteurl'] . "/index.php?do=pay&order_id=$order_id"; 
					return pay_return_fac_class::struct_response ( $_lang ['operate_notice'], $_lang ['task_pay_error_and_please_repay'], $pay_url, 'warning' );
					break;
			}
		}
	}
}