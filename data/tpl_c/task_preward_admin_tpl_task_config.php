<?php keke_tpl_class::checkrefresh('task/preward/admin/tpl/task_config', '1457950002' );?><!DOCTYPE HTML>
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
  <h1><?php echo $model_info['model_name'];?><?php echo $_lang['task_config'];?></h1>
    <div class="tool">
      <a href="index.php?do=<?php echo $do;?>&model_id=<?php echo $model_id;?>&view=<?php echo $view;?>&op=config" class="here"><?php echo $_lang['basic_config'];?></a>
      <a href="index.php?do=<?php echo $do;?>&model_id=<?php echo $model_id;?>&view=<?php echo $view;?>&op=control"><?php echo $_lang['control_config'];?></a>
      <a href="index.php?do=<?php echo $do;?>&model_id=<?php echo $model_id;?>&view=<?php echo $view;?>&op=priv"><?php echo $_lang['private_config'];?></a>
      <a href="index.php?do=custom&view=fields&model_id=<?php echo $model_id;?>">自定义输入字段</a>
     </div>
</div>
<div class="box post">
 <div class="tabcon">
  <div class="title"><h2><?php echo $_lang['basic_config'];?></h2></div>
<div class="detail">
 <form action="<?php echo $ac_url;?>" method="post">
 	<input type="hidden" name="pk[model_id]" value="<?php echo $model_id;?>">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
   <th scope="row" width="200"><?php echo $_lang['model_name'];?><?php echo $_lang['zh_mh'];?></th>
    <td>
       <input type="text" class="txt" name="fds[model_name]" value="<?php echo $model_info['model_name']?>">
</td>
        </tr>
<tr>
   <th scope="row" width="200"><?php echo $_lang['is_open'];?><?php echo $_lang['zh_mh'];?></th>
     <td>
        <label for="status_yes"><input type="radio" id="status_yes" name="fds[model_status]" <?php if($model_info['model_status']) { ?>checked<?php } ?> value="1"> <?php echo $_lang['yes'];?></label>
<label for="status_no"><input type="radio" id="status_no" name="fds[model_status]" <?php if(!$model_info['model_status']) { ?>checked<?php } ?> value="0"> <?php echo $_lang['no'];?></label>
</td>
        </tr>
<tr>
  <!--<th scope="row" width="200"><?php echo $_lang['whether_private_model'];?><?php echo $_lang['zh_mh'];?></th>
   <td>
      <label for="hide_yes"><input type="radio" id="hide_yes" name="fds[hide_mode]" <?php if($model_info['hide_mode']) { ?>checked<?php } ?> value="1"> <?php echo $_lang['yes'];?></label>
  <label for="hide_no"><input type="radio" id="hide_no" name="fds[hide_mode]" <?php if(!$model_info['hide_mode']) { ?>checked<?php } ?> value="0"> <?php echo $_lang['no'];?></label>
              (<?php echo $_lang['private_models_not_task_list'];?>)
   </td>
   -->
        </tr>
<!--	<tr>
  <th scope="row" width="200"><?php echo $_lang['industry_binding'];?><?php echo $_lang['zh_mh'];?></th>
    <td><?php $milist = explode(',',$model_info['indus_bid']); ?>
     <!--         <select onchange="p_indus_add(this.value)" style="margin-bottom:5px;">
   <option value=""><?php echo $_lang['choose_industry'];?></option>
<?php if(is_array($indus_index['0'])) { foreach($indus_index['0'] as $indus) { ?>
<option value="<?php echo $indus['indus_id']?>"><?php echo $indus['indus_name']?></option>
<?php } } ?>
  </select>
 <br class="clear">
 <select name="p_indus_select" id="p_indus_select" multiple=multiple style="width:160px;float:left;height:200px;overflow-y:scroll;background:white;border:#ccc solid 1px" ></select>
<div style="width:80px;float:left;height:160px;overflow:hidden;margin-left:20px; text-align:center;padding-top:40px;">
<button onclick = "moveitems()" type="button" class="input_but"><<</button><br><br>
<button onclick = "additems()" type="button" class="input_but">>></button><br>
</div>
<select name="fds[indus_bid][]" id="s_indus_select" multiple=multiple style="width:160px;float:left;height:200px;overflow-y:scroll;background:white;border:#ccc solid 1px;margin-left:20px;">
<?php if(is_array($milist)) { foreach($milist as $k => $m) { ?>
<?php if(!empty($m)) { ?>
<option id="s_indus_select_op_<?php echo $m;?>" value="<?php echo $m?>" selected="selected"> <?php echo $indus_arr[$m]['indus_name']?></option>
<?php } ?>
<?php } } ?>
</select>
<br class="clear">(<?php echo $_lang['industry_binding_notice'];?>.)
</td>
         </tr>
<!--	 <tr>
    <th scope="row" width="200"><?php echo $_lang['model_about'];?><?php echo $_lang['zh_mh'];?></th>
      <td>
         <textarea cols=80 rows=8 name="fds[model_intro]"><?php echo $model_info['model_intro']?></textarea><br>(<?php echo $_lang['limit_50_bytes'];?>)
  </td>
         </tr>-->
 <tr>
    <th scope="row" width="200">模型说明<?php echo $_lang['zh_mh'];?></th>
      <td>
        <textarea cols=110  name="fds[model_desc]"   id="tar_content"   cols="70"><?php echo $model_info['model_desc']?></textarea>
  </td>
   <script type="text/javascript" src="<?php echo $_K['siteurl'];?>/static/js/ueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="<?php echo $_K['siteurl'];?>/static/js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="<?php echo $_K['siteurl'];?>/static/js/ueditor/lang/zh-cn/zh-cn.js"></script>
 <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('tar_content', {
            //这里可以选择自己需要的工具按钮名称,此处仅选择如下五个
        	initialFrameWidth: '80%',
        	initialFrameHeight: '400'
        });
    </script>
         </tr>
 <?php if($on_time) { ?>
    <tr>
      <th scope="row" width="200"><?php echo $_lang['last_modify'];?><?php echo $_lang['zh_mh'];?></th>
        <td><?php echo date('Y-m-d H:i:s',$on_time) ?></td>
    </tr>
         <?php } ?>
 <tr>
  <th scope="row" width="200">&nbsp;</th>
    <td>
       <div class="clearfix padt10">
         <button type="submit" name="sbt_edit" class="positive primary  button" value="1"><span class="check icon"></span><?php echo $_lang['submit'];?></button>

               </div>
             </td>
         </tr>
        </table>
       </form>
     </div>
   </div>
