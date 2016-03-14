<?php keke_tpl_class::checkrefresh('admin/tpl/admin_tpl_hotwords', '1457949905' );?><!DOCTYPE HTML>
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
    	<h1>热词管理</h1><br>
<input type="radio" name="switch" value="open" id="open" <?php if($arrHw['0']['v'] == 'open') { ?> checked="checked" <?php } ?> onchange="switchStatus(this)">开启
<input type="radio" name="switch" value="close" id="close"  <?php if($arrHw['0']['v'] == 'close') { ?> checked="checked" <?php } ?>  onchange="switchStatus(this)">关闭
<br>
<h2>搜索模式</h2>
<input type="radio" name="update" value="auto" id="auto" <?php if($arrUpdate['0']['v'] == 'auto') { ?> checked="checked" <?php } ?> onchange="autoUpdate(this)" >自动更新
<input type="radio" name="update" value="handle" id="handle" <?php if($arrUpdate['0']['v'] == 'handle') { ?> checked="checked" <?php } ?> onchange="autoUpdate(this)" >手动更新
</div>
<div class="box clearfix" id = "body">
<form method="post" action="#" id="frm_hw_search">
<input type="hidden" name="do" value="<?php echo $do;?>">
<input type="hidden" name="view" value="<?php echo $view;?>">
<div class="box search p_relative">
    	<div class="title"><h2><?php echo $_lang['search'];?></h2></div>
    	<div class="detail" id="detail">
    		<table cellspacing="0" cellpadding="0">
<tbody>
<tr>
<th>请输入编号</th>
<td>
<input type="text" class="txt" name="id" value="<?php echo $id;?>">
</td>
<th>请输入名称</th>
<td>
<input type="text" class="txt" name="title" value="<?php echo $title;?>">
</td>
<th>排序</th>
<td >
<select name="ord">
                			<option value="1" <?php if($ord=='1') { ?>selected="selected"<?php } ?>>按编号升序</option>
                			<option value="2" <?php if($ord=='2') { ?>selected="selected"<?php } ?>>按编号降序</option>
                			<option value="3" <?php if($ord=='3') { ?>selected="selected"<?php } ?>>搜索量升序</option>
<option value="4" <?php if($ord=='4') { ?>selected="selected"<?php } ?>>搜索量降序</option>
            			</select>
           				<button class="pill" type="submit" value=<?php echo $_lang['search'];?> name="sbt_search">
<span class="icon magnifier">&nbsp;</span><?php echo $_lang['search'];?>
</button>
</td>
</tr>
           		</tbody>
</table>
    	</div>
    </div>
</form>
<form method="post" action="#" id="frm_hw_add" onsubmit=" return validate_form(this)">
<input type="hidden" name="do" value="<?php echo $do;?>">
<input type="hidden" name="view" value="<?php echo $view;?>">
<div class="box search p_relative">
    	<div class="detail" id="detail">
    		<table cellspacing="0" cellpadding="0">
<tbody>
<tr>
<th>热词名称</th>
<td>
<input type="text" class="txt" name="words_add" id = "words_add">
</td>
<th>次数</th>
<td>
<input type="text" class="txt" name="count_add" id ="count_add">
</td>
<td>
           				<button class="pill" type="submit" value="添加" name="sbt_add">
<span class="icon magnifier">&nbsp;</span>添加
</button>
</td>
</tr>
           		</tbody>
</table>
    	</div>
    </div>
</form>
<div class="box list clearfix">
<form method="post" action="index.php?do=tpl&view=hotwords" name="frm_hotwords" id="frm_hotwords">
<div class="title"><h2>动态列表</h2></div>
    <div class="detail">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tab_list">
        	<tr class="item">
        		<th>
