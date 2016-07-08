<style type="text/css">
body{
font-weight:normal;
font-size:12px;
}
td,.word{
	
word-wrap:break-word;
word-break:break-all;
}	
.am-container {
  margin-left: auto;
  margin-right: auto;
  width: 100%;
  max-width: 1000px;
}
.am-g {
  margin: 0 auto;
  width: 100%;
}
.red{color:red;}
</style>
	
<style type="text/css">
.am-display{
display:none;
}	
</style>
<div class=" am-g am-container">
	       	<?php $project_code=isset($_GET['project_code'])?$_GET['project_code']:''; //pr($projectcode);?>
		<?php $category_code=isset($_GET['category_code'])&&$_GET['category_code']!=''?$_GET['category_code']:$categ_code['ApiCategorie']['code']; ?>
<!--//menu start-->
<div class="am-g">
<div class="am-u-sm-12 am-u-md-12 am-u-lg-12" style=" height:100px;">
	<div style="padding-top:8px;"><?php echo $project_info['ApiProject']['name']; ?></div>
     <div class="menu" style="padding-top:20px; ">
   	  <a href="<?php echo $html->url('/apidocs/'); ?>">
   		<?php echo $ld['api_documentation_center']; ?>
   	 </a> >
         <a href="<?php echo $html->url('/apidocs/api?project_code='.$project_code.'&category_code='); ?>">
		<?php echo $project_info['ApiProject']['name']; ?>	
	</a> > 
	<?php foreach($category_list as $k=>$v){ if($k==$category_code){echo $v;} }?>  

                </div>	
</div>
</div>
<!--// menu end
//所有类目 start-->


<div class="am-g">
<div class="am-show-sm-only">
	<button class="am-btn am-btn-primary" data-am-collapse="{target: '#collapse-nav'}">	 <?php foreach($category_list  as $kk=>$vv){  if($kk==$method_info['ApiMethod']['api_category_code']){  echo $vv;  }}?><i class="am-icon-bars"></i></button>
<nav>
  <ul id="collapse-nav" class="am-nav am-collapse">
    	<?php  foreach($method_infos as $v){ ?>
    <li><a href="<?php echo $html->url('/apidocs/detail?project_code='.$project_code.'&category_code='.$category_code.'&method_id='.$v['ApiMethod']['id'] ); ?>"  title="<?php echo $v['ApiMethod']['code'] ?>"  > <?php echo $v['ApiMethod']['code'] ?>
										<p><?php echo $v['ApiMethod']['name'] ?></p>
								 </a></li>
   <?php }  ?>
    
  </ul>
</nav>
	
</div>

  <div class="am-hide-sm-only  am-u-md-3 am-u-lg-3   ">
       <table class="am-table am-table-striped am-table-hover ">
   	<thead>
        <tr>
        	 <?php foreach($category_list  as $kk=>$vv){  if($kk==$method_info['ApiMethod']['api_category_code']){?>
            <th>		
   		<?php echo $vv;  ?>
		</th>
		<?php }} ?>
	</tr>
       </thead>
    	<tbody>  
                	<?php  foreach($method_infos as $v){ ?>
                	 <tr>
                		<td> <a href="<?php echo $html->url('/apidocs/detail?project_code='.$project_code.'&category_code='.$category_code.'&method_id='.$v['ApiMethod']['id'] ); ?>"  title="<?php echo $v['ApiMethod']['code'] ?>"  > <?php echo $v['ApiMethod']['code'] ?>
										<p><?php echo $v['ApiMethod']['name'] ?></p>
								 </a>
				</td>
			</tr>
				<?php }  ?>

        </tbody>  
         
	</table>
     </div>
       
      
