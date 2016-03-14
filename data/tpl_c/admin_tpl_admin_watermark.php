<?php keke_tpl_class::checkrefresh('admin/tpl/admin_watermark', '1457949839' );?><!DOCTYPE HTML>
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
<div class="box clearfix" id = "body">
<form method="post" action="index.php?do=watermark" id="frm_hw_add">
<input type="hidden" name="do" value="<?php echo $do;?>">
<input type="hidden" name="view" value="<?php echo $view;?>">
<input type="hidden" name="submit" value="1">
<div class="box search p_relative">
    	<div class="detail" id="detail">
    		<table cellspacing="0" cellpadding="0">
<tbody>
<tr>
<th style="width:40px;">水印开启：</th>
<td>
<input type="radio" name="switch" value="1" <?php if($config['switch']) { ?>checked<?php } ?>>开启
<input type="radio" name="switch" value="0" <?php if(!$config['switch']) { ?>checked<?php } ?>>关闭
<span style="color:red;">水印默认打在右下角</span>


</td>
</tr>
<tr>
<th style="width:40px;">生效限制：</th>
<td>
宽：<input type="text" class="txt" name="width" id = "width" value="<?php echo $config['width'];?>" onkeyup="clearstr(this);">
</td>
</tr>
<tr>
<th style="width:40px;"></th>
<td>
高：<input type="text" class="txt" name="hight" id ="hight" value="<?php echo $config['hight'];?>" onkeyup="clearstr(this);">
</td>
</tr>
<tr>
<th style="width:40px;"></th>
<td>
<span style="color:red;">低于这个范围的图片不加水印</span>
</td>
</tr>
<tr>
<th style="width:40px;">水印图片：</th>
<td>
<!--<input type="file" class="file" name="upload1" id="upload1">-->
<div id="picker">上传图片</div>
<div id="list1" class="affix-list upload-file-list-info" style="width:210px;"></div>
  			<a href="../<?php echo $config['img']?>" id="imgPreviewA" target="_blank" <?php if(!file_exists('../'.$config['img'])) { ?> class="hidden" <?php } ?>  style="max-height:60px; display:inline-block"  >
  				<img id="imgPreview1" src="../<?php echo $config['img'];?>" <?php if(!file_exists('../'.$config['img'])) { ?> class="hidden" <?php } ?> style="max-height:60px;"   />
</a>
  			<input type="hidden" name="filepath1" id="filepath1" value="">
</td>
</tr>
<tr>
<td colspan="2">
           				<button class="pill" type="submit" value="保存" name="sbt_add">
<span class="icon magnifier">&nbsp;</span>保存
</button>
　
<span style="color:red;">(在开启七牛云网盘时不可用)</span>
</td>
</tr>
           		</tbody>
</table>
    	</div>
    </div>
</form>
</div>

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
    $('#imgPreviewA').removeClass('hidden');
    $('#imgPreview1').attr('src','<?php echo REVIEW_BACKEND_PATH;?>'+url);
    $('#imgPreviewA').attr('href','<?php echo REVIEW_BACKEND_PATH;?>'+url);
}
},
fileNumLimit:1,
fileSingleSizeLimit:uploadsize*1024*1024,
listName:'list1',
hiddenName:'filepath1',
hiddenValType: 2
},
{
fileType:'sys',
  	objType:'tools',
filename : 'file',
});
});

</script>
<link rel="stylesheet" type="text/css" href="tpl/js/webuploader/webuploader.css">
<script type="text/javascript" src="../static/js/jquery.min.js?r=<?php echo RANDOM_PARA;?>"></script>
<script type="text/javascript" src="tpl/js/webuploader/webuploader.js?r=<?php echo RANDOM_PARA;?>"></script>
<script type="text/javascript" src="tpl/js/webuploader/kppw.webuploader.js"></script>
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