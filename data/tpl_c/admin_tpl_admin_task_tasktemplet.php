<?php keke_tpl_class::checkrefresh('admin/tpl/admin_task_tasktemplet', '1457950101' );?><!DOCTYPE HTML>
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
    <h1>任务模板</h1>
    <div class="tool">
    	 <a href=javascript:temedit("index.php?do=task&view=tasktemplet&ac=add"); void(0);>添加模板</a>
    </div>
</div>

<div class="box list">
    	<div class="title"><h2><?php echo $_lang['reply_list'];?></h2></div>
        <div class="detail">
  		<form action="index.php?do=<?php echo $do;?>&view=<?php echo $view;?>" id='frm_list' method="post">
  			<input type="hidden" name="page" value="<?php echo $page;?>">
<input type="hidden" name="w[page_size]" value="<?php echo $w['page_size'];?>">
<div id="ajax_dom">
  			<table  cellpadding="0" cellspacing="0">
  				<thead>
  					 <tr>
  					 <th>
<input type="checkbox" id="checkbox" onclick="checkall();" class="checkbox" >编号
</th>

                    <th width="10%">
                        模板名
                    </th>
                    <th width="10%">
                      标题
                    </th>
                    <th width="38%">
                      内容
                    </th>
                    <th width="15%">
                      <?php echo $_lang['operate'];?>
                    </th>
                </tr>
</thead>
<tbody>
                <?php if(is_array($tasktem_arr)) { foreach($tasktem_arr as $v) { ?>
                <tr class="item">
                	<td>
                		<input type="checkbox" name="ckb[]" class="checkbox" value="<?php echo $v['id'];?>"><?php echo $v['id'];?>
                	</td>
                   
                    <td>
<?php echo $v['template_name'];?>
                    </td>
                    <td>
                    <?php echo $v['template_title'];?>
                    </td>
                    <td>
                    <?php echo kekezu::cutstr(strip_tags(html_entity_decode(str_replace("&nbsp;","",$v['template_content']))),150); ?>
                    </td>
                    <td>
                        <a href=javascript:temedit("index.php?do=task&view=tasktemplet&tasktemid=<?php echo $v['id']?>&ac=edit"); void(0); class="button dbl_target"><span class="pen icon"></span><?php echo $_lang['edit'];?></a>
                       <a href="index.php?do=task&view=tasktemplet&ac=del&tasktemid=<?php echo $v['id']?>" onclick="return cdel(this);" class="button"><span class="trash icon"></span><?php echo $_lang['delete'];?></a>
                    </td>
                </tr>
<?php } } ?>
</tabody>
<tfoot>
 <tr>
                    <td colspan="5">
                    

                       <label for="checkbox" onclick="checkall(event);"><?php echo $_lang['select_all'];?></label>
                        <input type="hidden" name="sbt_action" class="sbt_action"/>
                        <button name="sbt_action" type="submit" value=<?php echo $_lang['mulit_delete'];?> onclick="return batch_act(this,'frm_list');" class="pill negative" ><span class="icon trash"></span><?php echo $_lang['mulit_delete'];?></button>

</td>	
</tr>		
</tfoot>
  			</table>
<div class="page"><?php echo $pages['page'];?></div>
</div>
</form>
      </div>
</div>	 
<script type="text/javascript">
function temedit(url){
art.dialog.open(url,{
title:"新建模板",
height:700,
width:800,
closeFn:function (){
var ispass=art.dialog.data('ispass');
if(ispass=='ok'){
var template_title = art.dialog.data('template_title');
var tasktemid=art.dialog.data("tasktemid");
var template_content = art.dialog.data('template_content');
var template_name = art.dialog.data('template_name');
var ac = art.dialog.data("ac");
art.dialog.data('ispass','no');
 	//window.location.href='index.php?do=task&view=tasktemplet_edit&tasktemid='+tasktemid+'&is_submit=1'+'&template_title='+template_title+'&template_content="'+template_content+'"&template_name='+template_name+'&ac='+ac; 
 	$.post('index.php?do=task&view=tasktemplet_edit&tasktemid='+tasktemid+'&is_submit=1'+'&template_title='+template_title+'&template_name='+template_name,{"template_content":template_content,"ac":ac},function(data){
 		window.location.href="index.php?do=task&view=tasktemplet&ac=freshen";
 	})
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
</html>
<?php keke_tpl_class::ob_out();?>