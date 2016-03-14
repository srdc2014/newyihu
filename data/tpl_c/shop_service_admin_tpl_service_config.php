<?php keke_tpl_class::checkrefresh('shop/service/admin/tpl/service_config', '1457949989' );?><!DOCTYPE HTML>
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
    	<h1><?php echo $_lang['witkey_service_config'];?></h1>
        <div class="tool">
            <a href="index.php?do=model&model_id=<?php echo $model_id;?>&view=config&op=config" <?php if($op=="config"||!$op) { ?>class="here"<?php } ?> id="tab_cont_1"><?php echo $_lang['basic_config'];?></a>
            <a href="index.php?do=model&model_id=<?php echo $model_id;?>&view=config&op=control" <?php if($op=="control") { ?>class="here"<?php } ?> id="tab_cont_2"><?php echo $_lang['control_config'];?></a>
            <a href="index.php?do=custom&view=fields&model_id=<?php echo $model_id;?>">自定义输入字段</a>
           <!-- <a <?php if($op=="rule") { ?>class="here"<?php } ?> id="tab_cont_3" onclick="swaptab('cont','here','',3,3)"><?php echo $_lang['private_config'];?></a>-->
    	</div>
</div>
<div class="box tip clearfix p_relative" id="man_tips">
   <div class="control"><a href="javascript:void(0);" title=<?php echo $_lang['close'];?> onclick="$('#man_tips').fadeOut();"><b>&times;</b></a></div>
   <div class="title"><h2><?php echo $_lang['tips'];?></h2></div>
   <div class="detail pad10">
      <ul>
         <li>*基本配置中的模型描述显示在上传服务时的展开说明界面</li>
      </ul>
   </div>
</div>
<div class="box post" id="div_cont_1" style="display:<?php if($op=='config'||!$op) { ?>block<?php } else { ?>none<?php } ?>">
<div class="tabcon">
    	<div class="title"><h2><?php echo $_lang['task_config'];?></h2></div>
        <div class="detail">
        	<form name="frm_config_employ" action="index.php?do=model&model_id=<?php echo $model_id;?>&view=config" method="post">
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                      	 <th scope="row" width="130"><?php echo $_lang['model_name'];?><?php echo $_lang['zh_mh'];?></th>
                        <td>
                        <input type="text" value="<?php echo $model_info['model_name']?>" name="fds[model_name]" class="txt" style=" width:260px;"/>
                        </td>
                      </tr>
                      <tr>
                        <th scope="row"><?php echo $_lang['is_open'];?><?php echo $_lang['zh_mh'];?></th>
                        <td>
<label><input type="radio" name="fds[model_status]" <?php if($model_info['model_status']) { ?>checked<?php } ?> value="1" id="model_status_0" /> <?php echo $_lang['yes'];?></label>
                            <label><input name="fds[model_status]" type="radio" <?php if(!$model_info['model_status']) { ?>checked<?php } ?> value="0"id="model_status_1" /><?php echo $_lang['no'];?></label>
                        </td>
                      </tr>
                   <!--   <tr>
  <th scope="row" width="200"><?php echo $_lang['bind_industry_shop'];?></th>
    <td><?php $milist = explode(',',$model_info['indus_bid']); ?>

         <!--     <select onchange="p_indus_add(this.value)" style="margin-bottom:5px;">
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
<br class="clear"><?php echo $_lang['choose_industry_nitice'];?>
</td>
         </tr>
 		 <!--<tr>
    <th scope="row" width="200"><?php echo $_lang['model_about'];?><?php echo $_lang['zh_mh'];?></th>
      <td>
         <textarea cols=80 rows=8 name="fds[model_intro]"><?php echo $model_info['model_intro']?></textarea><br>(<?php echo $_lang['limit_50_bytes'];?>)
  </td>
         	</tr>-->
 		 <tr>
    <th scope="row" width="200"><?php echo $_lang['model_description'];?><?php echo $_lang['zh_mh'];?></th>
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
 <?php if($model_info['on_time']) { ?>
<tr>
                      	 <th scope="row" width="130"><?php echo $_lang['last_modify'];?><?php echo $_lang['zh_mh'];?></th>
                        <td>
                        <?php echo date('Y-m-d H:i:s',$model_info[on_time]) ?>
                        </td>
                      </tr>
                  <?php } ?>
                      <tr>
                        <th scope="row">&nbsp;</th>
                        <td>
                            <div class="clearfix padt10">
                                <button class="positive primary pill button" type="submit" name="sbt_edit" value="<?php echo $_lang['submit'];?>"><span class="check icon"></span><?php echo $_lang['submit'];?></button>
                                <button class="pill button" type="reset" name="rst_edit" value="<?php echo $_lang['return'];?>" onclick="window.history.go(-1)"><span class="uparrow icon"></span><?php echo $_lang['return'];?></button>
                             </div>
                        </td>
                      </tr>
                    </table>
        	</form>
        </div>
   </div>
