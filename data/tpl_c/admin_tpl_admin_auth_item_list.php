<?php keke_tpl_class::checkrefresh('admin/tpl/admin_auth_item_list', '1457949930' );?><!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_K['charset'];?>">
<title> admin</title>


<link href="tpl/css/admin_management.css" rel="stylesheet" type="text/css" />
<link href="tpl/css/button/stylesheets/css3buttons.css" rel="stylesheet" type="text/css" />
<link title="style1" href="tpl/skin/default/style.css" rel="stylesheet" type="text/css" />
<!--<link title="style2" href="tpl/skin/light/style.css" rel="stylesheet" type="text/css" />-->
<!-- <link href="tpl/js/jquery.qtip.min.css" rel="stylesheet" type="text/css" /> -->
<script type="text/javascript" src="tpl/js/jquery.js"></script>
<script type="text/javascript" src="tpl/js/keke.js"></script>
</head>
<body class="frame_body">
<div class="frame_content">
<div id="append_parent"></div> 

<div class="page_title"><h1><?php echo $_lang['page_title'];?></h1></div>
<!--页头结束-->

<div class="box list">
    <div class="detail">
    	<form method="post" action="index.php?do=<?php echo $do;?>&view=<?php echo $view;?>" name="frm_art_search" id="frm_art_search">
    	<div id="ajax_dom">
    	<input type="hidden" name="page" value="<?php echo $page;?>" />
    	<table cellspacing="0" cellpadding="0">
    	  <tbody>
          <tr>
            <th><?php echo $_lang['auth_logo'];?></th>
<th><?php echo $_lang['auth_name'];?></th>
<th><?php echo $_lang['auth_audit_days'];?></th>
<!--<th><?php echo $_lang['auth_cash'];?></th>-->
<th><?php echo $_lang['auth_period'];?></th>
<th><?php echo $_lang['is_open'];?></th>
<th><?php echo $_lang['last_update'];?></th>
<th><?php echo $_lang['operate'];?></th>
          </tr>
  <?php if(is_array($auth_item_arr)) { foreach($auth_item_arr as $key => $value) { ?>
 <tr class="item">
<td><?php echo $value['auth_code'];?></td>
<td><?php echo $value['auth_title'];?></td>
<td><?php echo $value['auth_day'];?><?php echo $_lang['workdays'];?></td>
<!--<td><font color="#ff7700"><?php if($value['auth_code'] =='enterprise' || $value['auth_code'] =='realname') { ?>免费认证<?php } else { ?><?php  echo keke_curren_class::output(floatval($value['auth_cash']),-1);  ?><?php } ?></font></td>-->
<td><?php if($value['auth_expir']) { ?><?php echo $value['auth_expir'];?><?php echo $_lang['day'];?><?php } else { ?><?php echo $_lang['no_limit'];?><?php } ?></td>
<td><?php if($value['auth_open']==1) { ?><font color="green"><?php echo $_lang['open'];?></font><?php } else { ?><font color="gray"><?php echo $_lang['close'];?></font><?php } ?></td>
<td><?php if($value['update_time']){echo date('Y-m-d H:i:s',$value['update_time']); } ?></td>
<td>
<?php if($value['auth_open']==1) { ?>
<a class="button" href="index.php?do=auth&view=item_edit&sbt_edit=1&code=<?php echo $value['auth_code'];?>&pk[auth_code]=<?php echo $value['auth_code'];?>&fds[auth_open]=0"><span class="cross icon"></span><?php echo $_lang['disable'];?></a>
<?php } else { ?>
<a class="button" href="index.php?do=auth&view=item_edit&sbt_edit=1&code=<?php echo $value['auth_code'];?>&pk[auth_code]=<?php echo $value['auth_code'];?>&fds[auth_open]=1"><span class="check icon"></span><?php echo $_lang['use'];?></a>
<?php } ?>
<a class="button dbl_target" href="index.php?do=<?php echo $do;?>&view=item_edit&code=<?php echo $value['auth_code'];?>"><span class="pen icon"></span><?php echo $_lang['edit'];?></a>
<a class="button" href="index.php?do=<?php echo $do;?>&view=<?php echo $view;?>&ac=del&code=<?php echo $value['auth_code'];?>" onclick="return cdel(this);"><span class="trash icon"></span><?php echo $_lang['delete'];?></a>
</td>
</tr>
<?php } } ?>

          <tr>
          	<td colspan='11'>
          		
