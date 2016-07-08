<?php 
/*****************************************************************************
 * SV-Cart 字典管理
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
<!--Search-->
<div class="search_box">
	<?php if(isset($language) && sizeof($language)>0){ ?>
	<div class="am-form-group">
		<div class="am-u-lg-1 am-u-md-2 am-u-sm-4">
			<?php echo $form->create('dictionaries',array('action'=>'/','name'=>'language_form','id'=>'language_form','type'=>'GET','class'=>'am-form am-form-horizontal'));?>
			<select id="locale" name="locale" >
				<option value="-1"><?php echo $ld['choose_language'];?></option>
				<?php foreach($language as $key=>$value){ ?>
				<?php if($value['Language']['name'] != ""){?>
				<?php if(isset($is_select_locale) && $is_select_locale == $value['Language']['locale']){?>
				<option value="<?php echo $value['Language']['locale'];?>" selected>
				<?php }else{?>
				<option value="<?php echo $value['Language']['locale'];?>">
				<?php }?>
				<?php echo $value['Language']['name'];?></option>
				<?php }?>
				<?php }?>
			</select>
			<?php echo $form->end();?>
		</div>
				<p class="am-text-right am-btn-group-xs" style="margin-right:27px;">
				<a class='am-btn am-btn-default am-radius am-btn-sm'  href="<?php echo $html->url('/dictionaries/upload');?>"><?php echo $ld['bulk_upload']; ?></a>	
				</p>
			
		     			
				
		<?php if(isset($is_select_locale)){ ?>
		<?php echo $form->create('dictionaries',array('action'=>'/','name'=>'type_form','id'=>'type_form','type'=>'GET','class'=>'am-form am-form-horizontal','style'=>'margin-left:110px;'));?>
		<div class="am-u-lg-1 am-u-md-2 am-u-sm-4">
			<input type="hidden" name="locale" value="<?php echo $is_select_locale;?>">
			<select id="language_type" name="language_type" >
				<option value="all_type" <?php if(isset($_SESSION['language_type']) && $_SESSION['language_type'] == "all_type"){ echo "selected";}?>><?php echo $ld['all_type'];?></option>
				<option value="label" <?php if(isset($_SESSION['language_type']) && $_SESSION['language_type'] == "label"){ echo "selected";}?>>label</option>
				<option value="js" <?php if(isset($_SESSION['language_type']) && $_SESSION['language_type'] == "js"){ echo "selected";}?>>js</option>
			</select>
		</div>
		<div class="am-u-lg-1 am-u-md-2 am-u-sm-4" >
			<select id="language_location" name="language_location">
				<option value="all_location" <?php if(isset($_SESSION['is_select_location']) && $_SESSION['is_select_location'] == "all_location"){ echo "selected";}?>><?php echo $ld['all_position'];?></option>
				<option value="front" <?php if(isset($_SESSION['is_select_location']) && $_SESSION['is_select_location'] == "front"){ echo "selected";}?>><?php echo $ld['frontend'];?></option>
				<option value="backend" <?php if(isset($_SESSION['is_select_location']) && $_SESSION['is_select_location'] == "backend"){ echo "selected";}?>><?php echo $ld['backend'];?></option>
			</select>
		</div>
		
		<div class="am-u-lg-9 am-u-md-6 am-u-sm-12 am-form-group">
			<label class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-text-right" style="margin-top: 5px;"><?php echo $ld['name'].'/'.$ld['content'];?>:</label>
			<div class="am-u-lg-2 am-u-md-4 am-u-sm-5">
				<input class="am-input-sm "  type="text" name="keywords"  id="keywords" value="<?php if(isset($_SESSION['is_keywords'])){echo $_SESSION['is_keywords'];}?>" />
			</div>
			<div class="am-u-lg-2 am-u-md-1 am-u-sm-2">
				<button  style="margin:5px 0 0 5px;" type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="javascript:check_type_form('submit');"><?php echo $ld['s_select'];?></button>
			</div>
	   </div>
		<?php echo $form->end();?>
		<?php } ?>
	</div>
		
<?php echo $form->create('language_dictionaries',array('action'=>'/export','name'=>'Export','type'=>'POST'));?>
<input type="hidden" name="export_locale" id="export_locale" value="<?php echo isset($is_select_locale)?$is_select_locale:'';?>" />
<input type="hidden" name="export_type"  id="export_type" value="" />
<input type="hidden" name="export_location"  id="export_location" value="" />
<input type="hidden" name="export_keyword"  id="export_keyword" value="" />
<input type="hidden" name="export"  id="export" value="export" />
<?php echo $form->end();?>
		
	<?php } ?>
</div>

<!-- end -->
<!--Search End-->
<!--Main Start-->
<div class="home_main" style="padding:20px 0 20px 0;clear:both;">
  <div id="tablelist">
	<div class="add_list am-g" style="margin-bottom:30px;">
	<?php echo $form->create('dictionaries',array('action'=>'add','name'=>'add_form','class'=>'am-form'));?>
	  <div class="am-u-lg-1 am-u-md-12 am-u-sm-12">
	    <?php echo$ld['z_added'];?>
	  </div>
	  <div class="am-u-lg-2 am-u-md-12 am-u-sm-12">
		<div class="am-u-lg-12 am-u-md-4 am-u-sm-4"><?php echo$ld['z_name'];?></div>
		<div class="am-u-lg-12 am-u-md-8 am-u-sm-8" style="padding-bottom:5px;">
		  <input type="text" name="name" id="add_name" value="" class="am-input-sm">
		</div>
	  </div>
	  <div class="am-u-lg-1 am-u-md-12 am-u-sm-12">
		<div class="am-u-lg-12 am-u-md-4 am-u-sm-4"><?php echo$ld['z_position'];?></div>
		<div class="am-u-lg-12 am-u-md-8 am-u-sm-8" style="padding-bottom:5px;">
		  <select id="language_location" name="location">
			<option value="front"><?php echo $ld['frontend'];?></option>
			<option value="backend"><?php echo $ld['backend'];?></option>
		  </select>
		</div>
	  </div>
	  <div class="am-u-lg-1 am-u-md-12 am-u-sm-12">
		<div class="am-u-lg-12 am-u-md-4 am-u-sm-4"><?php echo$ld['z_type'];?></div>
		<div class="am-u-lg-12 am-u-md-8 am-u-sm-8" style="padding-bottom:5px;">
		  <select id="language_type" name="type">
			<option value="label">label</option>
			<option value="js">js</option>
		  </select>
		</div>
	  </div>
	  <div class="am-u-lg-2 am-u-md-12 am-u-sm-12">
		<div class="am-u-lg-12 am-u-md-4 am-u-sm-4"><?php echo$ld['z_language'];?></div>
		<?php $n = 0;if(isset($language) && sizeof($language)>0){foreach($language as $key=>$value){ $n++; ?>
		<div class="am-u-lg-12 am-u-md-4 am-u-sm-4" style="margin-bottom:5px">
		  <?php echo $value['Language']['name'];?>
		  <input type="hidden" id="foreach_local_name<?php echo $n?>" value="<?php echo $value['Language']['name']?>" />
		  <input type="hidden" id="foreach_local<?php echo $n?>" value="<?php echo $value['Language']['locale']?>" />
		  <input type="hidden" id="foreach_local_google<?php echo $n?>" value="<?php echo $value['Language']['google_translate_code']?>" />
		  <?php if($value['Language']['locale'] == $locale){
			$google_translate_code = $value['Language']['google_translate_code'];}
		  ?>
		  <input type="hidden" id="google_translate_code" value="<?php echo isset($google_translate_code)?$google_translate_code:'';?>" />
		</div>
		<?php }}?>
	  </div>
	  <div class="am-u-lg-2 am-u-md-12 am-u-sm-12">
		<div class="am-u-lg-12 am-u-md-4 am-u-sm-4"><?php echo$ld['z_content'];?></div>
		<?php $n = 0;if(isset($language) && sizeof($language)>0){foreach($language as $key=>$value){ $n++; ?>
		<div class="am-u-lg-12 am-u-md-4 am-u-sm-4" style="margin-bottom:5px">
		  <input type="text" class="am-input-sm" name="data[Dictionary][<?php echo $value['Language']['locale'];?>][value]" id="locale_value" value="">
		</div>
		<?php }}?>
	  </div>
	  <div class="am-u-lg-2 am-u-md-12 am-u-sm-12">
		<div class="am-u-lg-12 am-u-md-4 am-u-sm-4"><?php echo $ld['z_description'];?></div>
		<?php $n = 0;if(isset($language) && sizeof($language)>0){foreach($language as $key=>$value){ $n++; ?>
		<div class="am-u-lg-12 am-u-md-4 am-u-sm-4" style="margin-bottom:5px">
		  <input type="text" class="am-input-sm" name="data[Dictionary][<?php echo $value['Language']['locale'];?>][description]" id="locale_description"  value="">
		</div>
		<?php }}?>
	  </div>
	  <div class="am-u-lg-1 am-u-md-12 am-u-sm-12">
		<div class="am-u-lg-12 am-u-md-4 am-u-sm-4"><?php echo $ld['z_operation'];?></div>
		<div class="am-u-lg-12 am-u-md-8 am-u-sm-8">
		<?php if(sizeof($language) == $n){?>
		 <div class="am-btn-group-xs"> 
	        <button type="button" class="am-btn am-btn-warning am-radius am-btn-xs addbutton"><span class="am-icon-plus"></span>&nbsp;<?php echo $ld['add'] ?></button>
		 </div>
		<?php }?>
		</div>
	  </div>
	<?php echo $form->end();?>
	</div>
  </div>
  <div style="clear:both;"></div>
		<div class="am-panel-group am-panel-tree">
			<div class="am-panel am-panel-default am-panel-header"  style="margin-top:20px;">
				<div class="am-panel-hd">
					<div class="am-panel-title am-g">
						<div class="am-u-lg-1 am-show-lg-only">
							<label class="am-checkbox am-success" style="display: inline;">
						            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
									value="checkbox" data-am-ucheck>
								<?php echo $ld['z_id'];?>
							</label>
						</div>
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['z_name'];?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['z_position'];?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['z_type'];?></div>
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['z_content'];?></div>
						<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['z_description'];?></div>
						<div class="am-u-lg-1 am-u-md-3 am-u-sm-3"><?php echo $ld['z_operation'];?></div>
					</div>
				</div>
			</div>
			<?php if(isset($language_dictionaries) && count($language_dictionaries)>0){?>
			<?php foreach($language_dictionaries as $k=>$v){?>
				<div class="am-panel-hd">
			<div  <?php if((abs($k)+2)%2!=1){ echo 'class="am-g tr_bgcolor"'; }else{ echo 'class="am-g"'; }?> >
				<div class="am-u-lg-1 am-show-lg-only" >
						<label class="am-checkbox am-success">
							<input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Dictionary']['id']?>" />
						</label>
				</div>
				<div class="am-panel-title am-u-lg-3 am-u-md-3 am-u-sm-3" >
					<div>
						<div id="lang_name <?php echo $v['Dictionary']['id']?>">
								<span onclick="javascript:listTable.edit(this, 'dictionaries/update_dictionaries_name/', <?php echo 	$v['Dictionary']['id']?>)"><?php echo $v['Dictionary']['name']?></span>
						</div>
					</div>
				</div>
				<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['Dictionary']['location']?></div>
				<div class="am-u-lg-1 am-u-md-2 am-u-sm-2" id="type<?php echo $v['Dictionary']['id']?>">
					<div id="lang_type<?php echo $v['Dictionary']['id']?>">
					<?php if(isset($language_type_assoc[$v['Dictionary']['type']])) echo $language_type_assoc[$v['Dictionary']['type']]; else echo $v['Dictionary']['type'];?>
					</div>
				</div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
						<div id="lang_value<?php echo $v['Dictionary']['id']?>">
							<span onclick="javascript:listTable.edit(this, 'dictionaries/update_dictionaries_value/', <?php echo $v['Dictionary']['id']?>)"><?php echo $v['Dictionary']['value']?></span>
						</div>
				</div>
				<div class="am-u-lg-2 am-show-lg-only">
						<div id="lang_description<?php echo $v['Dictionary']['id']?>">
							<div id="description<?php echo $v['Dictionary']['id']?>" onclick="javascript:go_input(<?php echo $v['Dictionary']['id']?>,'<?php echo $v['Dictionary']['description']?>','description',28);">
								<?php echo $v['Dictionary']['description']?>
							</div>
					    </div>&nbsp;
				</div>
				<div class="am-u-lg-1 am-u-md-3 am-u-sm-3" style="margin:2px 0px;">
						<button type="button" class="am-btn am-btn-default am-text-danger am-radius am-btn-sm" onclick="if(confirm('<?php echo $ld['confirm_delete']; ?>')){window.location.href='<?php echo $admin_webroot; ?>/dictionaries/remove/<?php echo $v['Dictionary']['id']; ?>';}"><?php echo $ld['remove'];?></button>
				</div>
			</div>
			</div>		
		<?php }?>
            <div class="btnouterlist" style="position:relative">
            	<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-hide-sm-down" style="left:6px;">
						<div class="am-fl">
					          <label class="am-checkbox am-success" style="display: inline;">
					            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
								value="checkbox" data-am-ucheck><span><?php echo $ld['select_all']?></span>
					          </label>
			            	</div>
						<div class="am-fl" style="margin-left:3px;">
					            <select name="barch_opration_select" id="barch_opration_select" data-am-selected  onchange="barchdics_opration_select_onchange(this)">
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
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">	<?php echo $this->element('pagers');?> </div>
			</div>
		<?php }else{?>
			<div class="am-g">
				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="margin-left:40%;margin-top:2%;">
					<span style='font-size:16px;font-weight:bold;'>
					<?php if(isset($is_select_locale)){?>
					<?php echo$ld['z_prompt1'];?>
					<?php }else{?>
					<?php echo$ld['z_prompt4'];?>
					<?php }?>
					</span>
				</div>
			</div>
		<?php }?>
		</div>
</div>

<!--Main Start End-->
<style>
@media only screen  and (min-width : 1025px) 
{
	/*.lg_width input[type='text']{max-width:200px;}*/
	.am-topbar-nav{width:100%;}
}
@media only screen and (min-width:641px) and (max-width : 1024px) 
{
	.am-nav-pills > li {
	    float: none;
		min-height:40px;
	    padding: 10px 0;
	}
	.am-topbar-nav{float:none;}
}
@media only screen and (max-width:640px)
{
	.am-nav-pills > li {
	    float: none;
		min-height:40px;
	    padding: 10px 0;
	}
}
.am-form select{padding:0.3em;}
</style>
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
			window.location.href=admin_webroot+"/dictionaries/all_export_csv";
		
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
			url:admin_webroot+"dictionaries/batch_operations/",
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
	window.location.href=admin_webroot+"dictionaries/choice_export/"+postData;
	
	}
}	