</div>
<div class="box post" id="div_cont_2" style="display:<?php if($op!='control') { ?>none<?php } else { ?>block<?php } ?>">
<div class="tabcon">
    	<div class="title"><h2><?php echo $_lang['control_config'];?></h2></div>
        <div class="detail">
        	<form name="frm_config_pay" id="frm_config_pay" action="index.php?do=model&model_id=<?php echo $model_id;?>&view=config&op=control" method="post">
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
                   <tr>
                      	 <th scope="row" width="150">过期设定:</th>
                        <td>
<input type="radio" name="overdue_type"  id="forever" value="forever" />不过期<br />
<input type="radio" name="overdue_type" id="overdue_tian2" value="2" /><input type="text"  class="txt" name="overdue_tian" id="overdue_tian" onkeyup="clearstr(this);" />天后<br />
<input type="radio" name="overdue_type" id="custom"  value="custom" />商家自定义
    </td>
                  </tr>
               	  <tr>
                      	 <th scope="row" width="150"><?php echo $_lang['service_trans_charge_rate'];?><?php echo $_lang['zh_mh'];?></th>
                        <td>
                        <input type="text" value="<?php echo $config['service_profit']?>" name="conf[service_profit]" id="service_profit" class="txt" style=" width:260px;" maxlength="100" limit="type:float;required:true;len:1-3" msg="<?php echo $_lang['service_royalty_rate'];?>:1-100" title="<?php echo $_lang['please_input_service_royalty_rate'];?>" msgArea="service_profit_msg" class="txt"/>%<b style="color:red">*</b>
                         <span id="service_profit_msg"></span>
  <div class="padt10 direct">
                            <p>
                            	(<?php echo $_lang['website_royalty_rate_notice'];?>)
                            </p>
                        </div>
    </td>
                  </tr>
   <tr>
                      	 <th scope="row" width="150"><?php echo $_lang['service_submit_min_money'];?><?php echo $_lang['zh_mh'];?></th>
                        <td>
                        <input type="text" value="<?php echo $config['min_cash']?>" name="conf[min_cash]" id="min_cash" class="txt" style=" width:260px;" maxlength="100" limit="type:int;len:1-6" msg="<?php echo $_lang['submit_min_money_can_null'];?>" title="<?php echo $_lang['please_input_min_submit_money'];?>" msgArea="min_cash_msg" class="txt"/><?php echo $_lang['yuan'];?><b style="color:red">*</b>
                         <span id="min_cash_msg"></span>
  <div class="padt10 direct">
                            <p>
                            	(<?php echo $_lang['zero_or_null_no_limit'];?>)
                            </p>
                        </div>
    </td>
                  </tr>
    <tr>
                      	 <th scope="row" width="150">服务验收最长天数<?php echo $_lang['zh_mh'];?></th>
                        <td>
                        <input type="text" value="<?php echo $config['confirm_max_day']?>" name="conf[confirm_max_day]" id="confirm_max_day" class="txt" style=" width:260px;" maxlength="100" limit="type:int;len:1-2;required:true;between:1-" msg="服务验收期最小为1天"  msgArea="max_confirm_msg" class="txt"/>天<b style="color:red">*</b>
                         <span id="max_confirm_msg"></span>
  <div class="padt10 direct">
                            <p>
                            	(服务完成后，X天买家未验收系统会自动验收，并付款给卖家)
                            </p>
                        </div>
    </td>
                  </tr>
                  	<tr>
                      	 <th scope="row" width="150">上传默认图片</th>
                        <td>
