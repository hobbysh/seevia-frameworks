<?php 
/*****************************************************************************
 * SV-Cart 商店设置管理列表
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
?>
<style type="text/css">
 
	.am-panel-title div{font-weight:bold;}
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}	
	.am-form-label{top:5px;}

</style>

<?php //pr($configs_list) ?>
<div class="" style="margin-top:10px;">
	<?php echo $form->create('Config',array('action'=>'/','name'=>'PointForm','id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();'));?>
		<div>
			<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1 am-thumbnails">
				<li>
					<label class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label am-text-left">
							<?php echo $ld['group']?>
					</label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						<select  id="show_name" data-am-selected>
							<option value="all"><?php echo $ld['all']?></option>
							<?php if(isset($config_group_code) && sizeof($config_group_code)>0){?>
							<?php foreach( $config_group_code as $k=>$v ){?>
								<option value="<?php echo $k; ?>" <?php if($k == $show_name){echo "selected";}?>><?php echo $v; ?></option>
							<?php }?><?php }?>
						</select>
					</div>
				</li>
				<li>
					<label class="am-u-lg-3 am-u-md-4 am-u-sm-4  am-form-label am-text-left">
							html <?php echo $ld['type']?>
					</label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						<select  id="log_type" data-am-selected>
							<option value="all"><?php echo $ld['all']?></option>
							<option value="text" <?php if($log_type == "text"){echo "selected";}?>>text</option>
							<option value="radio"<?php if($log_type == "radio"){echo "selected";}?>>radio</option>
							<option value="select"<?php if($log_type == "select"){echo "selected";}?>>select</option>
							<option value="checkbox"<?php if($log_type == "checkbox"){echo "selected";}?>>checkbox</option>
							<option value="textarea"<?php if($log_type == "textarea"){echo "selected";}?>>textarea</option>
							<option value="image"<?php if($log_type == "image"){echo "selected";}?>>image</option>
							<option value="hidden"<?php if($log_type == "hidden"){echo "selected";}?>>hidden</option>
							<option value="map"<?php if($log_type == "map"){echo "selected";}?>>map</option>
							<option value="send_email_test" <?php if($log_type=="send_email_test"){echo "selected";} ?> >
								send email test</option>
						</select>
					</div>
				</li>
				<li>
					<label class="am-u-lg-3 am-u-md-4 am-u-sm-4  am-form-label am-text-left">
							<?php echo $ld['versions']?>
					</label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						<select  id="version" data-am-selected>
							<option value="all"><?php echo $ld['all']?></option>
							<option value="O2O" <?php if($version == "O2O"){echo "selected";}?>>O2O</option>
							<option value="AllInOne"<?php if($version == "AllInOne"){echo "selected";}?>>AllInOne</option>
						</select>
					</div>
				</li>
				<li>
					<label class="am-u-lg-3 am-u-md-4 am-u-sm-4  am-form-label am-text-left">
							<?php echo $ld['subparameter'];?> 
					</label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						<input type="text" name="sub_group" id="sub_group" class="am-form-field am-radius am-input-sm" value="<?php echo $sub_group;?>" />
					</div>
				</li>
				<li>
					<label class="am-u-lg-3 am-u-md-4 am-u-sm-4  am-form-label am-text-left">
							<?php echo $ld['keyword'];?>
					</label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						<input type="text" name="config_keywords" id="config_keywords" class="am-form-field am-radius am-input-sm" value="<?php echo $config_keywords;?>"  placeholder="<?php echo $ld['code']?>/<?php echo $ld['name']?>"/>
					</div>
				</li>
				<li>
					<div class="am-u-lg-3 am-u-md-4 am-u-sm-4 ">
						<button type="button" class="am-btn am-btn-success am-btn-sm am-radius search_article"  value="" onclick="search_html()"><?php echo $ld['search'];?></button>
					</div>
				</li>
			</ul>
		</div>
	<?php echo $form->end();?>
	<div class="am-g action-span" style="text-align:right;margin-bottom:10px;">
		<a class="am-btn am-btn-xs am-btn-default" href="<?php echo $html->url('/configs/config_upload'); ?>"><?php echo $ld['bulk_upload']?></a>
				
		<?php echo $html->link( isset($backend_locale)&&$backend_locale=='eng'?$ld['add'].' '.$ld['shop_configs']:$ld['add'].$ld['shop_configs'],"/configs/view/0",array("class"=>"addbutton am-btn am-btn-warning am-btn-xs am-radius"));?>				
	</div>
	<?php echo $form->create('',array('action'=>'','name'=>'UserForm',"onsubmit"=>"return false","type"=>"get"));?>
		<div class="am-panel-group am-panel-tree">
			<div class="am-panel am-panel-default am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title">
						<div class="am-hide"><?php echo $ld['number']?></div>
						<div class="am-u-lg-2 am-u-md-3 am-hide-sm-only">
							<label class="am-checkbox am-success" style="display: inline;">
						            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
									value="checkbox" data-am-ucheck>
							<?php echo $ld['group']?><br><?php echo $ld['subparameter']?>
							</label>
						</div>
						<div class="am-u-lg-3 am-show-lg-only"><?php echo $ld['name']?><br><?php echo $ld['code']?></div>
						<div class="am-u-lg-2 am-u-md-4 am-u-sm-5">描述</div>
						<div class="am-hide"><?php echo $ld['default_value']?></div>
						<div class="am-hide"><?php echo $ld['versions']?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['type']?></div>
						<div class="am-hide"><?php echo $ld['readonly']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-3"><?php echo $ld['status']?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['sort']?></div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-4"><?php echo $ld['operate']?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php if(isset($configs_list) && sizeof($configs_list)>0){?><?php foreach($configs_list as $k=>$v){ ?>
				<div <?php if((abs($k)+2)%2!=1){?>class="tr_bgcolor"<?php }else{?>class=""<?php }?> >
					<div class="am-panel am-panel-default am-panel-body">
						<div class="am-panel-bd">		
							<div class="am-hide">&nbsp;<?php echo $v['Config']['id'] ?></div>
							<div class="am-u-lg-2 am-u-md-3 am-hide-sm-only">
									<label class="am-checkbox am-success">
									<input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Config']['id']?>" />
										<?php echo $v['Config']['group_code'] ?><br><?php echo $v['Config']['subgroup_code'] ?>
									</label>
							</div>
							<div class="am-u-lg-3 am-show-lg-only"><?php echo $v['ConfigI18n']['name'] ?><br><?php echo $v['Config']['code'] ?></div>
							<div class="am-u-lg-2 am-u-md-4 am-u-sm-5 am-text-break">
							 <span onclick="javascript:listTable.edit(this, '/configs/update_config_description', <?php echo $v['Config']['id']?>,'250')"><?php echo isset($v['ConfigI18n']['description'])&&$v['ConfigI18n']['description'] != ""?$v['ConfigI18n']['description']:"-"; ?></span>
								&nbsp;
							</div>
							<div class="am-hide">&nbsp;<?php echo $v['ConfigI18n']['default_value'];?></div>
							<div class="am-hide">&nbsp;<?php echo $v['Config']['section'] ?></div>
							<div class="am-u-lg-1 am-show-lg-only">&nbsp;<?php echo $v['Config']['type'] ?></div>
							<div class="am-hide">
								<?php if ($v['Config']['readonly'] == 1){?>
								<!--<?php echo $html->image('/admin/skins/default/img/yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "configs/toggle_on_readonly", '.$v["Config"]["id"].')')) ?>&nbsp;-->
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'configs/toggle_on_readonly',<?php echo $v['Config']['id'];?>)"></span>
								<?php }elseif($v['Config']['readonly'] == 0){?>
								<!--<?php echo $html->image('/admin/skins/default/img/no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "configs/toggle_on_readonly", '.$v["Config"]["id"].')'))?>&nbsp;-->
								<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'configs/toggle_on_readonly',<?php echo $v['Config']['id'];?>)">&nbsp;</span>
								<?php }?>	
							</div>
							<div class="am-u-lg-1 am-u-md-2 am-u-sm-3">
								<?php if( $v['Config']['status'] == 1){ ?>
								&nbsp;
								<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'configs/toggle_on_status',<?php echo $v['Config']['id'];?>)"></span>
								
								<?php }elseif($v['Config']['status'] == 0){ ?> 
										<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'configs/toggle_on_status',<?php echo $v['Config']['id'];?>)">&nbsp;</span>
								<?php }?>
							</div>
							<div class="am-u-lg-1 am-show-lg-only">
								<span onclick="javascript:listTable.edit(this, 'configs/update_config_orderby/', <?php echo $v['Config']['id']?>)"><?php echo $v['Config']['orderby'] ?></span>
							</div>
							<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
								<?php echo $html->link($ld['edit'],"/configs/view/{$v['Config']['id']}",array("class"=>"am-btn am-btn-default am-btn-xs"));?>&nbsp;
								<?php echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-default am-text-danger am-btn-xs","onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}configs/remove/{$v['Config']['id']}';}"));?>&nbsp;
							</div>
							<div style="clear:both;"></div>
						</div>
					</div>
				</div>	
			<?php }} ?>
			<div class="btnouterlist" style="position:relative">
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-hide-sm-down" style="left:6px;">
						<div class="am-fl">
					          <label class="am-checkbox am-success" style="display: inline;">
					            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
								value="checkbox" data-am-ucheck><span><?php echo $ld['select_all']?></span>
					          </label>
			            	</div>
						<div class="am-fl" style="margin-left:3px;">
					            <select name="barch_opration_select" id="barch_opration_select" data-am-selected  onchange="barchconfigs_opration_select_onchange(this)">
					              <option value="0"><?php echo $ld['batch_operate']?></option>
					              <option value="delete"><?php echo $ld['batch_delete']?></option>
					    		  <option value="export_csv"><?php echo $ld['batch_export']?></option>
					            </select>
			            	</div> 
						<div class="am-fl" style="display:none;margin-left:3px;">
			                    <select id="export_csv" data-am-selected name="barch_opration_select_onchange" >
			                        <option value=""><?php echo $ld['click_select']?></option>
			                        <option value="all_export_csv"><?php echo  $ld['all_export']?></option>
			                        <option value="choice_export"><?php echo $ld['choice_export']?></option>
			                       
			                    </select>&nbsp;
			              	</div>
						<div class="am-fl" style="margin-left:3px;">
			               	   <button type="button" class="am-btn am-radius am-btn-danger am-btn-sm" onclick="select_batch_operations()"><?php echo $ld['submit']?></button>
			              	</div>
				</div>
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">				
					<?php echo $this->element('pagers', array('cache'=>'+0 hour'));?>
				</div>
			</div>
		</div>
	<?php echo $form->end();?>
</div>




<style>
.tablelist td{text-overflow: ellipsis;}
</style>
<?php
function utf8Substr($str, $from, $len){
	return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF] ){0,'.$from.'}'.
	'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF] ){0,'.$len.'}).*#s',
	'$1',$str);
}
?>
<script type="text/javascript">
function select_batch_operations(){
	var barch_opration_select = document.getElementById("barch_opration_select");
      var export_csv = document.getElementById("export_csv");
      if(barch_opration_select.value==0){
      	  	alert(j_select_operation_type);
			return;
      }
      if(barch_opration_select.value=='delete'){
		batch_operations();
	}
	if(barch_opration_select.value=='export_csv'){
		if(export_csv.value=='all_export_csv'){
			window.location.href=admin_webroot+"/configs/all_export_csv";
		
		}
		if(export_csv.value=='choice_export'){
			choice_upload();
		}
	}
}

//批量删除
function batch_operations(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if( postData=="" ){
		alert("<?php echo $ld['please_select'] ?>");
		return;
	}
	if(confirm("<?php echo $ld['confirm_delete']?>")){
		$.ajax({ 
			url:admin_webroot+"configs/batch_operations/",
			type:"GET",
			dataType:"json",
			data: postData,
			success:function(data){
				window.location.href = window.location.href;
			}
		});
	}
}	

//选择导出
function choice_upload(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if( postData=="" ){
		alert("<?php echo $ld['please_select'] ?>");
		return;
	}else{
	window.location.href=admin_webroot+"configs/choice_export/"+postData;
	
	}
}	

//触发子下拉
function barchconfigs_opration_select_onchange(obj){
	if(obj.value!="export_csv"){
		$("#export_csv").parent().hide();		
	}
	$("select[name='barch_opration_select_onchange[]']").parent().hide();
	
	var export_csv=document.getElementById("export_csv").value;
	
	if(obj.value=="export_csv"){
		if(export_csv=="all_export_csv"){
			$("#export_csv").parent().show();
		}else{
			$("#export_csv").parent().show();
		}
	}

}		
	
	
	
var label_obj="";
function update_orderby(obj,config_id){
	if(!isNaN(obj.innerHTML-0)){
		var orderby_num = obj.innerHTML;
		label_obj = obj;
		obj.innerHTML = "<input type='text' size='2' onBlur='update_orderby_out(this,"+config_id+")' value="+orderby_num+" >";
	}

}
function update_orderby_out(input_obj,config_id){
	label_obj.innerHTML = input_obj.value;
	var sUrl = webroot_dir+"configs/update_orderby/"+input_obj.value+"/"+config_id;
	var request = YAHOO.util.Connect.asyncRequest('POST', sUrl, "");
}


function update_color(obj){
	obj.style.background="#21964D";
}
function update_color_out(obj){
	obj.style.background="#FFFFFF";

}
function search_html(){
	var log_type = document.getElementById('log_type').value; 
	var show_name = document.getElementById('show_name').value;
	var version = document.getElementById('version').value; 
	var sub_group = document.getElementById('sub_group').value;
	var config_keywords = document.getElementById('config_keywords').value;
	var url="show_name="+show_name+"&log_type="+log_type+"&version="+version+"&sub_group="+sub_group+"&config_keywords="+config_keywords;
	window.location.href = encodeURI(admin_webroot+"configs?"+url);
} 
function batch_action() 
{ 
document.UserForm.action=webroot_dir+"configs/batch"; 
document.UserForm.onsubmit=""; 
document.UserForm.submit(); 
} 
function change_state(obj,func,id){
	var ClassName=$(obj).attr('class');
	var val = (ClassName.match(/yes/i)) ? 0 : 1;
	var postData = "val="+val+"&id="+id;
	$.ajax({
		url:admin_webroot+func,
		Type:"POST",
		data: postData,
		dataType:"json",
		success:function(data){
			if(data.flag == 1){
				if(val==0){
					$(obj).removeClass("am-icon-check am-yes");
					$(obj).addClass("am-icon-close am-no");
				}
				if(val==1){
					$(obj).removeClass("am-icon-close am-no");
					$(obj).addClass("am-icon-check am-yes");
				}
			}
		}
	});
}
</script>