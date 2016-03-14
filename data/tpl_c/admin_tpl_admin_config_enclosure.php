<?php keke_tpl_class::checkrefresh('admin/tpl/admin_config_enclosure', '1457950096' );?><!DOCTYPE HTML>
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
<div class="box post">
<div class="tabcon">
    	<div class="title"><h2>附件配置</h2></div>
        <div class="detail">
<form name="frm_config_enclosure" id="frm_config_enclosure" action="index.php?do=config&view=enclosure" method="post" accept-charset="<?php echo $_K['charset'];?>" enctype='multipart/form-data'>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
        <th scope="row">附件大小限制<?php echo $_lang['zh_mh'];?></th>
        <td>
        	<input type="text" size="50" name="max_size" value="<?php echo $arrBasicConfig['max_size'];?>" limit="required:true;type:int;len:1-2" value="2" msg="附件大小限制" msgArea="txt_max_size_msg" class="txt"/><b style="color:red">*</b>
            <span id="txt_max_size_msg"></span>MB 系统配置值：2M，如果设定值超过系统配置值，则以系统配置值为准,该配置生效需配合php.ini,<a href="http://bbs.kppw.cn/thread-32292-1-1.html" target="_blank">查看详细</a>
        </td>
    </tr>
    <tr>
    	<th scope="row">
        	附件格式<?php echo $_lang['zh_mh'];?>
        </th>
        <td>
            <input type="text" size="50" name="file_type" value="<?php echo $arrBasicConfig['file_type'];?>" limit="required:true;len:3-100;general:false" msg="<?php echo $_lang['attachment_format_msg'];?><?php echo $_lang['zh_mh'];?>3-100" msgArea="txt_file_type_msg" class="txt"/><b style="color:red">*</b>
            <span id="txt_file_type_msg"></span>如果有多个关键字，(zip|rar|jpg|gif|png)(不含引号) 等符号分隔
        </td>
    </tr>
    <tr>
                <th scope="row">
                    	使用网盘：
                </th>
                <td>
                    <label for="upload_type1">
                        <input type="radio" id="upload_type1" name="upload_type" <?php if($arrBasicConfig['upload_type']==1) { ?>checked="checked"<?php } ?> value="1" /><?php echo $_lang['close'];?>
                    </label>
                    <label for="upload_type2">
                        <input type="radio" id="upload_type2" name="upload_type" <?php if($arrBasicConfig['upload_type']==2) { ?>checked="checked"<?php } ?> value="2" /><?php echo $_lang['open'];?>
                    </label>
                    <br/>&nbsp;&nbsp;本系统自动接入七牛云网盘，<a href="#">查看教程</a>
                </td>
            		</tr>
            		<tr id="access_key" <?php if($arrBasicConfig['upload_type']==1) { ?>class="hidden"<?php } ?>>
        <th scope="row">Access Key：</th>
        <td>
        	<input type="text" size="50" name="access_key" value="<?php echo $arrBasicConfig['access_key'];?>" class="txt"/><b style="color:red">*</b>
            <span id="txt_secret_key_msg"></span>网盘未开启状态可不填
        </td>
    </tr>
    <tr id="secret_key" <?php if($arrBasicConfig['upload_type']==1) { ?>class="hidden"<?php } ?>>
        <th scope="row">Secret Key：</th>
        <td>
        	<input type="text" size="50" name="secret_key" value="<?php echo $arrBasicConfig['secret_key'];?>" class="txt"/><b style="color:red">*</b>
            <span id="txt_secret_key_msg"></span>网盘未开启状态可不填
        </td>
    </tr>
    <tr id="qn_bucket" <?php if($arrBasicConfig['upload_type']==1) { ?>class="hidden"<?php } ?>>
        <th scope="row">bucket（网盘空间名称）：</th>
        <td>
        	<input type="text" size="50" name="qn_bucket" limit="required:true" value="<?php echo $arrBasicConfig['qn_bucket'];?>" msg="请填写网盘空间名称" msgArea="txt_qn_bucket_msg" class="txt"/><b style="color:red">*</b>
            <span id="txt_qn_bucket_msg"></span>网盘未开启状态可不填
        </td>
    </tr>
    <tr id="qn_domain" <?php if($arrBasicConfig['upload_type']==1) { ?>class="hidden"<?php } ?>>
        <th scope="row">domain（网盘空间绑定域名）：</th>
        <td>
        	<input type="text" size="50" name="qn_domain" limit="required:true" value="<?php echo $arrBasicConfig['qn_domain'];?>" msg="请填写网盘空间绑定的域名" msgArea="txt_qn_domain_msg" class="txt"/><b style="color:red">*</b>
            <span id="txt_qn_domain_msg"></span>网盘未开启状态可不填
        </td>
    </tr>
    <tr>
    <th scope="row">&nbsp;</th>
<td>
<div class="clearfix padt10">
                	<button class="positive pill primary button" type="submit" name='submit' value="<?php echo $_lang['submit'];?>"><span class="check icon"></span><?php echo $_lang['submit'];?></button>
                </div>
</td></tr>
</table>
</form>
</div>
</div>
</div>

<script type="text/javascript">
$(function(){
$("#upload_type1").click(function(){
$("#access_key").addClass("hidden");
$("#secret_key").addClass("hidden");
$("#qn_bucket").addClass("hidden");
$("#qn_domain").addClass("hidden");
});
$("#upload_type2").click(function(){
$("#access_key").removeClass("hidden");
$("#secret_key").removeClass("hidden");
$("#qn_bucket").removeClass("hidden");
$("#qn_domain").removeClass("hidden");
});
});
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