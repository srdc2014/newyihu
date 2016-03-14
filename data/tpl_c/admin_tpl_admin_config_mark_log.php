<?php keke_tpl_class::checkrefresh('admin/tpl/admin_config_mark_log', '1457949926' );?><!DOCTYPE HTML>
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
<h1><?php echo $_lang['mutual_evaluation_record_manage'];?></h1>
<!--
    <div class="tool"> 
        <a href="index.php?do=<?php echo $do?>&view=<?php echo $view?>&op=<?php echo $op?>" class="here"><?php echo $_lang['mutual_evaluation_record_manage'];?></a>
</div>
-->
</div>
   <div class="box search p_relative">
    	<div class="title"><h2><?php echo $_lang['search'];?></h2></div>
        <div class="detail" id="detail">
<form action="" method="get">
        	<input type="hidden" name="do"   value="<?php echo $do?>">
<input type="hidden" name="view" value="<?php echo $view?>">
<input type="hidden" name="op" value="<?php echo $op?>">
<input type="hidden" name="page" value="<?php echo $page?>">
<table cellspacing="0" cellpadding="0">
 <tbody>
 	<tr>
 		<th><?php echo $_lang['mutual_evaluation_id'];?></th>
<td><input type="text" class="txt" name="w[mark_id]" value="<?php echo $w['mark_id'];?>" onkeyup="clearstr(this);"></td>
<th><?php echo $_lang['from'];?></th>
<td><input type="text" class="txt" name='w[by_username]' value="<?php echo $w['by_username'];?>" onkeyup="clearspecial(this);"></td>
 	</tr>

<tr>
<th><?php echo $_lang['display_number'];?></th>
<td>
<select name="w[page_size]" class="ps vm">
<option value="10" <?php if($w['page_size']=='10') { ?>selected="selected"<?php } ?>><?php echo $_lang['page_size'];?>10</option>
<option value="20" <?php if($w['page_size']=='20') { ?>selected="selected"<?php } ?>><?php echo $_lang['page_size'];?>20</option>
<option value="30" <?php if($w['page_size']=='30') { ?>selected="selected"<?php } ?>><?php echo $_lang['page_size'];?>30</option>
</select>
</td>
<th><?php echo $_lang['result_order'];?></th>
<td>

<select name="ord[]">
                        <option value="mark_id" <?php if($ord['0']=='mark_id' or !isset($ord['0'])) { ?> selected="selected"<?php } ?>><?php echo $_lang['default_order'];?></option>
                        <option value="mark_time" <?php if($ord['0']=='mark_time' ) { ?> selected="selected"<?php } ?>><?php echo $_lang['mutual_evaluation_time'];?></option>
<option value="mark_status" <?php if($ord['0']=='mark_status' ) { ?> selected="selected"<?php } ?>><?php echo $_lang['mutual_evaluation_status'];?></option>
<option value="mark_value" <?php if($ord['0']=='mark_value' ) { ?> selected="selected"<?php } ?>><?php echo $_lang['mutual_evaluation_value'];?></option>
 

                        </select>
                        <select name="ord[]">
                        <option <?php if($ord['1']=='desc' or !isset($ord['1'])) { ?>selected="selected" <?php } ?> value="desc"><?php echo $_lang['desc'];?></option>
                        <option <?php if($ord['1']=='asc') { ?>selected="selected" <?php } ?> value="asc"><?php echo $_lang['asc'];?></option>
                        </select>
<button type="submit" name="sbt_search" value=<?php echo $_lang['search'];?> class="pill" /><span class="icon magnifier">&nbsp;</span><?php echo $_lang['search'];?></button>
</td>
</tr>
 
 </tbody>
</table>
</form>
        </div>
 </div>



<div class="box list">
    	<div class="title"><h2><?php echo $_lang['mutual_evaluation_record'];?></h2></div>
        <div class="detail">
<form method="post" action="#" id="frm_user_search">
<div id="ajax_dom">
<input type="hidden" name="page" value="<?php echo $page;?>" />
  		<table cellpadding="0" cellspacing="0">
  		  <tbody>
          <tr>
          	<th>
<input type="checkbox" id="checkbox" onclick="checkall();" class="checkbox" >编号
</th>
<th width="15%"><?php echo $_lang['belong_model'];?></th>
<th><?php echo $_lang['from'];?></th>
<th><?php echo $_lang['to_user'];?></th>
<th><?php echo $_lang['evaluation'];?></th>
<th><?php echo $_lang['ability_credit'];?></th>
<th><?php echo $_lang['time'];?></th>
<th><?php echo $_lang['operate'];?></th>
          </tr>
<?php if(is_array($mark_data)) { foreach($mark_data as $k => $v) { ?>
        <tr class="item">
        	<td><input type="checkbox" name="ckb[]" class="checkbox" value="<?php echo $v['mark_id'];?>"><?php echo $v['mark_id'];?>
        	</td>
            
            <td width="12%">
            	《<?php echo $model_type_arr[$v['model_code']];?>》
<?php if($model_list[$v['model_code']]['model_type']=='task') { ?>
<a href="<?php echo $_K['siteurl'];?>/index.php?do=task&id=<?php echo $v['origin_id'];?>&page=<?php echo $page;?>" target="_blank"><?php echo $_lang['view_task'];?></a>
<?php } else { ?>
<a href="<?php echo $_K['siteurl'];?>/index.php?do=goods&id=<?php echo $v['origin_id'];?>&page=<?php echo $page;?>" target="_blank"><?php echo $_lang['view_service'];?></a>
<?php } ?>
</td>
<td><?php echo $form[$v['mark_type']]?> <?php echo $v['by_username'];?></td>
            <td><?php echo $v["username"];?></td>
<td><?php echo $status[$v["mark_status"]];?></td>
<td><?php echo $v['mark_value'];?></td>
<td><?php if($v['mark_time']) { ?><?php if($v['mark_time']){echo date('Y-m-d',$v['mark_time']); } ?><?php } ?></td>
<td>
<a class="button" href="<?php echo $url;?>&ac=del&mark_id=<?php echo $v['mark_id'];?>&page=<?php echo $page;?>" onclick="return cdel(this);"><span class="trash icon"></span><?php echo $_lang['delete'];?></a>
</td>
          </tr>
 <?php } } ?>
          <tr>
          	<td colspan="9">
<div class="clearfix">
                    
                    <label for="checkbox" onclick="checkall(event);"><?php echo $_lang['select_all'];?></label>
<input type="hidden" name="sbt_action" class="sbt_action" />
<button type="submit" name="sbt_action" value="<?php echo $_lang['mulit_delete'];?>" class="pill negative" onclick="return batch_act(this,'frm_user_search');">
<span class="icon trash"></span><?php echo $_lang['mulit_delete'];?></button>
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
</html>
<?php keke_tpl_class::ob_out();?>