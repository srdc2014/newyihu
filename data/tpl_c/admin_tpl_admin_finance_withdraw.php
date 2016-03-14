<?php keke_tpl_class::checkrefresh('admin/tpl/admin_finance_withdraw', '1457949968' );?><!DOCTYPE HTML>
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
    <h1><?php echo $_lang['financial_model'];?></h1>
    <div class="tool">
    	<a href="index.php?do=<?php echo $do;?>&view=revenue">财务概况</a>
    	<a href="index.php?do=<?php echo $do;?>&&view=budget">网站收支</a>
<a href="index.php?do=<?php echo $do;?>&view=all"><?php echo $_lang['flow_record'];?></a>
    <a href="index.php?do=<?php echo $do;?>&view=recharge"><?php echo $_lang['recharge_audit'];?></a>
   <a href="index.php?do=<?php echo $do;?>&view=withdraw" class="here"><?php echo $_lang['withdraw_audit'];?></a>
    </div>
</div>
<div class="box tip clearfix p_relative" id="man_tips">
   <div class="control"><a href="javascript:void(0);" title=<?php echo $_lang['close'];?> onclick="$('#man_tips').fadeOut();"><b>&times;</b></a></div>
   <div class="title"><h2><?php echo $_lang['tips'];?></h2></div>
   <div class="detail pad10">
      <ul>
         <li><?php echo $_lang['system_info'];?></li>
         <li><?php echo $_lang['search_info'];?></li>
      </ul>
   </div>
</div>
<div class="box search p_relative">
    <div class="title">
        <h2><?php echo $_lang['search'];?></h2>
    </div>
    <div class="detail" id="detail">
        <form action="index.php?do=<?php echo $do;?>&view=withdraw" method="post" id=frm_user_search>
        	<input type="hidden" value="<?php echo $page;?>" name="page">
        	 <table cellspacing="0" cellpadding="0">
        	 	
                <tbody>
                    <tr>
                    	 <th>
                            <?php echo $_lang['financial_id'];?>
                        </th>
                        <td>
                            <input type="text" value="<?php echo $w['withdraw_id'];?>" name="w[withdraw_id]" class="txt" onkeyup="clearstr(this);"/>
                        </td>
 <th>
                            <?php echo $_lang['username'];?>
                        </th>
                        <td>
                            <input type="text" value="<?php echo $w['username'];?>" name="w[username]" class="txt"/>
                        </td>
 <th>
                         <?php echo $_lang['payment_account_type'];?>
                        </th>
                        <td>
                            <select name="w[pay_type]">
                            	<option value=''><?php echo $_lang['all'];?></option>
<?php if(is_array($paytype_list)) { foreach($paytype_list as $k => $v) { ?>
<?php $config = unserialize($v['config']); ?>
<?php if($config['pay_status']) { ?>
<option <?php if($w['pay_type']==$k) { ?>selected<?php } ?> value="<?php echo $k;?>"><?php echo $k;?></option>
<?php } ?>
                                <?php } } ?>
                            </select>
                        </td>
   
</tr>
<tr>
                        <th>
                            <?php echo $_lang['result_order'];?>
                        </th>
                        <td>
                      <select name="w[ord][]">
                           <option value="withdraw_id" <?php if($w['ord']['0']=='withdraw_id' or !isset($w['ord']['0'])) { ?> selected="selected"<?php } ?>><?php echo $_lang['default'];?><?php echo $_lang['order'];?></option>
                           <option value="applic_time" <?php if($w['ord']['0']=='applic_time' ) { ?> selected="selected"<?php } ?>><?php echo $_lang['application_time'];?></option>
                           <option value="process_time" <?php if($w['ord']['0']=='process_time' ) { ?> selected="selected"<?php } ?>><?php echo $_lang['processing_time'];?></option>
                      </select>
                      <select name="w[ord][]">
                            <option <?php if($w['ord']['1']=='desc' or !isset($w['ord']['1'])) { ?>selected="selected" <?php } ?> value="desc"><?php echo $_lang['desc'];?></option>
                            <option <?php if($w['ord']['1']=='asc') { ?>selected="selected" <?php } ?> value="asc"><?php echo $_lang['asc'];?></option>
                      </select>
                       </td>
                        <th>
                                                    <?php echo $_lang['list_result'];?>
                         </th>
                        <td>
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
<!--搜索结束-->
<div class="box list">
    <div class="title">
        <h2><?php echo $_lang['statistical_info'];?></h2>
    </div>
     <div class="detail">
        <form action="index.php?do=<?php echo $do;?>&view=withdraw" method="post" id='frm_art_action' >
 	<input type="hidden" value="<?php echo $page;?>" name="page">
<input type="hidden" name="w[page_size]" value="<?php echo $page_size;?>">
<div id="ajax_dom"><input type="hidden" value="<?php echo $page;?>" name="page">
  <table cellpadding="0" cellspacing="0">
             <tbody>
               <tr>
               	   <th>
<input type="checkbox" id="checkbox" onclick="checkall();" class="checkbox" >编号
</th>
                    <th>
                       <?php echo $_lang['withdraw_type'];?>
