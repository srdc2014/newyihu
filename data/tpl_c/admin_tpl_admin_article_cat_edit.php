<?php keke_tpl_class::checkrefresh('admin/tpl/admin_article_cat_edit', '1457950075' );?><!DOCTYPE HTML>
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
   <h1><?php echo $_lang['cat_manage'];?></h1>
         <div class="tool"> 
            <a href="index.php?do=<?php echo $do?>&view=cat_list&type=<?php echo $type?>" <?php if($view=='cat_list') { ?>class="here"<?php } ?> <?php if($view=='list') { ?>class="here" <?php } ?>><?php echo $_lang['cat_list'];?></a>
            <a href="index.php?do=<?php echo $do?>&view=cat_edit&type=<?php echo $type?>" <?php if($view=='cat_edit') { ?>class="here"<?php } ?> <?php if($view=='edit') { ?>class="here" <?php } ?>><?php echo $_lang['cat_add'];?></a>
    	</div>
</div>
<!--页头结束-->
    
<div class="box post">
    <div class="tabcon">
        	<div class="title"><h2><?php if($art_cat_id) { ?><?php echo $_lang['edit'];?><?php } else { ?><?php echo $_lang['add'];?><?php } ?><?php if($type=='art') { ?><?php echo $_lang['article'];?><?php } elseif($type=='help') { ?><?php echo $_lang['help'];?><?php } else { ?><?php echo $_lang['single_page'];?><?php } ?><?php echo $_lang['category'];?></h2></div>
            <div class="detail">
                <form method="post" action="index.php?do=<?php echo $do;?>&view=<?php echo $view;?>" id="frm_cat_edit" >
                <input type="hidden" name="do" value="<?php echo $do;?>">
                <input type="hidden" name="view" value="<?php echo $view;?>">
                <input type="hidden" name="hdn_art_cat_id" value="<?php echo $art_cat_info['art_cat_id'];?>">
                <input type="hidden" name="type" value="<?php echo $type;?>">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <th scope="row" width="130"><?php echo $_lang['father_cat'];?><?php echo $_lang['zh_mh'];?></th>
                        <td>
                        <select id="slt_cat_id" name="slt_cat_id" style=" width:270px;"
                        		 limit = "required:true;type:int" 
                                     msg = '<?php echo $_lang['select_father_cat'];?>' 
                                     title='<?php echo $_lang['cat_what'];?><?php echo $_lang['zh_wh'];?>' 
                                     msgArea="msg_cat_id">
                        <?php if(is_array($cat_arr)) { foreach($cat_arr as $v) { ?>
                        <?php echo $v?>
                        <?php } } ?>
                   		 </select>
                        <span id="msg_cat_id"></span>
                        </td>
                      </tr>
                      
                      <tr>
                        <th scope="row"><?php echo $_lang['cat_name'];?><?php echo $_lang['zh_mh'];?></th>
                        <td> 
                         <input type="text" class="txt" style=" width:260px;" name="txt_cat_name" id="txt_cat_name" value="<?php echo $art_cat_info['cat_name'];?>" maxlength="20" 
                         limit="required:true;len:2-20" 
                         msg="<?php echo $_lang['cat_name_limit'];?>" 
                         msgArea="cat_name_msg" 
                         title="<?php echo $_lang['input_cat_name'];?>"/><span id="cat_name_msg"></span>
                	</td>
                      </tr>
                      
                      <tr>
                        <th scope="row"><?php echo $_lang['order'];?><?php echo $_lang['zh_mh'];?></th>
                        <td>
                         <input type="text"  class="txt" style=" width:260px;" id="txt_listorder" name="txt_listorder" value="<?php if($art_cat_info['listorder']) { ?><?php echo $art_cat_info['listorder'];?><?php } else { ?>1<?php } ?>" maxlength="5" 
limit = "required:true;type:int" 
                                onkeyup="clearstr(this)"
                                msg = '<?php echo $_lang['please_enter'];?><?php if($type=='art') { ?><?php echo $_lang['article'];?><?php } elseif($type=='help') { ?><?php echo $_lang['help'];?><?php } else { ?><?php echo $_lang['single_page'];?><?php } ?><?php echo $_lang['category'];?>{lang:order' 
                                title='<?php if($type=='art') { ?><?php echo $_lang['article'];?><?php } elseif($type=='help') { ?><?php echo $_lang['help'];?><?php } else { ?><?php echo $_lang['single_page'];?><?php } ?><?php echo $_lang['category_order'];?>' 
                                msgArea="slt_txt_listorder"/><span id="slt_txt_listorder"></span>
                        </td>
                      </tr>
                      
                      <?php if($type =='help') { ?>
                      
  <tr>
                        <th scope="row">SEO<?php echo $_lang['title'];?><?php echo $_lang['zh_mh'];?></th>
                        <td><textarea cols="70" rows="2"  name="seo_title" value="<?php echo $art_cat_info['seo_title'];?>"><?php echo $art_cat_info['seo_title'];?></textarea></td>
                     </tr>
                     
                     <tr>
                        <th scope="row">SEO<?php echo $_lang['keywords'];?><?php echo $_lang['zh_mh'];?></th>
                        <td><textarea cols="70" rows="2"  name="seo_keyword" value="<?php echo $art_cat_info['seo_keyword'];?>"><?php echo $art_cat_info['seo_keyword'];?></textarea></td>
                     </tr>
                     
                      <tr>
                        <th scope="row">SEO<?php echo $_lang['description'];?><?php echo $_lang['zh_mh'];?></th>
                        <td><textarea cols="70" rows="3" name="seo_desc" value="<?php echo $art_cat_info['seo_desc'];?>"><?php echo $art_cat_info['seo_desc'];?></textarea></td>
                     </tr>
                     <?php } ?>
  <tr style="display:none;">
                        <th scope="row"><?php echo $_lang['is_recommend'];?><?php echo $_lang['zh_mh'];?></th>
                        <td>
                          <p>
                            <label><input type="checkbox" value="1" name="chk_is_show" <?php if($art_cat_info['is_show']==1) { ?>checked="checked"<?php } ?>/>&nbsp;<?php echo $_lang['yes'];?></label> <br />
                          </p>
                        </td>
                      </tr>
                      				
<?php if($art_cat_info['on_time']) { ?>
 <tr>
                        <th scope="row"><?php echo $_lang['last_modify'];?><?php echo $_lang['zh_mh'];?></th>
                        <td><?php if($art_cat_info['on_time']){echo date('Y-m-d H:i:s',$art_cat_info['on_time']); } ?></td>
                     </tr>
                    <?php } ?> 
                    
                    <tr>
                        <th scope="row">&nbsp;</th>
                    	<td>
                    	<button name="sbt_edit" value="1" onclick="return checkForm(document.getElementById('frm_cat_edit'),false)" class="pill positive primary  button" type="submit"><span class="check icon"></span><?php echo $_lang['submit'];?></button>
                    	<button class="pill button" type="button" onclick="history.go(-1);" value="<?php echo $_lang['return'];?>"><span class="uparrow icon"></span><?php echo $_lang['return'];?></button>
                    	</td>
                    </tr>
                    
                    </table>
                </form>
             </div>
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