<!--<input type="file" class="file" name="upload1" id="upload1">-->
<div id="picker">上传图片</div>
<input type="hidden" name="filepath" id="filepath" />
<div id="list" class="affix-list upload-file-list-info" style="width:210px;"></div>
<p>图片建议大小：高210px 宽210px 图片类型:jpeg,gif,png,jpg </p>
  <div class="padt10 direct">
                        </div>
                        	
    </td>
                  </tr>
                  <tr>
                  		<th scope="row" width="150">图片</th>
                  		<td>
                  			<img src="../tpl/default/img/shop/shop_default_big.png" name="imgPreview1"  id="imgPreview1"  height="100" width="100" />
                  		</td>
                  </tr>
 <!--  <tr>
                      	 <th scope="row" width="150"><?php echo $_lang['service_submit_audit_money'];?><?php echo $_lang['zh_mh'];?></th>
                        <td>
                        <input type="text" value="<?php echo $config['audit_cash']?>" name="conf[audit_cash]" id="audit_cash" class="txt" style=" width:260px;" maxlength="100" limit="type:int;len:1-6" msg="<?php echo $_lang['submit_audit_money_can_null'];?>" title="<?php echo $_lang['please_input_submit_audit_money'];?>" msgArea="audit_cash_msg" class="txt"/><?php echo $_lang['yuan'];?><b style="color:red">*</b>
                         <span id="audit_cash_msg"></span>
  <div class="padt10 direct">
                            <p>
                            	(<?php echo $_lang['zero_or_null_no_limit'];?>)
                            </p>
                        </div>
    </td>
                  </tr>-->
  <!--
   <tr>
                      	 <th scope="row" width="150"><?php echo $_lang['service_each_stage_min_money'];?><?php echo $_lang['zh_mh'];?></th>
                        <td>
                        <input type="text" value="<?php echo $config['step_min_account']?>" name="conf[step_min_account]" id="step_min_account" class="txt" style=" width:260px;" maxlength="100" limit="required:true;type:int;len:1-5" msg="<?php echo $_lang['service_each_stage_min_money'];?>" title="<?php echo $_lang['please_input_allow_min_money'];?>" msgArea="step_min_account_msg" class="txt"/><?php echo $_lang['yuan'];?>
                         <span id="step_min_account_msg"></span>
    </td>
                  </tr>
   <tr>
                      	 <th scope="row" width="150"><?php echo $_lang['service_attachment_number_limit'];?><?php echo $_lang['zh_mh'];?></th>
                        <td>
                        <input type="text" value="<?php echo $config['max_filecount']?>" name="conf[max_filecount]" id="max_filecount" class="txt" style=" width:260px;" maxlength="100" limit="type:int;between:1-20" msg="<?php echo $_lang['service_attachment_number_can_null'];?>" title="<?php echo $_lang['please_input_allow_attachment_number'];?>" msgArea="max_filecount_msg" class="txt"/><?php echo $_lang['counts'];?>
                         <span id="max_filecount_msg"></span>
    </td>
                  </tr>
  -->
                      <tr>
                        <th scope="row">&nbsp;</th>
                        <td>
                            <div class="clearfix padt10">
                                <button class="positive primary pill button" type="submit" name="sbt_edit"  onclick="return checkForm(document.getElementById('frm_config_pay'))" value="<?php echo $_lang['submit'];?>"><span class="check icon"></span><?php echo $_lang['submit'];?></button>
                          		<button type="reset" name="rst_edit"  class="positive primary pill button"  value="<?php echo $_lang['reset'];?>"/><span class="reload icon"></span><?php echo $_lang['reset'];?></button>
   </div>
                        </td>
                      </tr>
                </table>
        	</form>
        </div>
        </div>
    </div>
    <script type="text/javascript">
    var overdue_type="<?php echo $config['overdue_type'];?>";
    $(function(){
    	if(overdue_type=='forever'){
    		$("#forever").attr("checked","checked");
    	}else if(overdue_type=='custom'){
    		$("#custom").attr("checked","checked");
    	}else{
    		$("#overdue_tian2").attr("checked","checked");
    		$("#overdue_tian").val(overdue_type);
    	}
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

<script type="text/javascript">
var baseurl = "<?php echo $basic_config['website_url'];?>";
var uploadsize = parseInt("<?php echo $basic_config['max_size'];?>");
uploadsize =  isNaN(uploadsize)? 1 : uploadsize;
$(function(){
$("#picker").KKUploader({
accept: {
extensions : 'jpg,jpeg,gif,png,bmp'
},
uploadEvents: {
uploadComplete:function(file){
var url = $("#"+file.id).find('.webuploader-pick-file-close').attr('data-fileurl');
        				$('#imgPreview1').removeClass('hidden');
        				$('#imgPreview1').attr('src','<?php echo REVIEW_BACKEND_PATH;?>'+url);
}
},
fileNumLimit:1,
fileSingleSizeLimit:uploadsize*1024*1024,
listName:'list',
hiddenName:'filepath',
hiddenValType: 2
},
{
fileType:'sys',
  		objType:'auth',
filename : 'file',
});
});

</script>
<link rel="stylesheet" type="text/css" href="../static/js/webuploader/webuploader.css">
<script type="text/javascript" src="../static/js/jquery.min.js?r=<?php echo RANDOM_PARA;?>"></script>
<script type="text/javascript" src="../static/js/webuploader/webuploader.js?r=<?php echo RANDOM_PARA;?>"></script>
<script type="text/javascript" src="../static/js/webuploader/kppw.webuploader.js"></script>		
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