</th>
                    <th>
                        <?php echo $_lang['withdraw_people'];?>
                    </th>
                    <th>
                       <?php echo $_lang['real_withdraw_amount'];?>/ <?php echo $_lang['withdraw_amount'];?>
                    </th>
                    <th>
                        <?php echo $_lang['payment_accoun'];?>
                    </th>
                    <th>
                        <?php echo $_lang['collection_name'];?>
                    </th>
                    <th>
                        <?php echo $_lang['withdraw_state'];?>
                    </th>
                    <th>
                        <?php echo $_lang['operate'];?>
                    </th>
                    <th>
                        <?php echo $_lang['view'];?>
                    </th>
                </tr>
  				 <?php if(is_array($withdraw_arr)) { foreach($withdraw_arr as $key => $v) { ?>
                <tr class='item'>
                    <td>
                        <input type="checkbox" name="ckb[]" id="ckb_<?php echo $v['withdraw_id']?>" class="checkbox" value="<?php echo $v['withdraw_id'];?>"><?php echo $v['withdraw_id'];?>
                    </td>
                    <td>
                        <?php if($v['pay_type']=='alipayjs') { ?>
<?php echo $_lang['alipay'];?>
<?php } else { ?>
<?php echo $bank_arr[$v['pay_type']]?>
<?php } ?>
                    </td>
                    <td>
                        <?php echo $v['username'];?>
                    </td>
                    <td>
                        <font color="red">
                        	<?php if($v['pay_type']=='alipayjs') { ?>
                        		<?php $get_cash = keke_finance_class::alipayjs_format_moneys($v['withdraw_cash']); ?>
                        	<?php } else { ?>
                        		<?php $get_cash = keke_finance_class::get_to_cash($v['withdraw_cash']); ?>
                        	<?php } ?>
<?php  echo keke_curren_class::output(floatval($v['withdraw_cash']),-1);  ?>/<?php  echo keke_curren_class::output(floatval($get_cash),-1);  ?>
</font>
                    </td>
                    <td>
                        <?php echo $v['pay_account'];?>
                    </td>
                    <td>
                        <?php echo $v['pay_username'];?>
                    </td>
                    <td>
                    	<?php echo $status_arr[$v['withdraw_status']];?>
                    </td>
                     <td>
                    	<?php if($v['withdraw_status']==1) { ?>
               			<a href="index.php?do=<?php echo $do;?>&view=<?php echo $view;?>&ac=pass&withdraw_id=<?php echo $v['withdraw_id'];?>&page=<?php echo $page;?>&is_submit=1" class="button">
               			<span class="check  icon"></span><?php echo $_lang['confirm_cash'];?></a>
                        <a href="javascript:void(0)" data-id="<?php echo $v['withdraw_id'];?>" data-page="<?php echo $page;?>" onclick="c_confirm(this);" class="button"><?php echo $_lang['nopass'];?></a>
                    	 <?php } ?>
  </td>
   <td>
                      	 <a href="index.php?do=<?php echo $do;?>&view=<?php echo $view;?>&ac=pass&withdraw_id=<?php echo $v['withdraw_id'];?>&page=<?php echo $page;?>" class="button dbl_target"><span class="book icon"></span><?php echo $_lang['view'];?></a>
                   		</td>
 </tr>
 <?php } } ?>
                    <tr>
                    <td colspan="9">
                               
                           <div class="clearfix">
                           	 
 <input type="hidden" name="action_type" id="action_type" value="0" />
 
<label for="checkbox" onclick="checkall(event);"><?php echo $_lang['select_all'];?></label>

 <input type="hidden" name="sbt_action" class="sbt_action"/>   
 <?php if($w['pay_type']!='alipayjs') { ?>
 <button name="sbt_action" type="submit" value="<?php echo $_lang['mulit_review'];?>" onclick="return batch_act(this,'frm_art_action');" class="pill positive button">
                                      <span class="icon check"></span><?php echo $_lang['mulit_review'];?>
                                 </button>
<?php } else { ?>
 <button name="sbt_action" type="button" value="<?php echo $_lang['mulit_payment'];?>" onclick="batch_pay();" class="pill positive button">
                                      <span class="icon check"></span><?php echo $_lang['mulit_payment'];?>
                                 </button>
<?php } ?> 
                           		 <button name="sbt_action" type="submit" value="<?php echo $_lang['mulit_nopass'];?>" onclick="return batch_act(this,'frm_art_action');" class="pill negative button">
                                      <span class="icon trash"></span><?php echo $_lang['mulit_nopass'];?>
                                 </button>
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
<script type="text/javascript">
function batch_pay(withdraw_id){
var ids;

if(withdraw_id){ 
$("#ckb_"+withdraw_id).attr("checked","checked");
}

if($(":checkbox[name='ckb[]']:checked").length>0){
$(":checkbox[name='ckb[]']:checked").each(function(i,n){
i==0?ids=$(this).val():ids+=","+$(this).val();
})
if(ids&&typeof(ids)!=undefined){
$.post("index.php?do=finance&view=withdraw&type=batch&pay_type=alipayjs&ids="+ids,function(url){
art.dialog({
title:"<?php echo $_lang['confirm_payment'];?>",
content: "<?php echo $_lang['you_confirm_payment'];?>",
   				yesFn: function(){
window.open(url);this.close();
},
noFn :function(){this.close();return false;}
});
},'text')
 }
}else{
art.dialog.alert("<?php echo $_lang['have_no_choice_play_money'];?>");
}
}
$(function(){
$(".control").add(".title").click(function(){
$(".tip").children().not($(".control,.title")).slideToggle('800');
});
});
function c_confirm(o){
var obj  =$(o); 
var withdraw_id  = obj.attr('data-id'); 
var page  = obj.attr('data-page'); 
var url= 'index.php?do=ajax&frompage=finance_withdraw&ac=nopass&page='+page+'&withdraw_id='+withdraw_id;
art.dialog.open(url,{
title:"审核不通过的原因",
height:300,
width:600,
closeFn:function (){
var ispass=art.dialog.data('ispass');
var url=art.dialog.data('url');
if(ispass=='ok'){
art.dialog.data('ispass','no');
art.dialog.data('url',null);
 	window.location.href=url; 
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