<!--//所有类目 end
//列表 start-->

          <div class="am-u-sm-12  am-u-md-9 am-u-lg-9  ">
          	  <div class="am-g" style="margin-left:10px;">
                	
                	<h2>	<?php echo $method_info['ApiMethod']['code'] ?><span style="color:gray;">(<?php echo $method_info['ApiMethod']['name'] ?>)</span></h2>
                			
				<p>	<?php echo $method_info['ApiMethod']['description'] ?>	</p>			
								 
          	  </div>
        <div class="am-panel-group" id="accordion" style="padding-top:5px;">
		  <div class="am-panel am-panel-default">
				    <div class="am-panel-hd">
				      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#do-not-say-1'}">
				        公共参数
				      </h4>
				    </div>
			    <div id="do-not-say-1" class="am-panel-collapse am-collapse ">
			      <div class="am-panel-bd">
			       <dd><b>请求地址：</b></dd>
						<table class="am-table am-table-bd am-table-striped am-table-hover"> 
						  <tr>
						    <th width="20%">环境</th>
						    <th width="40%">HTTP请求地址</th>
						    <th width="40%">HTTPS请求地址</th>
						  </tr>
						
						  <tr>
						    <td>正式环境</td>
						    <td><?php echo $project_info['ApiProject']['http_address']; ?></td>
						    <td><?php echo $project_info['ApiProject']['https_address']; ?></td>
						  </tr>
						  <tr>
						    <td>沙箱环境</td>
						    <td><?php echo $project_info['ApiProject']['sandbox_http_address']; ?></td>
						    <td><?php echo $project_info['ApiProject']['sandbox_https_address']; ?></td>
						  </tr>
						</table>
                    	<dd><b>公共请求参数：</b></dd>
                    	  <table class="am-table am-table-bd am-table-striped am-table-hover"> 
                            <thead>
                                <tr> 
                                   <th width="18%">名称</th> 
                                   <th width="10%">类型</th> 
                                   <th width="15%">是否必须</th> 
                                   <th width="57%">描述</th> 
                                </tr> 
                            </thead>
                              <?php foreach($project_commonparameter_infos as $v){ ?>
                            <tbody> 
                                <tr> 
                                    <td><?php echo $v['ApiProjectCommonParameter']['name']; ?></td> 
                                    <td><?php switch( $v['ApiProjectCommonParameter']['type'] ){case '0': echo 'String';break; case '1': echo 'Boolean';break; case '2': echo '其他';break;} ?></td> 
                                    <td><?php switch( $v['ApiProjectCommonParameter']['required']){case '0': echo '否';break;case '1': echo "是" ; break;}  ?></td> 
                                    <td><?php echo $v['ApiProjectCommonParameter']['description']; ?></td> 
                                </tr> 
                            </tbody> 
                            <?php } ?>
                        </table>
			      </div>
			    </div>
		  </div>
		  					
		  					
		  					
		  				 <div class="am-panel am-panel-default">
				    <div class="am-panel-hd">
				      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#do-not-say-2'}">
				        请求参数
				      </h4>
				    </div>
			    <div id="do-not-say-2" class="am-panel-collapse am-collapse am-in">
			      <div class="am-panel-bd">
			       
                    	  <table class="am-table am-table-bd am-table-striped am-table-hover"> 
                          <thead>
                                <tr> 
                                   <th width="20%">名称</th> 
                                   <th width="10%">类型</th> 
                                   <th width="14%">是否必须</th> 
                                   <th width="20%">示例值</th>
                                   <th width="36%">描述</th>
                                </tr> 
                            </thead>
                         <?php foreach($method_request_infos as $v){  ?>
                            <tbody> 

                            	       <tr class="   " > 
                       <td>
				
						<?php echo  $v['ApiMethodRequest']['name'];   ?>
			</td> 
                       <td>
<?php switch( $v['ApiMethodRequest']['type']){case 'String': echo 'String';break; case 'Number':echo 'Number';break; case 'Boolean':echo 'Boolean';break;} ?> 						
				</td> 
                        <td  class="<?php echo isset($v['ApiMethodRequest']['required']) && ($v['ApiMethodRequest']['required']=='1' )?'red':'';    ?> "  >
<?php switch( $v['ApiMethodRequest']['required']){case '1': echo '是';break; case '0':echo '否';break;} ?> 
                          </td> 
		                <td>
					<?php   echo $v['ApiMethodRequest']['defualt'];   ?>

		               </td> 
                      
                         <td>
                        	<?php echo  $v['ApiMethodRequest']['description'];   ?>

				</td> 
         	</tr> 
         	             </tbody> 
                            <?php } ?>
                        </table>
			      </div>
			    </div>
		  </div>	
		  					
		  					
						

		  		<div class="am-panel am-panel-default">
				    <div class="am-panel-hd">
				      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#do-not-say-3'}">
				        响应参数
				      </h4>
				    </div>
			    <div id="do-not-say-3" class="am-panel-collapse am-collapse am-in">
			      <div class="am-panel-bd">
			      
                    	  <table class="am-table am-table-bd am-table-striped am-table-hover" > 
                            <thead>
                                <tr> 
                                   <th width="25%">名称</th> 
                                   <th width="10%">类型</th> 
                                   <th width="25%">示例值</th>
                                   <th width="40%">描述</th>
                                </tr> 
                            </thead><tbody > 
                        <?php foreach($method_response_infos as $v ){ ?>
                            
                       <tr  > 
                       <td id="accordion-field-<?php echo $v['ApiMethodResponse']['id']; ?>">
                  <span class="my-collapse-<?php echo $v['ApiMethodResponse']['id']; ?>   <?php echo isset($object_field_info[$v['ApiMethodResponse']['type']])&&!empty($object_field_info[$v['ApiMethodResponse']['type']])?'am-icon-plus':'';  ?>   am-collapsed" data-am-collapse="{parent: '#accordion-field-<?php echo $v['ApiMethodResponse']['id']; ?>', target: '#my-collapse-<?php echo $v['ApiMethodResponse']['id']; ?>'}"></span>&nbsp;
			<?php echo $v['ApiMethodResponse']['name']; ?>
						
				 </td> 
                       <td>
	                  <?php switch($v['ApiMethodResponse']['type']){ case 'String':echo 'String';break; case 'Number': echo 'Number';break; case 'Boolean': echo 'Boolean';break; default: echo $v['ApiMethodResponse']['type']; } ?> 
		
					
				</td> 
                       <td>
			<?php echo $v['ApiMethodResponse']['samples']; ?>
                
               	 </td> 
                       <td>
			<?php echo $v['ApiMethodResponse']['description']; ?>
			</td> 
         	</tr> 
 	 
 	        
         		<?php if(isset($object_field_info[$v['ApiMethodResponse']['type']])&&!empty($object_field_info[$v['ApiMethodResponse']['type']])){  ?>
         			<tr  class="my-collapse-<?php echo $v['ApiMethodResponse']['id']; ?>-display   am-display"  >
         			<td colspan="4" >
	         		<div class="am-icon-changes  am-collapse" id="my-collapse-<?php echo $v['ApiMethodResponse']['id']; ?>">	
         			<div class="am-g "> 
		                            	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">&nbsp;
		                        		</div>
		                        		<div class="am-u-lg-11 am-u-md-11 am-u-sm-11 " >
			                        			<div class="am-panel-bd am-cf  "  style="border-top:0px solid #ddd">
					                                <div class="am-g "> 
					                                   <div class="am-u-lg-3 am-u-md-3 am-u-sm-3">名称</div> 
					                                   <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">类型</div> 
					                                   <div class="am-u-lg-3 am-u-md-3 am-u-sm-3">示例值</div>
					                                   <div class="am-u-lg-4 am-u-md-4 am-u-sm-4">描述</div>
					                                </div> 
					              		</div>
		                            <?php foreach($object_field_info[$v['ApiMethodResponse']['type']] as $field_val){  ?>
			                        			<div class="am-panel-bd am-hd-border-e"  style="border-bottom:0 solid #eee;">
					                        		<div class="am-g">
					                        			<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $field_val['ApiObjectField']['name'];   ?></div>
					                        			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $field_val['ApiObjectField']['type'];   ?></div>
					                        			<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $field_val['ApiObjectField']['samples'];   ?></div>
					                        			<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $field_val['ApiObjectField']['description'];   ?></div>
						                    	</div>
						                    </div>
					          <?php   }  ?>
		                        		</div>
                        			
                        </div>
                      </div>
                      </td>
                         </tr>	
                        <?php } ?>
                    		
                        	<?php } ?>				
                       </tbody>
         		
                        </table>
                        	
			      </div>
			    </div>
		  </div>	
		  			  
		  			  
			  
		  			  
		  			  
		  			  
		  			  
		  			  
		  			  
		  			  
		  			  
		  			  
		  			  
		  			  
		  			  
		  			  
		  			  
		  				<div class="am-panel am-panel-default">
				    <div class="am-panel-hd">
				      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#do-not-say-4'}">
				        请求示例
				      </h4>
				    </div>
			    <div id="do-not-say-4" class="am-panel-collapse am-collapse am-in">
			      <div class="am-panel-bd">
			      
                    	 <div class="am-tabs" data-am-tabs>
  <ul class="am-tabs-nav am-nav am-nav-tabs">
  		                          <?php foreach($method_request_example_infos as $k=> $v ){  ?>

    <li class="am-active"><a href="#tabs3<?php echo $k; ?>">
    <?php switch($v['ApiMethodRequestExample']['type']){ case 'JAVA':echo 'JAVA';break; case '.NET': echo '.NET';break; case 'PHP': echo 'PHP';break; case 'Python':echo 'Python';break; case 'CURL': echo 'CURL';break; case 'C/C++': echo 'C/C++';break;case 'NodeJS': echo 'NodeJS';break; case 'JQuery': echo 'JQuery';break; default: echo '&nbsp;';}?>              

    	</a></li>
    	<?php } ?>
  </ul>

  <div class="am-tabs-bd">
  	                              	  <?php foreach($method_request_example_infos as $k=> $v ){  ?>

    <div class="am-tab-panel am-fade am-in am-active" id="tabs3<?php echo $k; ?>">
     <pre><?php echo $v['ApiMethodRequestExample']['description']; ?></pre>
    </div>
 <?php } ?>
  </div>
