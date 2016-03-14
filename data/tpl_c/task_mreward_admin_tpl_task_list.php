<?php keke_tpl_class::checkrefresh('task/mreward/admin/tpl/task_list', '1457949996' );?><!DOCTYPE HTML>
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
    	<h1><?php echo $model_info['model_name'];?><?php echo $_lang['manage'];?></h1>
         <div class="tool">
         	<a href="index.php?do=model&model_id=2&view=list&status=0" class="here"><?php echo $_lang['task_list'];?></a>
<!--<a href="index.php?do=model&model_id=2&view=config"><?php echo $_lang['task_config'];?></a>-->
 </div>
</div>
    <!--页头结束-->

    <!--提示结束-->

        <div class="box search p_relative">
    	<div class="title"><h2><?php echo $_lang['search'];?></h2></div>
        <div class="detail" id="detail">

    <form action="index.php?do=model&model_id=2&view=list" method="post">
 <input type="hidden" value="<?php echo $page;?>" name="page">
                <table cellspacing="0" cellpadding="0">
<tbody>
                        <tr>
                            <th><?php echo $_lang['task_id'];?></th>
                            <td><input type="text" value="<?php echo $w['task_id']?>" name="w[task_id]" class="txt"/><?php echo $_lang['search_by_like'];?></td>
                            <th><?php echo $_lang['task_title'];?></th>
                            <td><input type="text" value="<?php echo $w['task_title']?>" name="w[task_title]" class="txt"/> <?php echo $_lang['search_by_like'];?></td>
                           <th></th> <td><button class="pill" type="submit" value="<?php echo $_lang['search'];?>" name="sbt_search">
                            		<span class="icon magnifier">&nbsp;</span><?php echo $_lang['search'];?>
</button></td>
</tr>
                        <tr>
                        	 <th><?php echo $_lang['please_choose_task_status'];?></th>
                            <td>
                            	<select class="ps vm" name="w[task_status]" id="catid" onchange="statusJump(this.value)">
                            		<option value=""><?php echo $_lang['please_choose'];?></option>
                            	<?php if(is_array($task_status)) { foreach($task_status as $k => $v) { ?>
<?php $k = strval($k); ?>
<option value="<?php echo $k?>" <?php if($w['task_status']===$k) { ?> selected="selected" <?php } ?>><?php echo $v?></option>
<?php } } ?>
</select>
</td>
<th><?php echo $_lang['result_order'];?></th>
<td>
<select name="ord[]" id="ord1">
                           <option value="task_id" <?php if($ord['0']=='task_id' or !isset($ord['0'])) { ?> selected="selected"<?php } ?>><?php echo $_lang['default'];?><?php echo $_lang['order'];?></option>
                           <option value="start_time" <?php if($ord['0']=='start_time' ) { ?> selected="selected"<?php } ?>>开始时间</option>
                      </select>
                      <select name="ord[]" onchange="orderJump(this.value)">
                            <option <?php if($ord['1']=='desc' or !isset($ord['1'])) { ?>selected="selected" <?php } ?> value="desc"><?php echo $_lang['desc'];?></option>
                            <option <?php if($ord['1']=='asc') { ?>selected="selected" <?php } ?> value="asc"><?php echo $_lang['asc'];?></option>
                      </select>
</td>
                            <th><?php echo $_lang['list_result'];?></th>
                            <td><select name="page_size" onchange="pageJump(this.value)">
<option value="10" <?php if($page_size=='10') { ?>selected="selected"<?php } ?>><?php echo $_lang['page_size'];?>10</option>
<option value="20" <?php if($page_size=='20') { ?>selected="selected"<?php } ?>><?php echo $_lang['page_size'];?>20</option>
<option value="30" <?php if($page_size=='30') { ?>selected="selected"<?php } ?>><?php echo $_lang['page_size'];?>30</option>
</select>
                              	</td>
                        </tr>
                    </tbody>
                </table>
            </form>

        </div>
    </div>
    <!--搜索结束-->

    <div class="box list">
    	<div class="title"><h2><?php echo $_lang['more_reward'];?><?php echo $_lang['task_list'];?></h2></div>
        <div class="detail">
        	<form action="" id='frm_list' method="post">
        	<input type="hidden" value="<?php echo $page;?>" name="page">
<input type="hidden" name="w[page_size]" value="<?php echo $page_size;?>">
              <div id="ajax_dom">
              <input type="hidden" value="<?php echo $page;?>" name="page" />
              <table cellpadding="0" cellspacing="0">
                <tbody>
                  <tr>
                    <th>编号</th>
                    <th width="28%"><?php echo $_lang['task_title'];?></th>
