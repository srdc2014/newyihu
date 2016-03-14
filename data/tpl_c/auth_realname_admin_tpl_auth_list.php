<?php keke_tpl_class::checkrefresh('auth/realname/admin/tpl/auth_list', '1457949935' );?><!DOCTYPE HTML>
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

<div class="page_title"><h1><?php echo $_lang['realname_auth_list'];?></h1></div>
<!--页头结束-->

<div class="box search p_relative">
<div class="control">
    <a href="javascript:d();"><b>&times;</b></a>
</div>
<div class="title"><h2><?php echo $_lang['realname_auth_list'];?></h2></div>
<div class="detail" id="detail">
 <form method="post" action="<?php echo $url;?>" id="frm_art_search">
<table cellspacing="0" cellpadding="0">
<tbody>
<tr>
<th><?php echo $_lang['auth_num'];?></th>
<td><input type="text" class="txt" name="w[realname_a_id]" 	value="<?php echo $w['realname_a_id'];?>" onkeyup="clearstr(this);"></td>
<th><?php echo $_lang['auth_user'];?></th>
<td><input type="text" class="txt" name='w[username]' value="<?php echo $w['username'];?>"></td>
</tr>
<tr>
<th><?php echo $_lang['auth_status'];?></th>
<td>
<select name="w[auth_status]">
 <option value=""><?php echo $_lang['select_all'];?></option>
 <option value="1" <?php if($w['auth_status']=='1') { ?> selected="selected" <?php } ?>><?php echo $_lang['passed'];?></option>
 <option value="2" <?php if($w['auth_status']=='2') { ?> selected="selected" <?php } ?>><?php echo $_lang['nopassed'];?></option>
 <option value="0" <?php if($w['auth_status']==='0') { ?> selected="selected" <?php } ?>><?php echo $_lang['wait_audit'];?></option>
</select>
</td>
<th><?php echo $_lang['list_result'];?></th>
<td>
<select name="w[page_size]">
<option value="10" <?php if($w['page_size']=='10') { ?>selected="selected"<?php } ?>><?php echo $_lang['page_size'];?>10</option>
<option value="20" <?php if($w['page_size']=='20') { ?>selected="selected"<?php } ?>><?php echo $_lang['page_size'];?>20</option>
<option value="30" <?php if($w['page_size']=='30') { ?>selected="selected"<?php } ?>><?php echo $_lang['page_size'];?>30</option>
</select>
<button class="pill" type="submit" value="<?php echo $_lang['search'];?>" name="sbt_search"><span class="icon magnifier">&nbsp;</span><?php echo $_lang['search'];?></button>
   </td>
</tr>
</tbody>
</table>
</form>
</div>
</div>
<!--搜索结束-->



<div class="box list">
    	<div class="title"><h2><?php echo $_lang['realname_auth_list'];?></h2></div>
        <div class="detail">
        <form action="<?php echo $url;?>" id='frm_list' method="post">
        	<div id="ajax_dom">
<input type="hidden" vlalue="<?php echo $page;?>" name="page" />
        <table cellspacing="0" cellpadding="0">
         <tbody>
          <tr>
          	<th width="15">
          		<input type="checkbox" id="checkbox" onclick="checkall();">
          	</th>

            <th width="30"><?php echo $_lang['id'];?></th>
            <th><?php echo $_lang['username'];?></th>
<th><?php echo $_lang['realname'];?></th>
<th><?php echo $_lang['id_card'];?></th>
<th><?php echo $_lang['id_card_copy'];?></th>
<!--<th><?php echo $_lang['cash'];?></th>-->
<th><?php echo $_lang['validity'];?></th>
<th><?php echo $_lang['auth_status'];?></th>
<!--<th><?php echo $_lang['operate'];?></th>-->
          </tr>
