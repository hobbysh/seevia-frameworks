<?php 
/*****************************************************************************
 * SV-Cart 菜单管理
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
.am-yes{color:#5eb95e;}
.am-no{color:#dd514c;}
</style>
<div class="am-g am-container am-other_action">
	<div class="am-fr am-u-lg-6 am-u-md-6 am-u-sm-3 am-padding-right-0" style="text-align:right;margin-bottom:10px;">
	<?php if(  isset($profile_id) && !empty($profile_id)   ) {  ?>
				 <a class="am-btn am-btn-xs am-btn-default" href="<?php echo $html->url('/menus/menu_upload'); ?>"><?php echo $ld['bulk_upload']?></a>
	<?php } ?>
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/menus/view/0'); ?>"><span class="am-icon-plus"></span> <?php echo $ld['add'] ?></a>
	</div>
</div>
<div class="">
	<div class="am-panel-group am-panel-tree" id="accordion">
		
		<div class="am-panel am-panel-default am-panel-header">
		    <div class="am-panel-hd">
		      <div class="am-panel-title">
				 <div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
						<label class="am-checkbox am-success" style="display: inline;">
						            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
									value="checkbox" data-am-ucheck>
							<?php echo $ld['menu_name'] ?>
						</label>
				</div>
				 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['code'] ?></div>
	   			 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-show-lg-only"><?php echo $ld['link_address'] ?></div>
				 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-show-lg-only"><?php echo $ld['versions'] ?></div>
				 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['orderby'] ?></div>
				 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['status'] ?></div>
	             <div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['operate'] ?></div>
				 <div style="clear:both;"></div>
		      </div>
		    </div>
		</div>
		<?php if(isset($menus_tree) && sizeof($menus_tree)>0){foreach($menus_tree as $k => $v){ ?>
		<div>
		<div class="am-panel am-panel-default am-panel-body">
		    <div class="am-panel-bd">
				 <div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
					<label class="am-checkbox am-success">
							<input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['OperatorMenu']['id']?>" />
					<span data-am-collapse="{parent: '#accordion', target: '#menu_<?php echo $v['OperatorMenu']['id']?>'}" class="<?php echo (isset($v['SubMenu']) && !empty($v['SubMenu']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;
					<?php echo $v['OperatorMenuI18n']['name'];?>
					</label>	
				</div>
				 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['OperatorMenu']['operator_action_code']?>&nbsp;</div>
	   			 <div class="am-u-lg-2 am-show-lg-only"><?php echo $v['OperatorMenu']['link']?>&nbsp;</div>
				 <div class="am-u-lg-2 am-show-lg-only"><?php echo $v['OperatorMenu']['section']?>&nbsp;</div>
				 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['OperatorMenu']['orderby']?></div>
				 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">&nbsp;<span class="<?php echo (!empty($v['OperatorMenu']['status'])&&$v['OperatorMenu']['status'])?'am-icon-check am-yes':'am-icon-close am-no'; ?>"></span></div>
	             <div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><a class="am-btn am-btn-default am-btn-sm am-radius" href="<?php echo $html->url('/menus/view/'.$v['OperatorMenu']['id']); ?>"><?php echo $ld['edit']; ?></a>&nbsp;<a class="am-btn am-btn-default am-text-danger am-btn-sm am-radius" href="javascript:void(0);" onclick="list_delete_submit('<?php echo $admin_webroot; ?>menus/remove/<?php echo $v['OperatorMenu']['id']; ?>')"><?php echo $ld['delete']; ?></a></div>
				 <div style="clear:both;"></div>
		    </div>
		    <?php if(isset($v['SubMenu']) && !empty($v['SubMenu'])){?>
		    <div class="am-panel-collapse am-collapse am-panel-child" id="menu_<?php echo $v['OperatorMenu']['id']?>">
		    	<?php foreach($v['SubMenu'] as $kk=>$vv){  ?>
				<div class="am-panel-bd am-panel-childbd">
					 <div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $vv['OperatorMenuI18n']['name'];?></div>
					 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $vv['OperatorMenu']['operator_action_code']?></div>
		   			 <div class="am-u-lg-2 am-show-lg-only"><?php echo $vv['OperatorMenu']['link']?></div>
					 <div class="am-u-lg-2 am-show-lg-only"><?php echo $vv['OperatorMenu']['section']?></div>
					 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $vv['OperatorMenu']['orderby']?></div>
					 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">&nbsp;&nbsp;<span class="<?php echo (!empty($vv['OperatorMenu']['status'])&&$vv['OperatorMenu']['status'])?'am-icon-check am-yes':'am-icon-close am-no'; ?>"></span></div>
		             <div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><a class="am-btn am-btn-default am-btn-sm am-radius" href="<?php echo $html->url('/menus/view/'.$vv['OperatorMenu']['id']); ?>"><?php echo $ld['edit']; ?></a>&nbsp;<?php echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-default am-text-danger am-btn-sm am-radius","onclick"=>"list_delete_submit('{$admin_webroot}menus/remove/{$vv['OperatorMenu']['id']}');"));?></div>
					 <div style="clear:both;"></div>
		    	</div>
		    	<?php } ?>
		    </div>
		    <?php } ?>
		</div>
		</div>	
		<?php }} ?>
				<div id="btnouterlist" class="btnouterlist">
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-hide-sm-down" style="left:6px;">
						<div class="am-fl">
					          <label class="am-checkbox am-success" style="display: inline;">
					            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
								value="checkbox" data-am-ucheck><span><?php echo $ld['select_all']?></span>
					          </label>
			            	</div>
						<div class="am-fl" style="margin-left:3px;">
					            <select name="barch_opration_select" id="barch_opration_select" data-am-selected  onchange="barchmenus_opration_select_onchange(this)">
					              <option value="0"><?php echo $ld['batch_operate']?></option>
					              <option value="delete"><?php echo $ld['batch_delete']?></option>
							<?php if( isset($profile_id) && !empty($profile_id) ){ ?>
					    		  <option value="export_csv"><?php echo $ld['batch_export']?></option>
					    		  <?php } ?>
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
	</div>
	</div>
</div>
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
			window.location.href=admin_webroot+"/menus/all_export_csv";
		
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
			url:admin_webroot+"menus/batch_operations/",
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
	window.location.href=admin_webroot+"menus/choice_export/"+postData;
	
	}
}	

//触发子下拉
function barchmenus_opration_select_onchange(obj){
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
								
								
$(function(){
	var $collapse =  $('.am-panel-child');
	$collapse.on('opened.collapse.amui', function() {
		var parentbody=$(this).parent();
		var collapseoobj=parentbody.find(".am-icon-plus");
		collapseoobj.removeClass("am-icon-plus");
		collapseoobj.addClass("am-icon-minus")
	});
		
	$collapse.on('closed.collapse.amui', function() {
		var parentbody=$(this).parent();
		var collapseoobj=parentbody.find(".am-icon-minus");
		collapseoobj.removeClass("am-icon-minus");
		collapseoobj.addClass("am-icon-plus")
	});
})
</script>