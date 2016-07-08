<style type="text/css">
body{
font-weight:normal;
font-size:12px;
}
td{
	list-style-type:none;
word-wrap:break-word;
word-break:break-all;
}	
</style>

<div class=".am-container">
  <div class="am-u-sm-8 am-u-md-8 am-u-lg-8 am-u-sm-centered">
       <table class="am-table am-table-striped am-table-hover">
   	<thead>
        <tr>
            <th>		
   			 所有项目
		</th>
	</tr>
       </thead>
    	<tbody>  
                		<?php foreach($project_list as $k=>$v){ ?>
                	 <tr>
                		<td><a  href="<?php echo $html->url('/apidocs/api?project_code='.$k.'&category_code=');       ?>" >
									<?php  echo $v;  ?></a>	
				</td>
			</tr>
		<?php }  ?>

        </tbody>  
         
	</table>
     </div>
</div>