<?php if(is_array($realname_arr)) { foreach($realname_arr as $key => $v) { ?>
<tr class="item">
<td><input type="checkbox" name="ckb[]" id="cbk_selected" value="<?php echo $v['realname_a_id'];?>"></td>
<td><?php echo $v['realname_a_id'];?></td>
<td><?php echo $v['username'];?></td>
<td><?php echo $v['realname'];?></td>
<td><?php echo $v['id_card'];?></td>
<td><a target="_blank" href="../<?php echo $v['id_pic'];?>"><img src="../<?php echo $v['id_pic'];?>" width="40" height="40"></a></td>

<!--<td class="wraphide"><font color="#ff7700"><?php echo $_lang['free'];?></font></td>-->
<td><?php if($v['start_time']==$v['end_time']) { ?><?php echo $_lang['lifetime_validity'];?><?php } else { ?><?php if($v[start_time]){echo date('Y-m-d',$v[start_time]); } ?><?php echo $_lang['to'];?><?php if($v[end_time]){echo date('Y-m-d',$v[end_time]); } ?><?php } ?></td>
<td>
<?php if($v['auth_status']==1) { ?>
<font color="green"><?php echo $_lang['passed'];?></font>
<?php } elseif($v['auth_status']==2) { ?>
<font color="red"><?php echo $_lang['nopassed'];?></font>
<?php } else { ?>
<?php echo $_lang['wait_audit'];?>
<?php } ?>
<?php if($v['auth_status']==2||$v['auth_status']==0) { ?>
<a class="button dbl_target" href="index.php?do=<?php echo $do;?>&view=<?php echo $view;?>&ac=pass&code=<?php echo $code?>&realname_a_id=<?php echo $v['realname_a_id'];?>&obj=<?php echo $v['username']?>" 	  onclick="return cdel(this,'<?php echo $_lang['confirm_pass'];?>')"><span class="check icon"></span><?php echo $_lang['pass'];?></a>
<?php } ?>
<?php if($v['auth_status']==0) { ?>
<!-- <a class="button" href="index.php?do=<?php echo $do;?>&view=<?php echo $view;?>&code=<?php echo $code?>&ac=not_pass&realname_a_id=<?php echo $v['realname_a_id'];?>&obj=<?php echo $v['username']?>" onclick="return cdel(this,'<?php echo $_lang['confirm_nopass'];?>')"><span class="cross icon"></span>22<?php echo $_lang['nopass'];?></a> -->
<a  class="button" href=javascript:lookinfo("index.php?do=<?php echo $do;?>&view=<?php echo $view;?>&code=<?php echo $code?>&ac=not_pass&realname_a_id=<?php echo $v['realname_a_id'];?>&uid=<?php echo $v['uid'];?>"); ><span class="cross icon"></span><?php echo $_lang['nopass'];?></a>
<?php } ?>
<?php if($v['auth_status']!=1) { ?>
<a class="button" href="index.php?do=<?php echo $do;?>&view=<?php echo $view;?>&code=<?php echo $code?>&ac=del&realname_a_id=<?php echo $v['realname_a_id'];?>&obj=<?php echo $v['username']?>" 	  onclick="return cdel(this);"><span class="trash icon"></span><?php echo $_lang['delete'];?></a>
<?php } ?>
</td>

</tr>
<?php } } ?>
          <tr>
          	<td colspan="11">


 					<label for="checkbox"><?php echo $_lang['select_all'];?></lable>
<input type="hidden" name="sbt_action" class="sbt_action"/>　
<button type="submit" name="sbt_action" value="<?php echo $_lang['mulit_pass'];?>"   class="pill positive button" onclick="return batch_act(this,'frm_list');"><span class="icon check"></span><?php echo $_lang['pass'];?></button>
<button type="submit" name="sbt_action" value="<?php echo $_lang['mulit_nopass'];?>" class="pill button" onclick="return batch_act(this,'frm_list');"><span class="cross icon"></span><?php echo $_lang['nopass'];?></button>
<button type="submit" name="sbt_action" value="<?php echo $_lang['mulit_delete'];?>"   class="pill negative" onclick="return batch_act(this,'frm_list');"><span class="icon trash"></span><?php echo $_lang['delete'];?></button>

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
<script type="text/javascript">
function lookinfo(url){
art.dialog.open(url,{
title:"审核不通过的原因",
height:300,
width:400,
closeFn:function (){
var ispass=art.dialog.data('ispass');
var reason = art.dialog.data('reason');
var username = art.dialog.data('username');
if(ispass==1){
 window.location=url+'&is_submit=1'+'&reason='+reason+'&name='+username; 
}
}
},false);
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
</html><?php keke_tpl_class::ob_out();?>