</div>
<script type="text/javascript">
        $(function(){
$("#txt_task_min_cash").keyup(function(){
$("#txt_task_min_cash_1").val($(this).val());
})
 $("#end_action_3").click(function(){
 	 if($("#end_action_3").attr("checked")==true){
  $("#slt").show();
  }else{
 $("#slt").hide();}
 })
 if($("#end_action_3").attr("checked")==true){
  $("#slt").show();
  }
 $("#end_action_1").click(function(){
 	$("#slt").hide();
 })
 $("#end_action_2").click(function(){
 	$("#slt").hide();
 })


            $("#add_cash_rul").click(function(){
                var i = parseInt($("#add_new_rul li:last-child span").html());
                k = eval(i + 1);
                var rul_option = '<li><span id="span_' + k + '"></span> <input type="text" class="txt" size="10" name="txt_task_min_cash_' + k + '" limit="required:true;type:float" msg="<?php echo $_lang['task_min_money_error'];?>"  class="input_t"/><?php echo $_lang['yuan_over'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $_lang['continue'];?><input type="text" class="txt" size="10" limit="require:true;type:int" msg="<?php echo $_lang['day_not_null'];?>"  name="txt_task_min_day_'+k+'" class="input_t"> <?php echo $_lang['day'];?>  </li>';
                $("#add_new_rul").append(rul_option);
                var lastinput = $("#add_new_rul li:last-child input");
                $("#add_new_rul li:last-child #span_" + k).html(k);
$("#cash_rul_count").val(k);
            })
            $("#del_cash_rul").click(function(){
               var i = parseInt($("#add_new_rul li:last-child span").html());
if(i!=1)
{
  $("#add_new_rul li:last-child").remove();
  $("#cash_rul_count").attr('value',i-1);
}
else
{
alert("<?php echo $_lang['first_rule_not_delete'];?>");
}

            })

            $("#add_adjourn_rul").click(function(){

                var i = parseInt($("#new_adjourn_rul li:last-child input").eq(0).val());

k = eval(i + 1);
var rul_option ='<li><?php echo $_lang['di'];?><input type="text" class="txt" size="10" name="txt_defer_times_'+k+'" value="'+k+'" readonly="true" class="input_t"/><?php echo $_lang['times_info'];?><input type="text" size="10" class="txt"  limit="type:float;required:true" msg="<?php echo $_lang['percent_not_null'];?>!" name="txt_defer_cash_scale_'+k+'" class="input_t">%</li>';
                $("#new_adjourn_rul").append(rul_option);
                var lastinput = $("#new_adjourn_rul li:last-child input");
$("#adjourn_rul_count").val(k);

            })
            $("#del_adjourn_rul").click(function(){
var i = parseInt($("#new_adjourn_rul li:last-child input").eq(0).val());
if(i!=1)
{
                	$("#new_adjourn_rul li:last-child").remove();
$("#adjourn_rul_count").val(i-1);
}
else
{
alert("<?php echo $_lang['first_rule_not_delete'];?>");
}
            })


        })

function p_indus_add(id){
mod = "";
<?php if(is_array($indus_index)) { foreach($indus_index as $k => $si) { ?>
if(id=='<?php echo $k?>'){
<?php if(is_array($si)) { foreach($si as $i) { ?>
if(!$('#s_indus_select_op_<?php echo $i['indus_id'];?>').val()){
mod +="<option id=\"p_indus_select_op_<?php echo $i['indus_id'];?>\" value=\"<?php echo $i['indus_id'];?>\"><?php echo $i['indus_name'];?></option>";
}
<?php } } ?>
}
<?php } } ?>
$('#p_indus_select').html(mod);
}

function moveitems(){
vs = $('#s_indus_select').val()+'';
vs = vs.split(",");
var mod = '';
for(v=0;v<vs.length;v++){

if(String(vs[v]) == "null"){
continue;
}
mod+="<option value="+vs[v]+" id=\"p_indus_select_op_"+vs[v]+"\">"+$('#s_indus_select_op_'+vs[v]).html()+"</option>";
$('#s_indus_select_op_'+vs[v]).remove();
};
if(mod!='')
$('#p_indus_select').append(mod);
}
$(function(){
if($("#hdn_indus_id").val()!=''){
p_indus_add($("#hdn_indus_id").val());
}
})
function additems(){
vs = $('#p_indus_select').val()+'';
vs = vs.split(",");
var mod = '';
for(v=0;v<vs.length;v++){
if(String(vs[v]) == "null"){
continue;
}
mod+="<option value="+vs[v]+" id=\"s_indus_select_op_"+vs[v]+"\" selected=\"selected\">"+$('#p_indus_select_op_'+vs[v]).html()+"</option>";
$('#p_indus_select_op_'+vs[v]).remove();
};
if(mod!='')
$('#s_indus_select').append(mod);
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