<th width="10%"><?php echo $_lang['task_cash'];?></th>

                    <th width="7%"><?php echo $_lang['pub_name'];?></th>
 <th width="7%"><?php echo $_lang['task_status'];?></th>

                    <th width="23%"><?php echo $_lang['operate'];?></th>
                  </tr>
                  <?php if(is_array($task_arr)) { foreach($task_arr as $k => $v) { ?>
                  <tr class="item">
                 	<td class="td25"><?php echo $v['task_id'];?></td>
                    <td class="td28">
                    	<a href="../index.php?do=task&id=<?php echo $v['task_id'];?>" target="_blank">
                    		<?php echo(kekezu::cutstr($v[task_title],40)); ?></a>
<?php if($v['is_top']==1) { ?><font color="red">[<?php echo $_lang['recommended'];?>]</font><?php } ?>
</td>
<td><?php  echo keke_curren_class::output(floatval($v[task_cash]),-1);  ?></td>
                    <td><?php echo $v['username']?></td>
    <td><?php echo $task_status[$v['task_status']]?></td>

                    <td>
                       <!--
                       <?php if($v['task_status']==1||$v['task_status']==0) { ?>
<a class="button" href="index.php?do=model&model_id=<?php echo $v['model_id']?>&view=list&task_id=<?php echo $v['task_id']?>&ac=pass&page=<?php echo $page;?>" onclick="return cpass(this,'',1);"><span class="check icon"></span>审核通过</a>
<a class="button" href="index.php?do=model&model_id=<?php echo $v['model_id']?>&view=list&task_id=<?php echo $v['task_id']?>&ac=nopass&page=<?php echo $page;?>" onclick="return cpass(this,'',2);"><span class="cross icon"></span>审核失败</a>
<?php } ?>
 -->

                	<?php if($v['task_status']==1||$v['task_status']==0) { ?>
<a class="button" href="index.php?do=model&model_id=<?php echo $v['model_id']?>&view=list&task_id=<?php echo $v['task_id']?>&ac=pass&page=<?php echo $page;?>" onclick="return cpass(this,'',1,<?php echo $v['task_status']?>);"><span class="check icon"></span>审核通过</a>
<!-- <a class="button" href="index.php?do=model&model_id=<?php echo $v['model_id']?>&view=list&task_id=<?php echo $v['task_id']?>&ac=nopass&page=<?php echo $page;?>" onclick="return cpass(this,'',2);"><span class="cross icon"></span>审核失败</a> -->
<a href=javascript:lookinfo("index.php?do=model&model_id=<?php echo $v['model_id']?>&view=list&task_id=<?php echo $v['task_id']?>&ac=nopass&page=<?php echo $page;?>&uid=<?php echo $v['uid'];?>"); class="button"><span class="cross icon"></span>审核失败</a>
<?php } ?>
<a class="button dbl_target" href="index.php?do=model&model_id=2&view=edit&task_id=<?php echo $v['task_id']?>&page=<?php echo $page;?>"><span class="pen icon"></span>查看</a>
<?php if(in_array($v['task_status'],array('6','8','9','10'))) { ?>
<a class="button" href="index.php?do=model&model_id=2&view=list&ac=del&task_id=<?php echo $v['task_id']?>&page=<?php echo $page;?>"  onclick="return cdel(this);"><span class="trash icon"></span><?php echo $_lang['delete'];?></a>
<?php } ?>
<?php if(!in_array($v['task_status'],array('0','1','8','9','10'))) { ?>
<a href="#" class="button" onclick="stoptask(<?php echo $v['task_id']?>);">结束任务</a>
<?php } ?>
</td>
                  </tr>
                  <?php } } ?>

                  <tr>
                    <td colspan="7">
                    <div class="page fl_right"><?php echo $pages['page'];?></div>

                    
                    </td>
                  </tr>
                </tbody>
              </table></div>
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
<script type="text/javascript">
var url = '<?php echo $url_str;?>';
function statusJump(task_status){
window.location.href = url+'&w[task_status]='+task_status;
}
function orderJump(value){
var ord1 = $("#ord1").children("option:selected").val();//selected的值
window.location.href= url+'&ord[0]='+ord1+'&ord[1]='+value;
}
function pageJump(value){
window.location.href = url+'&page_size='+value;
}
function lookinfo(url){
art.dialog.open(url,{
title:"审核不通过的原因",
height:400,
width:600,
closeFn:function (){
var ispass=art.dialog.data('ispass');
if(ispass=='ok'){
var reason = art.dialog.data('reason');
var username = art.dialog.data('username');
var title = art.dialog.data('title');
art.dialog.data('ispass','no');
 	window.location.href=url+'&is_submit=1'+'&reason='+reason+'&username='+username+'&title='+title; 
} 

}
},false); 
}
function stoptask(task_id){
art.dialog.open('index.php?do=model&model_id=2&view=list&ac=settask&task_id='+task_id,{
title:"请输入结束任务原因",
height:260,
width:450,
closeFn:function (){
var ispass=art.dialog.data('ispass');
var content=art.dialog.data('content');
if(ispass=='ok'){
art.dialog.data('ispass','no');
window.location="index.php?do=model&model_id=2&view=list&ac=stoptask&content="+content+"&task_id="+task_id;
}
}
},false);
}
</script><?php keke_tpl_class::ob_out();?>