<div class="clearfix">

<span><?php echo $_lang['enter_dirname'];?>:</span>
<input  type="text"   name="auth_dir"   id="auth_dir"    class="txt"/>&nbsp;
<button type="submit" name="sbt_add"    value="<?php echo $_lang['install_module'];?>" class="pill positive"><span class="check icon">&nbsp;</span><?php echo $_lang['install_module'];?></button>
</div>
          	</td>
         </tr>
 
 </tbody>
        </table>
<div class="page"><?php echo $pages['page'];?></div>
</div>
</form>

    </div>
</div>
<!--主体结束-->

</div>
<script type="text/javascript" src="../lang/<?php echo $language?>/script/lang.js"></script>
<script type="text/javascript" src="tpl/js/form_and_validation.js"></script>
<!-- <script type="text/javascript" src="tpl/js/jquery.qtip.min.js"></script> -->
<script type="text/javascript" src="tpl/js/script_calendar.js"></script>
<script type="text/javascript" src="tpl/js/artdialog/artDialog.js"></script>
<script type="text/javascript" src="tpl/js/artdialog/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="tpl/js/artdialog/artDialog.iframeTools.source.js"></script>
<script type="text/javascript" src="tpl/js/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="tpl/js/styleswitch.js"></script>
<script type="text/javascript" src="tpl/js/table.js"></script>
<script type="text/javascript">
var sessionId = "<?php echo $xyq = session_id(); ?>";
function cdel(o, s) {
d = art.dialog;
var c = "<?php echo $_lang['you_comfirm_delete_operate'];?>";
if (s) {
c = s;
}
d.confirm(c, function() {
window.location.href = o.href;
});
return false;
}
function cpass(o, s, type,pay=1) {
d = art.dialog;
if (type == 1) {
if(pay){
var c = "确认审核通过？";
}else{
var c = "<font color='red'>该任务尚未付款</font>,确认审核通过？";
}
} else {
var c = "确认审核失败？";
}
if (s) {
c = s;
}
d.confirm(c, function() {
window.location.href = o.href;
});
return false;
}
function cfreeze(o, s, type) {
d = art.dialog;
if (type == 1) {
var c = "确认冻结？";
}
if (s) {
c = s;
}
d.confirm(c, function() {
window.location.href = o.href;
});
return false;
}
function crecomm(o, s, type) {
d = art.dialog;
if (type == 1) {
var c = "确认推荐？";
} else {
var c = "确认取消推荐？";
}
if (s) {
c = s;
}
d.confirm(c, function() {
window.location.href = o.href;
});
return false;
}
function pdel(frm) {
d = art.dialog;
var frm = frm;
var c = "<?php echo $_lang['you_comfirm_delete_operate'];?>";
d.confirm(c, function() {
$("#" + frm).submit();
});
return false;
}
function fdel(frm) {
d = art.dialog;
var frm = frm;
var c = "<?php echo $_lang['you_comfirm_delete_operate'];?>";
d.confirm(c, function() {
$("#" + frm).submit();
});
return false;
}
function batch_act(obj, frm) {
d = art.dialog;
var frm = frm;
var c = $(obj).val();
var conf = $(":checkbox[name='ckb[]']:checked").length;
if (conf > 0) {
d.confirm("<?php echo $_lang['confirm'];?>" + c + '?', function() {
$(".sbt_action").val(c);
$("#" + frm).submit();
});
} else {
d.alert("<?php echo $_lang['has_none_being_choose'];?>");
}
return false;
}
</script>
<!-- <?php if(KEKE_DEBUG) { ?>
<div
style="background-color: red; color: #fff; width: 400px; text-align: center;">
querys:
{eval echo db_factory::create()->get_query_num()}
&nbsp; times:
{eval echo kekezu::execute_time()}
</div>

<?php } ?> -->
</body>
</html><?php keke_tpl_class::ob_out();?>