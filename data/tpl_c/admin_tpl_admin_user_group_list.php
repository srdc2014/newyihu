<?php keke_tpl_class::checkrefresh('admin/tpl/admin_user_group_list', '1457949919' );?><!DOCTYPE HTML>
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
<div class="page_title">
<h1><?php echo $_lang['system_group_manage'];?></h1>
   
     <div class="tool"> 
        <a href="index.php?do=user&view=group_list"      <?php if($view=='group_list') { ?>class="here"<?php } ?>><?php echo $_lang['user_group_manage'];?></a>
        <a href="index.php?do=user&view=group_add&op=add" <?php if($view=='group_add') { ?>class="here"<?php } ?>><?php echo $_lang['add_user_group'];?></a>
</div>
</div>
<!--页头结束-->


<div class="box list">
    	<div class="title"><h2><?php echo $_lang['user_group_manage'];?></h2></div>
        <div class="detail">
<form method="post" action="#" id="frm_user_search">
  		<table cellpadding="0" cellspacing="0">
  		  <tbody>
          <tr>
            <th><?php echo $_lang['id'];?></th>
<th><?php echo $_lang['group_name'];?></th>
<th><?php echo $_lang['update_time'];?></th>
<th><?php echo $_lang['operate'];?></th>
          </tr>
  
<?php if(is_array($grouplist_arr)) { foreach($grouplist_arr as $key => $value) { ?>
        <tr class="item">
            <td><?php echo $value['group_id']?></td>
            <td class="motif"><?php echo $value['groupname']?></td>
            <td><?php if($value['on_time']){echo date('Y-m-d H:i:s',$value['on_time']); } ?></td>
<td>
<a href="index.php?do=user&view=group_add&op=add&editgid=<?php echo $value['group_id'];?>&page=<?php echo $page;?>" class="button dbl_target"><span class="pen icon"></span><?php echo $_lang['edit'];?></a>
<a href="index.php?do=user&view=group_list&op=del&editgid=<?php echo $value['group_id'];?>&page=<?php echo $page;?>" onclick="return cdel(this);"class="button"><span class="trash icon"></span><?php echo $_lang['delete'];?></a>
</td>
          </tr>
 <?php } } ?>
          <tr>
          	<td colspan="4">
            <div class="clearfix">
            	<button type="button" name="sbt_add" value="<?php echo $_lang['add_user_group'];?>" class="positive pill button" onclick="document.location.href='index.php?do=user&view=group_add&op=add'"/><span class="check icon"></span><?php echo $_lang['add_user_group'];?></button>
</div>
</td>
          </tr>
  </tbody>
        </table>
<div class="page"><?php echo $pages['page'];?></div>
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
</html>


<?php keke_tpl_class::ob_out();?>