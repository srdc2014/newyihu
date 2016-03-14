<?php keke_tpl_class::checkrefresh('auth/email/admin/tpl/auth_list', '1457949937' );?><!DOCTYPE HTML>
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

<div class="page_title"><h1><?php echo $_lang['email_auth_list'];?></h1></div>
<!--页头结束-->


<div class="box search p_relative">
<div class="control">
   <a href="javascript:d();"><b>&times;</b></a>
</div>
<div class="title"><h2><?php echo $_lang['search'];?></h2></div>
<div class="detail" id="detail">
<form method="post" action="index.php?do=<?php echo $do;?>&view=<?php echo $view;?>&code=<?php echo $code?>&page=<?php echo $page;?>" id="frm_art_search">
<table cellspacing="0" cellpadding="0">
<tbody>
<tr>
<th><?php echo $_lang['auth_num'];?></th>
<td><input type="text" class="txt" name="w[email_a_id]" 	value="<?php echo $w['email_a_id'];?>" onkeyup="clearstr(this);"></td>
<th><?php echo $_lang['auth_user'];?></th>
<td><input type="text" class="txt" name='w[username]' value="<?php echo $w['username'];?>"></td>
</tr>
<tr>
<th><?php echo $_lang['list_result'];?></th>
<td>
<select name="w[page_size]">
<option value="10" <?php if($w['page_size']=='10') { ?>selected="selected"<?php } ?>><?php echo $_lang['page_size'];?>10</option>
<option value="20" <?php if($w['page_size']=='20') { ?>selected="selected"<?php } ?>><?php echo $_lang['page_size'];?>20</option>
<option value="30" <?php if($w['page_size']=='30') { ?>selected="selected"<?php } ?>><?php echo $_lang['page_size'];?>30</option>
</select>
<button class="pill" type="submit" value="<?php echo $_lang['search'];?>" name="sbt_search"><span class="icon magnifier">&nbsp;</span><?php echo $_lang['search'];?></button>
 </td>
 <th>&nbsp;</th>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
</form>
</div>
</div>
<!--搜索结束-->

<div class="box list">
    	<div class="title"><h2><?php echo $_lang['email_auth'];?></h2></div>
        <div class="detail">
        <form action="" id='frm_list' method="post">
        <div id="ajax_dom"><input type="hidden" name="page" value="<?php echo $page;?>" />
        <table cellspacing="0" cellpadding="0">
         <tbody>
          <tr>
          	<th width="15"><input type="checkbox" id="checkbox" onclick="checkall();"></th>
            <th><?php echo $_lang['auth_num'];?></th>
            <th><?php echo $_lang['user_id'];?></th>
            <th><?php echo $_lang['username'];?></th>
<th><?php echo $_lang['auth_email'];?></th>
<th><?php echo $_lang['auth_time'];?></th>
<th><?php echo $_lang['auth_status'];?></th>
<!--<th><?php echo $_lang['operate'];?></th>-->
 </tr>
  <?php if(is_array($email_arr)) { foreach($email_arr as $v) { ?>
<tr class="item">
<td><input type="checkbox" name="ckb[]" id="cbk_selected" value="<?php echo $v['email_a_id'];?>"></td>
<td><?php echo $v['email_a_id'];?></td>
<td><?php echo $v['uid'];?></td>
<td><?php echo $v['username'];?></td>
<td><?php echo $v['email'];?></td>
<td><?php echo date("Y-m-d H:i:s",$v[auth_time]) ?></td>
<td>
<?php if($v['auth_status']==1) { ?>
<font color="green"><?php echo $_lang['confirmed'];?></font>
<?php } else { ?>
<font color="red"><?php echo $_lang['wait_confirm'];?></font>
<?php } ?>	
<!--
<?php if($v['auth_status']==2||$v['auth_status']==0) { ?>
<a href="index.php?do=<?php echo $do?>&view=<?php echo $view?>&code=<?php echo $code?>&action=pass&auth_id=<?php echo $v['email_a_id']?>&euid=<?php echo $v['uid'];?>" onclick="return cdel(this,'<?php echo $_lang['confirm_pass'];?>')" class="button dbl_target"><span class="check icon"></span><?php echo $_lang['pass'];?></a>
<?php } ?>

<?php if($v['auth_status']==0) { ?>
<a href="index.php?do=<?php echo $do?>&view=<?php echo $view?>&code=<?php echo $code?>&action=not_pass&auth_id=<?php echo $v['email_a_id']?>" onclick="return cdel(this,'<?php echo $_lang['confirm_nopass'];?>')" class="button"><span class="cross icon"></span><?php echo $_lang['nopass'];?></a>
<?php } ?>
-->
<?php if($v['auth_status']!=1) { ?>
<a  href="index.php?do=<?php echo $do;?>&view=<?php echo $view;?>&code=<?php echo $code?>&ac=del&email_a_id=<?php echo $v['email_a_id'];?>&obj=<?php echo $v['username']?>" 	class="button"   onclick="return cdel(this);"><span class="trash icon"></span><?php echo $_lang['delete'];?></a>
<?php } ?>
   </td>
 
</tr>
 <?php } } ?>
 <tr>
         <td colspan="8">
        	 
 
 <label for="checkbox"><?php echo $_lang['select_all'];?></lable>　
 <input type="hidden" name="sbt_action" class="sbt_action"/>
 <button type="submit" name="sbt_action" value="<?php echo $_lang['mulit_delete'];?>"   class="pill negative" onclick="return batch_act(this,'frm_list');"><span class="icon trash"></span><?php echo $_lang['mulit_delete'];?></button>
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
<script language="javascript">
function d()
{
$("#frm_art_search").slideToggle('800');	  
}
</script>
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
</html>
<?php keke_tpl_class::ob_out();?>