</div>

			      </div>
			    </div>
		  </div>	
		  				
		  				
		  				
		  			  <div class="am-panel am-panel-default">
				    <div class="am-panel-hd">
				      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#do-not-say-5'}">
				        响应示例
				      </h4>
				    </div>
			    <div id="do-not-say-5" class="am-panel-collapse am-collapse am-in">
			      <div class="am-panel-bd">
			       		<div class="am-tabs" data-am-tabs>
  <ul class="am-tabs-nav am-nav am-nav-tabs" >
     <?php foreach($method_response_example_infos as $k=>$v ){  ?>

    <li class="am-active"><a href="#tabs4<?php echo $k;?>">
    	<?php switch( $v['ApiMethodResponseExample']['type']){case 'XML': echo 'XML示例';break; case 'JSON':echo 'JSON示例';break; }?>

	    	</a></li>
	   <?php } ?>
  </ul>

  <div class="am-tabs-bd">
     <?php foreach($method_response_example_infos as $k=>$v ){  ?>

    <div class="am-tab-panel am-fade am-in am-active" id="tabs4<?php echo $k; ?>">
     <pre>   <?php pr( json_decode( $v['ApiMethodResponseExample']['description'],TRUE )); ?></pre>
    </div>
   <?php }  ?>
  </div>
