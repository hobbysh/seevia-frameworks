<header id="am-header">
  <div class="am-g">
      <?php if($this->params['controller']=="categories" && $this->params['action']="view"){ ?>
	  <button style="margin:1rem 0; float:left" class="am-btn am-btn-sm am-btn-secondary am-show-sm-only" data-am-offcanvas="{target: '#prodcut_category', effect: 'push'}"><span >商品分类</span> </button>
      <?php } ?>
    <h1 class="am-topbar-brand am-hide-sm-only">
        <?php if(!empty($configs['shop_logo'])){
        	echo $svshow->link($svshow->image($configs['shop_logo'],LOCALE),"/",array("style"=>"height:50px;display:block;overflow:hidden;position:relative;top:-1px;"));
        }else{
        	echo $svshow->link($svshow->image('/theme/default/img/'.$template_style.'/logo.jpg',LOCALE),"/",array("style"=>"height:50px;display:block;overflow:hidden;position:relative;top:-3px;"));
        }?>
    </h1>
    	
    <button style="padding:10px 15px;" class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-secondary am-show-sm-only "
            data-am-collapse="{target: '#collapse-head'}"><span class="am-sr-only">导航切换</span> <span
        class="am-icon-bars"></span></button>

    <div class="am-collapse am-topbar-collapse" id="collapse-head">
    	<?php if(isset($navigations['T'])){?>
     <ul class="am-nav am-nav-pills am-topbar-nav">
      <?php $navigations_t_count=count($navigations['T']);
      	foreach($navigations['T'] as $k=>$v){?>
      <?php if(isset($v['SubMenu']) && sizeof($v['SubMenu']) >0) {  //含二级菜单 
      	  ?>
          <li class="am-dropdown  am-topbar-right am-login-btn" data-am-dropdown>
          <a class="am-dropdown-toggle   " data-am-dropdown-toggle href="javascript:;">
            <?php echo (isset($v['NavigationI18n']['name']))?$v['NavigationI18n']['name']:"-";?>&nbsp;<span class="am-icon-caret-down"></span>
          </a>
          <ul class="am-dropdown-content">
            <li class="am-dropdown-header"><?php echo $svshow->link($v['NavigationI18n']['name'],$v['NavigationI18n']['url'],array('target'=>$v['Navigation']['target']));?></li>
            <?php foreach($v['SubMenu'] as $kk=>$vv){ ?>
            <li><?php echo $svshow->link($vv['NavigationI18n']['name'],$vv['NavigationI18n']['url'],array('target'=>$vv['Navigation']['target']));?></li>
            <?php }  // foreach top2?>
          </ul>
        </li>
	<?php }?>
	<?php if(!isset($v['SubMenu']) ) { ?>
		<li><?php echo $svshow->link($v['NavigationI18n']['name'],$v['NavigationI18n']['url'],array('target'=>$v['Navigation']['target']));?></li>
        <?php }?>
        	
        <?php } // foreach top1?>
      </ul>
      <?php }?>
	<?php if(count($languages)>1){?>	 
      <div class="bottom_navigations am-show-sm-down">
<div class="am-topbar am-container" style="padding:0;margin:0px auto 0;border-bottom:none;">
  <?php if(isset($navigations['B'])){?>
  <ul class="am-nav am-nav-pills am-topbar-nav" >
    <?php $navigations_t_count=count($navigations['B']);
      foreach($navigations['B'] as $k=>$v){?>
      <?php if(isset($v['SubMenu']) && sizeof($v['SubMenu']) >0) {  //含二级菜单 ?>
    <li class="am-dropdown am-dropdown-up" data-am-dropdown>
      <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
        <?php echo (isset($v['NavigationI18n']['name']))?$v['NavigationI18n']['name']:"-";?><span class="am-icon-caret-up"></span>
      </a>
      <ul class="am-dropdown-content">
        <li class="am-dropdown-header"><?php echo $svshow->link($v['NavigationI18n']['name'],$v['NavigationI18n']['url'],array('target'=>$v['Navigation']['target']));?></li>
        <?php foreach($v['SubMenu'] as $kk=>$vv){ ?>
          <li><?php echo $svshow->link($vv['NavigationI18n']['name'],$vv['NavigationI18n']['url'],array('target'=>$vv['Navigation']['target']));?></li>
        <?php }  // foreach top2?>
      </ul>
    </li>
	<?php }?>
	<?php if(!isset($v['SubMenu']) ) { ?>
	<li><?php echo $svshow->link($v['NavigationI18n']['name'],$v['NavigationI18n']['url'],array('target'=>$v['Navigation']['target']));?></li>
    <?php }?>
  <?php } // foreach top1?>
  </ul>
 <?php }?>
</div>
</div>
   <div class="am-topbar-right">
      <a href="javascript:void(0)" class="language_change" data-am-modal="{target: '#language',width: 400, height: 225}"><?php echo $ld['language_switcher'] ?></a>
    </div>
    <?php }?>
    </div>
  </div>
  <input type='hidden' id='local' value="<?php echo LOCALE;?>">
</header>
<div style="clear:both;"></div>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="language">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
	  <?php echo $ld['language_switcher'];?>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
	<!-- 多语言选择 -->
	<?php if(count($languages)>1){?>
	  <div id="language">
		<?php $languages_count=count($languages);foreach($languages as $k=>$v){?>
		
		<p><?php echo $svshow->link($v['Language']['name'],(LOCALE==$k)?'javascript:void(0);':$v['Language']['url'],array('class'=>(LOCALE==$k)?'am-btn am-btn-secondary am-btn-lg':'color'));?></p>
		<?php }?>
		<!-- 记录当前语言 -->
		<input type='hidden' id='local' value="<?php echo LOCALE;?>">
		<!-- 记录当前语言 end -->
	  </div>
	<?php }?>
	<!-- 多语言选择 end -->
    </div>
  </div>
</div>
<script type="text/javascript">
var js_login_user_data=null;
<?php if(isset($_SESSION['User'])){ echo "js_login_user_data=".json_encode($_SESSION['User']).";"; } ?>
function users_login(){
    window.location.href=web_base+'/users/login';
}
function users_edit(){
    window.location.href=web_base+'/users/edit';
}
function users_logout(){
    window.location.href=web_base+'/users/logout';
}
</script>


