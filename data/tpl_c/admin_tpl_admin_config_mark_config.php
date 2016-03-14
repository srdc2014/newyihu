<?php keke_tpl_class::checkrefresh('admin/tpl/admin_config_mark_config', '1457949925' );?><!DOCTYPE HTML>
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
<h1><?php echo $_lang['mutual_evaluation_list'];?></h1>
</div>
<div class="box tip clearfix p_relative" id="man_tips">
    	<div class="control"><a title=<?php echo $_lang['close'];?> href="javascript:void(0);"><b>×</b></a></div>
        <div class="title"><h2><?php echo $_lang['tips'];?></h2></div>
        <div class="detail pad10">
            <ul>
            	<li><?php echo $_lang['mark_tips'];?></li>
            </ul>
        </div>
</div>

<div class="box list">
    	<div class="title"><h2><?php echo $_lang['credit_rules_list'];?></h2></div>
        <div class="detail">
<form method="get" action="<?php echo $url;?>" id="frm_user_search">
  		<table cellpadding="0" cellspacing="0">
  		  <tbody>
          <tr>
            <th><?php echo $_lang['id'];?></th>
<th><?php echo $_lang['object'];?></th>
<th><?php echo $_lang['good_value'];?></th>
<th><?php echo $_lang['middle_value'];?></th>
<th><?php echo $_lang['bad_value'];?></th>
<th><?php echo $_lang['role'];?></th>
<th><?php echo $_lang['operate'];?></th>
          </tr>
<?php if(is_array($mark_config_arr)) { foreach($mark_config_arr as $k => $v) { ?>
        <tr class="item">
            <td><?php echo $v['mark_config_id'];?></td>
            <td><?php echo $model_list2[$v['obj']];?></td>
<td><?php echo $v['good'];?>%</td>
            <td><?php echo $v["normal"];?>%</td>
<td><?php echo $v["bad"];?>%</td>
<td><?php echo $juese[$v["type"]];?></td>
<td>
<a class="button dbl_target" href="index.php?do=<?php echo $do;?>&view=<?php echo $view;?>&op=config_add&mark_config_id=<?php echo $v['mark_config_id'];?>"><span class="pen icon"></span><?php echo $_lang['edit'];?></a>
<a class="button" href="index.php?do=<?php echo $do;?>&view=<?php echo $view;?>&op=<?php echo $op;?>&ac=del&mark_config_id=<?php echo $v['mark_config_id'];?>" onclick="return cdel(this);"><span class="trash icon"></span><?php echo $_lang['delete'];?></a>
</td>
          </tr>
 <?php } } ?>
   		</tbody>
        </table>
</form>
        </div>
</div>
<script type="text/javascript">
$(function(){
$(".control").add(".title").click(function(){
$(".tip").children().not($(".control,.title")).slideToggle('800');
});
})
</script>
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