</div>

			      </div>
			    </div>
		  </div>	
		  				
		  				<div class="am-panel am-panel-default">
				    <div class="am-panel-hd">
				      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#do-not-say-6'}">
				        异常示例
				      </h4>
				    </div>
			    <div id="do-not-say-6" class="am-panel-collapse am-collapse am-in">
			      <div class="am-panel-bd">
			     <div class="am-tabs" data-am-tabs>
  <ul class="am-tabs-nav am-nav am-nav-tabs">
    <?php foreach($method_response_exception_infos  as $k=>$v){ ?>

    <li class="am-active"><a href="#tabs5<?php echo $k;?>">
<?php  switch( $v['ApiMethodResponseException']['type'] ){ case 'XML': echo 'XML示例'; break; case 'JSON': echo 'JSON示例';break;} ?> </a>
    	</li>
  	<?php } ?>

  </ul>

  <div class="am-tabs-bd">
  <?php foreach($method_response_exception_infos  as $k=>$v){ ?>

    <div class="am-tab-panel am-fade am-in am-active" id="tabs5<?php echo $k; ?>">
    	<pre> <?php echo    $v['ApiMethodResponseException']['description']; ?></pre>
    </div>
                             <?php } ?>

  
  </div>
</div>
			      </div>
			    </div>
		  </div>
		  				
		  				
		  				
		  				
		  			<div class="am-panel am-panel-default">
				    <div class="am-panel-hd">
				      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#do-not-say-7'}">
				        错误码解释
				      </h4>
				    </div>
			    <div id="do-not-say-7" class="am-panel-collapse am-collapse am-in">
			      <div class="am-panel-bd">
			      <table class="am-table am-table-bd am-table-striped am-table-hover">
			         <thead>
                                <tr> 
                                   <th width="30%">错误码</th> 
                                   <th width="30%">错误描述</th> 
                                   <th width="40%">解决方案</th> 
                                </tr> 
                            </thead>
                    <?php  foreach($error_code_interpretation_infos as $vv){?>

                        <?php foreach($method_error_code_infos as $v){ ?>
                        	<?php   if($v['ApiMethodErrorCode']['error_code']==$vv['ApiErrorCodeInterpretation']['code']){  ?>
                            <tbody> 
                            			<tr> 
		                                    <td><?php echo $vv['ApiErrorCodeInterpretation']['code'] ;  ?></td> 
		                                    <td><?php echo $vv['ApiErrorCodeInterpretation']['description']; ?></td>
		                                    <td><?php echo $vv['ApiErrorCodeInterpretation']['solution'];   ?></td>
		                                </tr> 
		                             		       
		                    </tbody> 
		             	<?php }}} ?>
                        </table>
			      </div>
			    </div>
		  </div>
		  					
		  				
		  				<div class="am-panel am-panel-default">
				    <div class="am-panel-hd">
				      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#do-not-say-8'}">
				        FAQ
				      </h4>
				    </div>
			    <div id="do-not-say-8" class="am-panel-collapse am-collapse am-in">
			      <div class="am-panel-bd">
			      <table class="am-table am-table-bd am-table-striped am-table-hover">
			       <thead>
                                <tr> 
                                   <th width="50%">问题</th> 
                                   <th width="50%">答案</th> 
                                </tr> 
                            </thead>
                        <?php foreach($method_faq_infos as $v){     ?>
                            <tbody> 
							<tr> 
		                                    <td><?php echo $v['ApiMethodFaq']['question'] ;  ?></td> 
		                                    <td><?php echo $v['ApiMethodFaq']['answer']; ?></td>
		                                </tr> 
		                             		       
		                    </tbody> 
		             	<?php }?>
                        </table>
			      </div>
			    </div>
		  </div>
		  				
		  				
		  					
		  					
  </div>
    
     
</div>
<!--列表 end	   -->    
			     
</div>
		
		
		
</div>
		  			  
	 <script type="text/javascript">
$('.am-icon-changes').on('open.collapse.amui', function() {
	var element_id=$(this).attr("id");
$('.'+element_id+'-display').removeClass("am-display");
	$('.'+element_id).removeClass("am-icon-plus");
	$('.'+element_id).addClass(" am-icon-minus ");
	

}).on('close.collapse.amui', function() {
	var element_id=$(this).attr("id");
	$('.'+element_id+'-display').addClass("am-display");
	$('.'+element_id).removeClass("am-icon-minus");
	$('.'+element_id).addClass(" am-icon-plus ");
});

</script>	  