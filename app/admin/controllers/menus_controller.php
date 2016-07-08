<?php

/*****************************************************************************
 * Seevia 菜单管理
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
class MenusController extends AppController
{
    public $name = 'Menus';
    public $helpers = array('Html');
    public $uses = array('Profile','ProfileFiled','OperatorMenu','OperatorMenuI18n','SystemResource');
    public $components = array('RequestHandler','Phpexcel','Phpcsv');

    public function index()
    {
        $this->pageTitle = '菜单管理'.' - '.$this->configs['shop_name'];
        $this->set('title_for_layout', $this->pageTitle);
        $this->menu_path = array('root' => '/web_application/','sub' => '/menus/');
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => '菜单管理','url' => '/menus/');
        $this->set('navigations', $this->navigations);
        $this->OperatorMenu->set_locale($this->backend_locale);
        $menus_tree = $this->OperatorMenu->tree('tree', $this->backend_locale, $this->apps['codes'], $this->admin['action_codes']);//取树形结构
        $this->set('menus_tree', $menus_tree);
        $this->Profile->hasOne = array();
           $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_menu_export', 'Profile.status' => 1)));
		$this->set('profile_id',$profile_id);
    }
    //编辑页(新增编辑)
    public function view($id = 0)
    {
        if (!empty($id)) {
            $this->pageTitle = '编辑菜单 - 菜单管理'.' - '.$this->configs['shop_name'];
            $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
            $this->navigations[] = array('name' => '菜单管理','url' => '/menus/');
        } else {
            $this->pageTitle = '添加菜单 - 菜单管理'.' - '.$this->configs['shop_name'];
            $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
            $this->navigations[] = array('name' => '菜单管理','url' => '/menus/');
        }
        $this->set('title_for_layout', $this->pageTitle);
        $this->menu_path = array('root' => '/web_application/','sub' => '/menus/');
        if ($this->RequestHandler->isPost()) {
            $this->data['OperatorMenu']['orderby'] = !empty($this->data['OperatorMenu']['orderby']) ? $this->data['OperatorMenu']['orderby'] : 50;
            $this->OperatorMenu->saveAll($this->data['OperatorMenu']);
            $id = $this->OperatorMenu->id;
            $this->OperatorMenuI18n->deleteAll(array('operator_menu_id' => $id)); //删除原有多语言
            foreach ($this->data['OperatorMenuI18n'] as $k => $v) {
                $menuI18n_info = array(
                      'locale' => $k,
                       'operator_menu_id' => $id,
                      'name' => isset($v['name']) ? $v['name'] : '',
                );
                $this->OperatorMenuI18n->saveAll(array('OperatorMenuI18n' => $menuI18n_info));//更新多语言
            }
            foreach ($this->data['OperatorMenuI18n'] as $k => $v) {
                if ($k == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
            if (!empty($id)) {
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑菜单:id '.$id.' '.$userinformation_name, $this->admin['id']);
                }
            } else {
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加菜单:'.$userinformation_name, $this->admin['id']);
                }
            }
            $this->redirect('/menus/');
        }

        $this->data = $this->OperatorMenu->localeformat($id);
        $parentmenu = $this->OperatorMenu->find('all', array('conditions' => array('OperatorMenu.parent_id' => 0, 'OperatorMenuI18n.locale' => $this->backend_locale)));
        $this->set('parentmenu', $parentmenu);
        if (isset($this->data['OperatorMenuI18n'][$this->backend_locale]['name'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$this->data['OperatorMenuI18n'][$this->backend_locale]['name'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        }
        //leo20090722导航显示
        //$this->navigations[] = array('name'=>$this->data["OperatorMenuI18n"]["name"],'url'=>'');
        $this->set('navigations', $this->navigations);
        //取版本标识
        /*$this->SystemResource->set_locale($this->locale);
        $this->set('section',$this->SystemResource->find_assoc('section'));*/
    }
    public function remove($id)
    {
    	Configure::write('debug', 0);
        $this->layout = 'ajax';
        $result = $this->OperatorMenu->find('first', array('conditions' => array('parent_id' => $id)));
        if ($result) {
            $result['flag'] = 0;
            $result['message'] = '删除失败，该菜单还有子菜单';
        } else {
            $pn = $this->OperatorMenuI18n->find('list', array('fields' => array('OperatorMenuI18n.operator_menu_id', 'OperatorMenuI18n.name'), 'conditions' => array('OperatorMenuI18n.operator_menu_id' => $id, 'OperatorMenuI18n.locale' => $this->locale)));
            $this->OperatorMenu->delete($id);
            $this->OperatorMenuI18n->deleteAll(array('operator_menu_id' => $id)); //删除原有多语言

            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除菜单:id '.$id, $this->admin['id']);
            }
            $result['flag'] = 1;
            $result['message'] = $this->ld['delete_the_ad_list_success'];
        }
        die(json_encode($result));
    }
    //批量删除
    public function batch_operations()
    {
    
        /*判断权限*/
        if (!$this->operator_privilege('resources_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        $user_checkboxes = $_REQUEST['checkboxes'];
        //pr($user_checkboxes);die();
        foreach ($user_checkboxes as $k => $v) {
        $ids_arr=$this->OperatorMenu->find('all',array('conditions'=>array('OperatorMenu.parent_id' => $v)));
        //pr($ids_arr);die();
        foreach($ids_arr as $kk =>$vv){
        	$this->OperatorMenu->delete(array('OperatorMenu.id' => $vv['OperatorMenu']['id']));
        	$this->OperatorMenuI18n->deleteAll(array('OperatorMenuI18n.operator_menu_id' => $vv['OperatorMenu']['id']));
        }
            $this->OperatorMenu->delete(array('OperatorMenu.id' => $v));
            
        }
       
       
        $result['flag'] = 1;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    
    //菜单管理上传
public function menu_upload(){
	  Configure::write('debug', 0);
        $this->operation_return_url(true);//设置操作返回页面地址

           $this->menu_path = array('root' => '/web_application/','sub' => '/dictionaries/');
           $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
           $this->navigations[] = array('name' => $this->ld['menu_manage'],'url' => '/menus/');
           $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
           $this->set('title_for_layout', $this->ld['menu_manage'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
           $this->Profile->hasOne = array();
           $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_menu_export', 'Profile.status' => 1)));
		$this->set('profile_id',$profile_id);
	
    }



//菜单管理cvs查看
 public function menu_uploadpreview()
    {
    	Configure::write('debug', 1);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/menus/menu_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_menu_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
                            $key_arr = array();
                            foreach($fields_array as $k=>$v){
                            	$key_arr[] = $v;
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                if($row_count!=$num_count){
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/menus/menu_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/menus/menu_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            //pr($fields);pr($key_arr);die();
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
			$upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        if(isset($v['OperatorMenu']['parent_id'])){
					$parent_id=$v['OperatorMenu']['parent_id']!=''?$v['OperatorMenu']['parent_id']:0;
				}
                        if(($parent_id!=0 && $v['OperatorMenu']['link']!=""&&$v['OperatorMenu']['operator_action_code']!="") || $parent_id==0){
                        $OperatorMenu_condition='';
                        if(isset($parent_id)){	
                        	$OperatorMenu_condition['OperatorMenu.parent_id']=$parent_id;
                        }
                        if(!empty($v['OperatorMenu']['operator_action_code'])){
                        	$OperatorMenu_condition['OperatorMenu.operator_action_code']=$v['OperatorMenu']['operator_action_code'];
                        }
                         if(!empty($v['OperatorMenu']['section'])){
                        	$OperatorMenu_condition['OperatorMenu.section']=$v['OperatorMenu']['section'];
                        }
                      
                        $OperatorMenu_first = $this->OperatorMenu->find('first', array('conditions' =>$OperatorMenu_condition));
                        $v['OperatorMenu']['id']=isset($OperatorMenu_first['OperatorMenu']['id'])?$OperatorMenu_first['OperatorMenu']['id']:0;
                        $v['OperatorMenu']['parent_id']=isset($parent_id)?$parent_id:0;
                        $v['OperatorMenu']['orderby']=isset($v['OperatorMenu']['orderby'])?$v['OperatorMenu']['orderby']:50;
                        $v['OperatorMenu']['status']=isset($v['OperatorMenu']['status'])?$v['OperatorMenu']['status']:1;
                        	if( $s1=$this->OperatorMenu->save($v['OperatorMenu']) ){
                        		$OperatorMenu_id=$this->OperatorMenu->id;
                        	}
                        $OperatorMenuI18n_condition='';
                        if(isset($OperatorMenu_id)){
                        	$OperatorMenuI18n_condition['OperatorMenuI18n.operator_menu_id']=$OperatorMenu_id;
                        }
                       
                        if(isset($v['OperatorMenuI18n']['locale']) && !empty($v['OperatorMenuI18n']['locale'])){
                        	$OperatorMenuI18n_condition['OperatorMenuI18n.locale']= $v['OperatorMenuI18n']['locale'];
                        }
                      	$OperatorMenuI18n_first = $this->OperatorMenuI18n->find('first', array('conditions' => $OperatorMenuI18n_condition));
                        $v['OperatorMenuI18n']['id']=isset($OperatorMenuI18n_first['OperatorMenuI18n']['id'])?$OperatorMenuI18n_first['OperatorMenuI18n']['id']:0;
                        $v['OperatorMenuI18n']['operator_menu_id']=isset($OperatorMenu_id)?$OperatorMenu_id:'';
                        if(isset($v['OperatorMenuI18n']['operator_menu_id'])&&$v['OperatorMenuI18n']['operator_menu_id']!=''){	$s2=$this->OperatorMenuI18n->save($v['OperatorMenuI18n']); }
                        	 if( isset($s1)&&!empty($s1)&&isset($s2)&&!empty($s2)){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                    }
                    }
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/menus/'</script>";
		            die();
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/menus/menu_upload/'</script>";
                    	
                }
         
    }

      /////////////////////////////////////////////
      public function fgetcsv_reg($handle, $length = null, $d = ',', $e = '"')
      {
          $d = preg_quote($d);
          $e = preg_quote($e);
          $_line = '';
          $eof = false;
          while ($eof != true) {
              $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
              $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
              if ($itemcnt % 2 == 0) {
                  $eof = true;
              }
          }
          $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
          $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
          preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
          $_csv_data = $_csv_matches[1];
          for ($_csv_i = 0; $_csv_i < count($_csv_data); ++$_csv_i) {
              $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s', '$1', $_csv_data[$_csv_i]);
              $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
          }

          return empty($_line) ? false : $_csv_data;
      }



		 
//菜单管理csv
public function download_menu_csv_example($out_type = 'OperatorMenu'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $this->OperatorMenu->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_menu_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
  	//pr($tmp);pr($fields_array);die();
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $OperatorMenu_info = $this->OperatorMenu->find('all', array('fields'=>array('OperatorMenu.parent_id','OperatorMenu.operator_action_code','OperatorMenu.app_code','OperatorMenu.link','OperatorMenu.section','OperatorMenu.status','OperatorMenu.orderby','OperatorMenuI18n.locale','OperatorMenuI18n.name'),'order' => 'OperatorMenu.id desc','conditions'=>array('OperatorMenu.parent_id  <>' => 0),'limit'=>10));
	//pr($Resource_info);die();

            
              //循环数组
              foreach($OperatorMenu_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                 
                                         $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]])?$v[$fields_ks[0]][$fields_ks[1]]:'';
	                  
	               
	              }
	              //pr($user_tmp);die();
	              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}
//全部导出   
public function all_export_csv($out_type = 'OperatorMenu'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $this->OperatorMenu->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_menu_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
  //	pr($tmp);
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $OperatorMenu_info = $this->OperatorMenu->find('all', array('fields'=>array('OperatorMenu.parent_id','OperatorMenu.operator_action_code','OperatorMenu.app_code','OperatorMenu.link','OperatorMenu.section','OperatorMenu.status','OperatorMenu.orderby','OperatorMenuI18n.locale','OperatorMenuI18n.name'),'order' => 'OperatorMenu.id desc'));
		//pr($OperatorRole_info);die();
                $name_id_menu = $this->OperatorMenuI18n->find('list', array('fields' => array('operator_menu_id', 'name'), 'order' => 'OperatorMenuI18n.id desc'));

            
              //循环数组
              foreach($OperatorMenu_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                if ($fields_ks[1] == 'parent_id') {
                                         $user_tmp[] = isset($name_id_menu[$v['OperatorMenu']['parent_id']]) ? $name_id_menu[$v['OperatorMenu']['parent_id']] : '';
                                     } else {
                                         $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
                                     }
	                  
	                 
	              }
	              //pr($user_tmp);die();
	              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        	exit;
      
}  
    
 //选择导出   
public function choice_export($out_type = 'OperatorMenu'){
 Configure::write('debug', 1);
     $this->layout="ajax";
     $this->OperatorMenu->set_locale($this->backend_locale);
     $user_checkboxes = $_REQUEST['checkboxes'];
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_menu_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
  //	pr($tmp);
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $OperatorMenu_conditions='';
          $OperatorMenu_conditions['OR']['OperatorMenu.parent_id']=$user_checkboxes; 
          $OperatorMenu_conditions['OR']['OperatorMenu.id']=$user_checkboxes; 
          $OperatorMenu_info = $this->OperatorMenu->find('all', array('fields'=>array('OperatorMenu.parent_id','OperatorMenu.operator_action_code','OperatorMenu.app_code','OperatorMenu.link','OperatorMenu.section','OperatorMenu.status','OperatorMenu.orderby','OperatorMenuI18n.locale','OperatorMenuI18n.name'),'order' => 'OperatorMenu.id desc','conditions'=>$OperatorMenu_conditions));
		//pr($OperatorRole_info);die();
                $name_id_menu = $this->OperatorMenuI18n->find('list', array('fields' => array('operator_menu_id', 'name'), 'order' => 'OperatorMenuI18n.id desc'));

            
              //循环数组
              foreach($OperatorMenu_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                if ($fields_ks[1] == 'parent_id') {
                                         $user_tmp[] = isset($name_id_menu[$v['OperatorMenu']['parent_id']]) ? $name_id_menu[$v['OperatorMenu']['parent_id']] : '';
                                     } else {
                                         $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
                                     }
	                  
	                 
	              }
	              //pr($user_tmp);die();
	              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpexcel->output($out_type.date('YmdHis').'.xls', $newdatas);
        	exit;
      
}  
  
    
}
