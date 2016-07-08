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

</style>
<div class=" am-g am-container">
	       	<?php $project_code=isset($_GET['project_code'])?$_GET['project_code']:''; //pr($projectcode);?>
		<?php $category_code=isset($_GET['category_code'])&&($_GET['category_code']=='')?$category_info['ApiCategory']['code']:$_GET['category_code']; //pr($category_code);?>
<!--//menu start-->
<div class="am-g">
<div class="am-u-sm-12 am-u-md-12 am-u-lg-12" style=" height:100px;">
	<div style="padding-top:8px;"><?php echo $project_info['ApiProject']['name']; ?>	</div>
     <div class="menu" style="padding-top:20px; ">
   
                    <a href="<?php echo $html->url('/apidocs/'); ?>">
					<?php echo $ld['api_documentation_center']; ?>
			</a> > 
			<a href="<?php echo $html->url('/apidocs/api?project_code='.$project_code.'&category_code='); ?>">
				<?php echo $project_info['ApiProject']['name']; ?>	
			</a> >          	   
				<?php 	 foreach($category_list as $k=>$v){ if($k==$category_code){echo $v;} } ?>  

                </div>	
</div>
</div>
<!--// menu end
//所有类目 start-->	
<div class="am-g">
	<div class="am-show-sm-only">
	<button class="am-btn am-btn-primary" data-am-collapse="{target: '#collapse-navs'}">所有类目 <i class="am-icon-bars"></i></button>
<nav>
  <ul id="collapse-navs" class="am-nav am-collapse">
    <?php foreach($category_list as $k=>$v){  ?>
    <li><a  href="<?php echo $html->url('/apidocs/api?project_code='.$project_code.'&category_code='.$k );  ?>" >
									<?php  echo $v;  ?></a></li>
  	<?php }  ?>
  </ul>
</nav>
	</div>
  <div class=" am-u-md-3 am-u-lg-3  am-hide-sm-only">
       <table class="am-table am-table-striped am-table-hover">
   	<thead>
        <tr>
            <th>		
   			 所有类目
		</th>
	</tr>
       </thead>
    	<tbody>  
                	  <?php foreach($category_list as $k=>$v){  ?>
                	 <tr>
                		<td><a  href="<?php echo $html->url('/apidocs/api?project_code='.$project_code.'&category_code='.$k );  ?>" >
									<?php  echo $v;  ?></a>
				</td>
			</tr>
				<?php }  ?>

        </tbody>  
         
	</table>
     </div>
<!--//所有类目 end
//列表 start-->
	
          <div class=" am-u-md-9 am-u-lg-9 am-u-sm-12">
        	<div class="am-g" style="margin-left:10px;">
        	  		<h3><?php 	 foreach($category_list as $k=>$v){ if($k==$category_code){echo $v;} } ?>  </h3>
        	  <p><?php echo $category_info['ApiCategory']['description']; ?></p>
        	  </div>
    
        	<table class="am-table am-table-bd am-table-striped am-table-hover">
    <thead>
        <tr>
               <th width="39%">API列表</th> 
                               <th width="10%">类型</th> 
                               <th width="51%">描述</th> 
        </tr>
    </thead>
   
                        <tbody> 
                        	  <?php foreach($method_infos as $v){ ?>
                        	                        	  <tr> 
                                <td><div class="word" ><a  href="<?php echo $html->url('/apidocs/detail?project_code='.$project_code.'&category_code='.$category_code.'&method_id='.$v['ApiMethod']['id'] )?>" >
									<?php  echo $v['ApiMethod']['name'];  ?></a></div></td>
                              	<td class="word" ><?php   switch($v['ApiMethod']['type']){ case '0': echo '免费'; break; case '1' : echo '收费';break;}  ?></td>
                                 <td><div class="word"><?php  echo $v['ApiMethod']['description'];  ?></div></td> 
                            </tr> 
               <?php } ?>
                          </tbody> 
                      
</table>
</div>
<!--列表 end	   -->    
			     
</div>
		
		
		
</div>