//触发子下拉
function barchdics_opration_select_onchange(obj){
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
	
	
	
	
$("#locale").change(function(){
	document.language_form.submit();
});

$("#language_type").change(function(){
	document.type_form.submit();
});

$("#language_location").change(function(){
	document.type_form.submit();
});

function export_act(){ 
	document.getElementById('export_type').value = document.getElementById('language_type').value;
	document.getElementById('export_location').value = document.getElementById('language_location').value;
	document.getElementById('export_keyword').value = document.getElementById('keywords').value;
	document.forms['Export'].submit(); 
}		
function import_act(){
	if(document.getElementById('import_span').style.display == "none"){
	    document.getElementById('import_span').style.display = "";
	}else{
	    document.getElementById('import_span').style.display = "none";
	}
}
function check_type_form(act){
	var keywords=document.getElementById('keywords').value;
	if(act =="submit"){
		if(keywords == ""){
			alert("<?php echo $ld["z_prompt3"];?>");
		}else{
			document.type_form.submit();
		}
	}
	else{
		document.type_form.submit();
	}
}
$(".addbutton").click(function(){
	var name=$("#add_name").val();
	var val1=$("#locale_value").val();
	var val2=$("#engvalue").val();
	if(name!="" && val1!="" && val2!=""){
		$.ajax({
	        cache: true,
	        type: "POST",
	        url:admin_webroot+"dictionaries/add",
	        data:$('#dictionariesAddForm').serialize(),// 你的formid
	        async: false,
	        success: function(data) {
	          var result= JSON.parse(data);
				if(result.code==1){
					alert(result.msg);
				}else if(result.code==0){
					alert(result.msg);
					window.location.href=encodeURI(admin_webroot+result.url);
				}
	        }
	    });
	}else{
		alert("名称和内容不能为空！");
	}
});
</script>