<input type="checkbox" id="checkbox" onclick="checkall();" class="checkbox" >编号
</th>
           		<th align="left">排序</th>
           		<th>词语搜索</th>
                <th>总搜索量</th>
                <th>最近搜索时间</th>
                <th>操作</th>
            </tr>
                <?php if(is_array($arrHotWords)) { foreach($arrHotWords as $k => $v) { ?>
                <tr class="item">
                	<td>
                       <input type="checkbox" name="ckb[]" class="checkbox" value="<?php echo $v['id'];?>"><?php echo $v['id'];?>
                    </td>
                    <td>
                        <input type="text" class="txt" name="sort" value = "<?php echo $v['sort'];?>" onchange="edit(<?php echo $v['id'];?>,this.value)">
                    </td>
                    <td>
                        <?php echo $v['words'];?>
                    </td>
                    <td>
                    	<?php echo $v['count'];?>
                    </td>
                    <td>
                    	<?php if($v['time']){echo date('Y-m-d H:m',$v['time']); } ?>
                    </td>
                    <td>
                        <a href="index.php?do=tpl&view=hotwords&op=del&delid=<?php echo $v['id']?>" onclick="return cdel(this)"  class="button"><span class="trash icon"></span><?php echo $_lang['delete'];?></a>
                    </td>
                </tr>
<?php } } ?>
<tr>
            	<td colspan="7">
                    <div class="clearfix">
     <label for="checkbox" onclick="checkall(event);"><?php echo $_lang['select_all'];?></label>
                     <input type="hidden" name="sbt_action" class="sbt_action" id="sbt_action"/>　
                        <button class="pill negative button" type="submit" value="<?php echo $_lang['mulit_delete'];?>" name="sbt_action" onclick="return batch_act(this,'frm_hotwords')"><span class="icon trash">&nbsp;</span><?php echo $_lang['mulit_delete'];?></button>
                    </div>
                 </td>
        	</tr>
       </table>
   <div class="page">
             <?php echo $pages['page'];?>
       </div>
  </div>
</form>
</div>
</div>
<style type="text/css">
.show{
display:block;
}
.hidden{
display:none;
}
</style>
<script type="text/javascript" src="static/js/jquery.min.1.8.3.js"></script>
<script type="text/javascript">
//加载时添加样式
$(function(){
var node = document.getElementById("open");
if(node.checked){
$("#body").addClass("show");
}else{
$("#body").addClass("hidden");
}
})
//检验添加表单
function validate_form(from){
if($("#words_add").val() ==""){
alert("请输入热词");
$("#words_add").focus();
return false;
}else if($("#count_add").val() == ""){
alert("请输入次数");
$("#count_add").focus();
return false;
}else if(isNaN($("#count_add").val())){
alert("请输入正确的数字");
$("#count_add").val("");
$("#count_add").focus();
return false;
}else{
return true;
}
}
//热词开启关闭功能
function switchStatus(node){
if(node.value == "open"){
if(confirm("确认开启热词功能?")){
$.get("index.php?do=tpl&view=hotwords&ac=open&status="+node.value);
$("#body").addClass("show");
$("#body").removeClass("hidden");
}else{
document.getElementById("close").checked=true;
$("#body").removeClass("show");
$("#body").addClass("hidden");
}
}else{
if(confirm("确认关闭热词功能?")){
$.get("index.php?do=tpl&view=hotwords&ac=open&status="+node.value);
$("#body").addClass("hidden");
$("#body").removeClass("show");
}else{
document.getElementById("open").checked=true;
$("#body").addClass("show");
$("#body").removeClass("hidden");
}
}
}
//自动手动更新功能
function autoUpdate(node){
if(node.value == "auto"){
if(confirm("确认开启自动更新?")){
$.get("index.php?do=tpl&view=hotwords&ac=handle&status="+node.value);
}else{
document.getElementById("handle").checked=true;
}
}else{
if(confirm("确认开启手动更新?")){
$.get("index.php?do=tpl&view=hotwords&ac=handle&status="+node.value);
}else{
document.getElementById("auto").checked=true;
}
}
}
//站长手动更改排序
function edit(id,val){
$.get("index.php?do=tpl&view=hotwords&ac=editlistorder&id="+id+"&sort="+val);
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