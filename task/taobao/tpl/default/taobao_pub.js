/**
 * 
 */
	// 提交
	function stepCheck_tao(model_id){
		var model_id = model_id;
		var pass     = true;
		// 判断是否一个平台都没选择
		var sinaObj = $('#delivery_platform_sina');
		var tenObj = $('#delivery_platform_ten');
			if(sinaObj.attr('checked')==false&&tenObj.attr('checked')==false){
				tipsAppend('platform',"请选择微博平台",'warning','orange');
				pass = false;
			}else{
				con= contentCheck('tar_content','新微博','50','140',1,'length_show');
				if(con==''){pass=false;}
			}
		pass==true&&stepCheck();
	}
	$(function(){
		var nAt  = parseInt('{$r_info[new_is_at]}');
			if(nAt){
				$('#new_at_demand').attr('checked',true);
				$('#span_new_at_num').show();
			}
			$('#new_at_demand').click(function(){
				$('#span_new_at_num').toggle();
			})
		 
	})
	$(function(){
			var taoUrl= $('#taobao_url');
			if(wb_img!=''){
				$('#div_wb_img').show();
				$('#wb_img').attr('src',wb_img);
			}else{
				$('#div_wb_img').hide();
			}
			$('#zf_1').click(function(){
				$('#get_prom_type').children('label').children('strong').text('掌柜名称：');
				if(taoUser.val()){
					taoButtonShow('tao_info','next');
				}
			});
			$('#zf_2').click(function(){
				$('#get_prom_type').children('label').children('strong').text('商品链接：');
				if(taoUrl.val()){
					taoButtonShow('tao_goods','prev');
				}	
			})
			taoUrl.blur(function(){
				var zf_obj = $(":radio[name='zf_obj']:checked").val();
				if(taoUrl.val()){
					if(typeof(zf_obj)=='undefined'||zf_obj=='shop'){
						taoButtonShow('tao_info','next');
					}else{
						taoButtonShow('tao_goods','prev');
					}
				}
			})
	})
	function taoButtonShow(obj,direction){
		var obj = $('#'+obj);
			obj.removeClass('hidden');
			direction=='next'?obj.next().addClass('hidden'):obj.prev().addClass('hidden');
						
	}
	function taoInfo(){
		var user = '';
			if(user = taoUser()){
				$.getJSON(basic_url,{api:'shop',nick:user},function(json){
					if(json.status){
						var info = json.data.shop;
						var purl = "http://shop"+info.sid+".taobao.com";
						var str  = '店铺名称:'+info.title;
							assign==1?str  += "\n店铺地址:"+purl:'';
							str  +="\n店铺简介:";
							info.desc!=''?str+=info.desc:str+='暂无';
							if(info.pic_path!=''){
								pic = 'http://logo.taobaocdn.com/shop-logo'+info.pic_path;
								$('#div_wb_img').show().children('strong').html('淘宝店标：');
								$('#div_wb_img img#wb_img').attr('src',pic);
								$("input[name='wb_img']").val(pic);
							}
							$('#div_prom_url').show().find('strong').html('店铺地址：');
							$('#prom_url').val(purl);
							$('#tar_content').html(str);
					}else{
						showDialog('店铺信息获取失败','alert','操作提示');
					}
				})
			}
	}
	function taoGoods(){
			//var user = '';
		        var id = taoUrl();
				if(id){
						$.getJSON(basic_url,{api:'info',id:id},function(json){
							if(json.status){
								
								var info = json.data.item;
								var purl = info.detail_url;
								var str  = "商品名称:"+info.title;
								str  += "\n商品价格:"+info.price;
								assign==1?str+="\n商品链接:"+info.detail_url:'';
									if(info.pic_url!=''){
										pic = info.pic_url;
										$('#div_wb_img').show().children('label').children('strong').text('商品图片：');
										$('#div_wb_img img#wb_img').attr('src',pic);
										$("input[name='wb_img']").val(pic);
									}
									$('#div_prom_url').show().children('label').find('strong').html('商品地址：');
									//alert($('#div_prom_url div').html());
									$('#div_wb_img input#prom_url').val(purl);
									$('#tar_content').html(str);
							}else{
								showDialog('商品信息获取失败','alert','操作提示');
							}
						})
				
			}
	}
	function taoUser(){
		var taoUser = $('#taobao_url').val();
			if(!taoUser){
				showDialog('请填写淘宝掌柜名称','alert','操作提示',function(){
					$('#taobao_url').focus();
			});
		}
		return taoUser;
	}
	function taoUrl(){
		var taoUrl = $('#taobao_url').val();
			if(!taoUrl){
				showDialog('请填写淘宝商品url','alert','操作提示',function(){
					$('#taobao_url').focus();
			});
		}else{
			var parames = parse_url(taoUrl);
		}
		return parames['id'];
	}
	function parse_url(url){ //定义函数
		 var pattern = /(\w+)=(\w+)/ig;//定义正则表达式
		 var parames = {};//定义数组
		 url.replace(pattern, function(a, b, c){
		  parames[b] = c;
		 });
		 return parames;//返回这个数组.
		}
		