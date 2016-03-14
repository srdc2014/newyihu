<?php keke_tpl_class::checkrefresh('admin/tpl/admin_case_list', '1457950035' );?><!DOCTYPE HTML>
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
    <h1><?php echo $_lang['case_manage'];?></h1>
    <div class="tool">
    	 <a class="here" href="index.php?do=<?php echo $do?>&view=list"><?php echo $_lang['case_list'];?></a>
        <a href="index.php?do=<?php echo $do?>&view=add"><?php echo $_lang['case_add'];?></a>
    </div>
</div>
    <div class="box search p_relative">
<div class="title"><h2><?php echo $_lang['search'];?></h2></div>
        <div class="detail" id="detail">
 <form action="index.php?do=<?php echo $do?>&view=list" method="post">
<input type="hidden" name="page" value="<?php echo $page?>">
<table cellspacing="0" cellpadding="0">
<tbody>
<tr>
<th><?php echo $_lang['id'];?></th>
<td><input type="text" value="<?php echo $w['case_id']?>" name="w[case_id]" class="txt"/></td>
<th><?php echo $_lang['case_name'];?></th>
<td><input type="text" value="<?php echo $w['art_title']?>" name="w[art_title]" class="txt"/>*<?php echo $_lang['search_by_like'];?></td>

<th><?php echo $_lang['case_type'];?></th>
<td>
<select name="w[obj_type]" class="ps vm">
<option value=''><?php echo $_lang['all'];?></option>
 									<option <?php if($w['obj_type']=="task") { ?>selected=selected<?php } ?> value='task'><?php echo $_lang['task'];?></option>
<option <?php if($w['obj_type']=="service") { ?>selected=selected<?php } ?> value='service'><?php echo $_lang['shop'];?></option>
         						 </select>
</td>
</tr>
<tr>
 
<th><?php echo $_lang['result_order'];?></th>
                 			 <td>
                    	<select name="w[ord][]">
                           <option value="case_id" <?php if($w['ord']['0']=='case_id' or !isset($w['ord']['0'])) { ?> selected="selected"<?php } ?>><?php echo $_lang['default'];?><?php echo $_lang['order'];?></option>
                           <option value="on_time" <?php if($w['ord']['0']=='on_time' ) { ?> selected="selected"<?php } ?>><?php echo $_lang['up_time'];?></option>
                      </select>
                      <select name="w[ord][]">
                            <option <?php if($w['ord']['1']=='desc' or !isset($w['ord']['1'])) { ?>selected="selected" <?php } ?> value="desc"><?php echo $_lang['desc'];?></option>
                            <option <?php if($w['ord']['1']=='asc') { ?>selected="selected" <?php } ?> value="asc"><?php echo $_lang['asc'];?></option>
                      </select>
                 			  </td>
            			  <th><?php echo $_lang['list_result'];?></th>
                  		 <td colspan="3">
                 			  <select name="w[page_size]">
                         		  <option value="10" <?php if($w['page_size']=='10') { ?>selected="selected"<?php } ?>><?php echo $_lang['page_size'];?>10</option>
                          		  <option value="20" <?php if($w['page_size']=='20') { ?>selected="selected"<?php } ?>><?php echo $_lang['page_size'];?>20</option>
                         		  <option value="30" <?php if($w['page_size']=='30') { ?>selected="selected"<?php } ?>><?php echo $_lang['page_size'];?>30</option>
                    			  </select>
<button class="pill" type="submit" value=<?php echo $_lang['search'];?> name="sbt_search">
                            		<span class="icon magnifier">&nbsp;</span><?php echo $_lang['search'];?>
</button>
</td>
</tr>
</tbody>
</table>	
</form>
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

                    <th width="38%">
                        <?php echo $_lang['case_name'];?>
                    </th>
                    <th width="10%">
                       <?php echo $_lang['case_money'];?>
                    </th>
                    <th width="10%">
                       <?php echo $_lang['case_type'];?>
                    </th>
                    <th width="17%">
                        <?php echo $_lang['pub_time'];?>
                    </th>
                    <th width="15%">
                      <?php echo $_lang['operate'];?>
                    </th>
                </tr>
</thead>
<tbody>
                <?php if(is_array($case_arr)) { foreach($case_arr as $v) { ?>
                <tr class="item">
                	<td>
                		<input type="checkbox" name="ckb[]" class="checkbox" value="<?php echo $v['case_id'];?>"><?php echo $v['case_id'];?>
                	</td>
                   
                    <td>
                     <?php if($v['obj_type']=='service') { ?>
   <a href="../index.php?do=goods&id=<?php echo $v['obj_id']?>" target="_blank">
                        	<?php echo kekezu::cutstr($v['case_title'],54) ?></a>
 <?php } elseif($v['obj_type']=='task') { ?>
   <a href="../index.php?do=task&id=<?php echo $v['obj_id']?>" target="_blank">
                        	<?php echo kekezu::cutstr($v['case_title'],54) ?></a>
 <?php } ?>
  
                    </td>
                    <td>
                        <?php if($v['task_cash']) { ?><?php  echo keke_curren_class::output(floatval($v['task_cash']),-1);  ?> <?php } else { ?><?php  echo keke_curren_class::output(floatval($v['case_price']),-1);  ?><?php } ?>
                    </td>
                    <td>
                        <?php if($v['obj_type']=="task") { ?><?php echo $_lang['task'];?><?php } else { ?><?php echo $_lang['shop'];?><?php } ?>
                    </td>
                    <td>
                        <?php echo date('Y-m-d H:i',$v['on_time']) ?>
                    </td>
                    <td>
                        <a href="index.php?do=case&view=add&case_id=<?php echo $v['case_id'];?>" class="button dbl_target"><span class="pen icon"></span><?php echo $_lang['edit'];?></a>
                       <a href="index.php?do=case&view=list&ac=del&case_id=<?php echo $v['case_id'];?>&page=<?php echo $page?>&w['page_size']=<?php echo $w['page_size'];?>&w['obj_type']=<?php echo $w['obj_type'];?>" onclick="return cdel(this);" class="button"><span class="trash icon"></span><?php echo $_lang['delete'];?></a>
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