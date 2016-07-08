<?php

class OperatorActionsController extends AppController
{
    public $name = 'OperatorActions';
    public $components = array('Phpexcel','Phpcsv','Pagination','RequestHandler');
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $uses = array('OperatorAction','OperatorActionI18n');

    public function index()
    {
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['rights_management'],'url' => '/operator_actions/');
        $this->menu_path = array('root' => '/web_application/','sub' => '/operator_actions/');
        $condition = '';
        if (isset($this->params['url']['name']) && !empty($this->params['url']['name'])) {
            $condition['OperatorActionI18n.name like'] = '%'.$this->params['url']['name'].'%';
        }
        $this->OperatorAction->set_locale($this->locale);

        $total = $this->OperatorAction->find('count', array('conditions' => $condition));
        $sortClass = 'OperatorAction';
        $page = 1;
        $rownum = isset($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters = array($rownum,$page);
        $options = array();
        $page = $this->Pagination->init($condition, $parameters, $options, $total, $rownum, $sortClass);

        $operator_action_data = $this->OperatorAction->find('all', array('conditions' => $condition, 'rownum' => $rownum, 'page' => $page, 'order' => 'OperatorAction.orderby asc'));
      //  $operator_action_data = $this->OperatorAction->find("threaded",array("order"=>"orderby asc"));
      //  $operator_action_data = $this->OperatorAction->find("all",array("order"=>"orderby asc"));
        $action_tree = $this->OperatorAction->alltree();
        //pr($action_tree);
        $this->set('action_tree', $action_tree);
        $this->set('operator_action_data', $operator_action_data);
        $this->set('title_for_layout', '权限管理'.' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }
    public function view($id = 0)
    {
        $this->set('title_for_layout', '添加/编辑权限- 权限管理'.' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['rights_management'],'url' => '/operator_actions/');
        $this->navigations[] = array('name' => $this->ld['add_edit_permissions'],'url' => '');
        $this->menu_path = array('root' => '/web_application/','sub' => '/operator_actions/');
        if ($this->RequestHandler->isPost()) {
            $this->data['OperatorAction']['orderby'] = !empty($this->data['OperatorAction']['orderby']) ? $this->data['OperatorAction']['orderby'] : '50';
            if (isset($this->data['OperatorAction']['id']) && $this->data['OperatorAction']['id'] != 0) {
                $this->OperatorAction->save(array('OperatorAction' => $this->data['OperatorAction'])); //关联保存
                $id = $this->data['OperatorAction']['id'];
            } else {
                $this->OperatorAction->saveAll(array('OperatorAction' => $this->data['OperatorAction'])); //关联保存
                $id = $this->OperatorAction->getLastInsertId();
            }
            $this->OperatorActionI18n->deleteall(array('operator_action_id' => $id)); //删除原有多语言
            foreach ($this->data['OperatorActionI18n'] as $v) {
                $v['operator_action_id'] = $id;
                $this->OperatorActionI18n->saveAll(array('OperatorActionI18n' => $v));//更新多语言
            }
             //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'添加/编辑权限:id '.$id, $this->admin['id']);
            }
            $this->redirect('/operator_actions/');
        }

        $operator_action_data = $this->OperatorAction->localeformat($id);
        //var_dump($operator_action_data);
        $this->set('operator_action_data', $operator_action_data);
        //pr($operator_action_data);
        $this->OperatorAction->set_locale($this->locale);
        $operator_action_parent = $this->OperatorAction->find('threaded');
        $action_tree = $this->OperatorAction->tree($this->locale);
        $this->set('action_tree', $action_tree);
        //pr($operator_action_parent);
        $this->set('operator_action_parent', $operator_action_parent);
    }

    //列表修改排序//无用函数
    public function update_operator_action_orderby()
    {
        $this->OperatorAction->hasMany = array();
        $this->OperatorAction->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = '请输入正确的排序数据！';
        }
        if (is_numeric($val) && $this->OperatorAction->save(array('id' => $id, 'orderby' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //列表状态修改//无用函数
    public function toggle_on_status()
    {
        $this->OperatorAction->hasMany = array();
        $this->OperatorAction->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->OperatorAction->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = '删除权限失败';
        $this->OperatorAction->deleteAll(array('OperatorAction.id' => $id));
        $this->OperatorActionI18n->deleteAll(array('operator_action_id' => $id));
        $this->removechild($id);
        $result['flag'] = 1;
        $result['message'] = '删除权限成功';
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    public function batch_operations()
    {
        $this->OperatorAction->hasOne = array();
        $user_checkboxes = $_REQUEST['checkboxes'];
        foreach ($user_checkboxes as $k => $v) {
        $ids_arr=$this->OperatorAction->find('all',array('conditions'=>array('OperatorAction.parent_id' => $v)));
        //pr($ids_arr);die();
        foreach($ids_arr as $kk =>$vv){
            $id_arr=$this->OperatorAction->find('all',array('conditions'=>array('OperatorAction.parent_id' => $vv['OperatorAction']['id'])));
        	foreach($id_arr as $kkk =>$vvv){
        		$this->OperatorAction->delete(array('OperatorAction.id' => $vvv['OperatorAction']['id']));
        		$this->OperatorActionI18n->deleteAll(array('OperatorActionI18n.operator_action_id' => $vvv['OperatorAction']['id']));
        	}
        	$this->OperatorAction->delete(array('OperatorAction.id' => $vv['OperatorAction']['id']));
        	$this->OperatorActionI18n->deleteAll(array('OperatorActionI18n.operator_action_id' => $vv['OperatorAction']['id']));
        }
            $this->OperatorAction->delete(array('OperatorAction.id' => $v));
            
        }
        
       
       $result['flag'] = 1;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function removechild($id = 0)
    {
        $child_actions = $this->OperatorAction->find('list', array('fields' => array('OperatorAction.id'), 'conditions' => array('OperatorAction.parent_id' => $id)));
        if (!empty($child_actions)) {
            foreach ($child_actions as $v) {
                $this->OperatorAction->deleteAll(array('OperatorAction.id' => $v));
                $this->OperatorActionI18n->deleteAll(array('operator_action_id' => $v));
                $this->removechild($v);
            }
        }
    }
    
     //批量上传
     public function operator_action_upload()
    {
        $this->menu_path = array('root' => '/web_application/','sub' => '/operator_actions/');
        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['rights_management'],'url' => '/operator_actions/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
        $this->set('title_for_layout', $this->ld['rights_management'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
    }
	
    public function operator_action_uploadpreview()
    {
        ////////////判断权限
        Configure::write('debug', 1);
        $success_num=0;
                if (isset($_POST['sub1']) && $_POST['sub1'] == 1 && !empty($_FILES['file'])) {
                    $this->menu_path = array('root' => '/web_application/','sub' => '/operator_actions/');
		        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
		        $this->navigations[] = array('name' => $this->ld['rights_management'],'url' => '/operator_actions/');
		        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
		        $this->set('title_for_layout', $this->ld['rights_management'].' - '.$this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/operator_actions/operator_action_upload';</script>";
                            die();
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                              $fields_array = array(
                            'OperatorAction.parent_id',
         				'OperatorAction.code',
         				'OperatorAction.section',
         					'OperatorAction.status',
         				'OperatorAction.orderby','OperatorActionI18n.locale',
                            'OperatorActionI18n.name',
             			'OperatorActionI18n.operator_action_values',
                            'OperatorActionI18n.description');

                            $fields = array(
                                $this->ld['previous_operator_action'],
                           	  $this->ld['userconfig_code'],
                           	  $this->ld['version'],
                           	  $this->ld['z_status'],
                           	  $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                                $this->ld['value'],
                                $this->ld['z_description'], );
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $key_arr[] = isset($v) ? $v : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                 if($row_count!=$num_count){
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/operator_actions/operator_action_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = empty($v) ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/operator_actions/operator_action_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
                    	$upload_num=count($checkbox_arr);
                    //	pr($this->data);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        if(isset($v['OperatorAction']['code']) && $v['OperatorAction']['code']!=''){
                        $OperatorAction_list = $this->OperatorAction->find('list', array('fields'=>array('code','id'),'order' => 'OperatorAction.id desc'));
			//	pr($InformationResource_list);
				$parent_id=isset($OperatorAction_list[$v['OperatorAction']['parent_id']])?$OperatorAction_list[$v['OperatorAction']['parent_id']]:0;
                        //pr($parent_id);die();
                        $OperatorAction_condition='';	
                        if(isset($parent_id)&&$parent_id!=''){
                        $OperatorAction_condition['OperatorAction.parent_id']=$parent_id;
                        }
                        if(isset($v['OperatorAction']['code'])&&$v['OperatorAction']['code']!=''){
                        $OperatorAction_condition['OperatorAction.code']=$v['OperatorAction']['code'];
                        }
                        if(isset($v['OperatorAction']['section'])&&$v['OperatorAction']['section']!=''){
                        $OperatorAction_condition['OperatorAction.section']=$v['OperatorAction']['section'];
                        }
                        if(isset($v['OperatorAction']['status'])&&$v['OperatorAction']['status']!=''){
                        $OperatorAction_condition['OperatorAction.status']=$v['OperatorAction']['status'];
                        }
                        //pr($OperatorAction_condition);
                        $OperatorAction_first = $this->OperatorAction->find('first', array('conditions' =>$OperatorAction_condition));
                        $v['OperatorAction']['id']=isset($OperatorAction_first['OperatorAction']['id'])?$OperatorAction_first['OperatorAction']['id']:0;
                        //pr($v['OperatorAction']['id']);die();
                        $v['OperatorAction']['parent_id']=isset($parent_id)?$parent_id:0;
                        $v['OperatorAction']['status']=isset($v['OperatorAction']['status'])?$v['OperatorAction']['status']:1;
                        $v['OperatorAction']['orderby']=isset($v['OperatorAction']['orderby'])?$v['OperatorAction']['orderby']:50;
					if( $s1=$this->OperatorAction->save($v['OperatorAction']) ){
                        		$OperatorAction_id=$this->OperatorAction->id;
                        	}
                        $OperatorActionI18n_condition='';
                        if(isset($OperatorAction_id)&&$OperatorAction_id!=''){
                        	$OperatorActionI18n_condition['OperatorActionI18n.operator_action_id']=$OperatorAction_id;
                        	
                        if(isset($v['OperatorActionI18n']['locale'])&&$v['OperatorActionI18n']['locale']!=''){
                        	$OperatorActionI18n_condition['OperatorActionI18n.locale']=$v['OperatorActionI18n']['locale'];
                        }
                        //pr($OperatorActionI18n_condition);		
                        $OperatorActionI18n_first = $this->OperatorActionI18n->find('first', array('conditions' => $OperatorActionI18n_condition));
                        $v['OperatorActionI18n']['id']=isset($OperatorActionI18n_first['OperatorActionI18n']['id'])?$OperatorActionI18n_first['OperatorActionI18n']['id']:0;
                        //pr($v['OperatorActionI18n']['id']);die();
                        $v['OperatorActionI18n']['operator_action_id']=isset($OperatorAction_id)?$OperatorAction_id:0;
				if(isset($v['OperatorActionI18n']['operator_action_id'])&&$v['OperatorActionI18n']['operator_action_id']!=''){
                        	$s2=$this->OperatorActionI18n->save($v['OperatorActionI18n']);
                		}
                		 if( isset($s1)&&!empty($s1)&&isset($s2)&&!empty($s2)){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                     }
                    }
                    }
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/operator_actions/'</script>";

                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/operator_actions/operator_action_upload/'</script>";
                }
    }
    //导出信息资源
      public function download_operator_action_csv_example()
      {
      	  Configure::write('debug', 1);
      	  $this->layout="ajax";
      	  $this->OperatorAction->set_locale($this->backend_locale);
              //定义一个数组
         $fields_array = array(
                            'OperatorAction.parent_id',
         				'OperatorAction.code',
         				'OperatorAction.section',
         					'OperatorAction.status',
         				'OperatorAction.orderby','OperatorActionI18n.locale',
                            'OperatorActionI18n.name',
             			'OperatorActionI18n.operator_action_values',
                            'OperatorActionI18n.description');

                            $fields = array(
                                $this->ld['previous_operator_action'],
                           	  $this->ld['userconfig_code'],
                           	  $this->ld['version'],
                           	  $this->ld['z_status'],
                           	  $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                                $this->ld['value'],
                                $this->ld['z_description']);
          $newdatas = array();
          $newdatas[] = $fields;
          //查询所有表里面所有信息 查询 10 条信息
          $OperatorAction_info = $this->OperatorAction->find('all', array('fields'=>array('OperatorActionI18n.locale',
                            'OperatorActionI18n.name',
             			'OperatorActionI18n.operator_action_values',
                            'OperatorActionI18n.description',
                            'OperatorAction.parent_id',
         				'OperatorAction.code',
         				'OperatorAction.section',
         					'OperatorAction.status',
         				'OperatorAction.orderby'),'order' => 'OperatorAction.id desc', 'limit' => 10));
          $OperatorAction_list = $this->OperatorAction->find('list', array('fields'=>array('id','code'),'order' => 'OperatorAction.id desc'));
          foreach ($OperatorAction_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  if($fields_ks[1]=='parent_id'){
                  	 $user_tmp[]= isset($OperatorAction_list[$v['OperatorAction']['parent_id']])?$OperatorAction_list[$v['OperatorAction']['parent_id']]:'';
                  }else{
                  	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
                  }
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
          $nameexl = $this->ld['rights_management'].date('Ymd').'.csv';
          $this->Phpcsv->output($nameexl, $newdatas);
          die();
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
      
      //全部导出
      public function all_export_csv()
      {
      	  Configure::write('debug', 0);
      	  $this->layout="ajax";
      	  $this->OperatorAction->set_locale($this->backend_locale);
              //定义一个数组
        $fields_array = array(
                            'OperatorAction.parent_id',
         				'OperatorAction.code',
         				'OperatorAction.section',
         					'OperatorAction.status',
         				'OperatorAction.orderby','OperatorActionI18n.locale',
                            'OperatorActionI18n.name',
             			'OperatorActionI18n.operator_action_values',
                            'OperatorActionI18n.description');

                            $fields = array(
                                $this->ld['previous_operator_action'],
                           	  $this->ld['userconfig_code'],
                           	  $this->ld['version'],
                           	  $this->ld['z_status'],
                           	  $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                                $this->ld['value'],
                                $this->ld['z_description'] );
          $newdatas = array();
          $newdatas[] = $fields;
          //查询所有表里面所有信息 
          $OperatorAction_info = $this->OperatorAction->find('all', array('fields'=>array('OperatorActionI18n.locale',
                            'OperatorActionI18n.name',
             			'OperatorActionI18n.operator_action_values',
                            'OperatorActionI18n.description',
                            'OperatorAction.parent_id',
         				'OperatorAction.code',
         				'OperatorAction.section',
         					'OperatorAction.status',
         				'OperatorAction.orderby'),'order' => 'OperatorAction.id desc'));
          $OperatorActionI18n_list = $this->OperatorActionI18n->find('list', array('fields'=>array('operator_action_id','name'),'order' => 'OperatorActionI18n.id desc'));

          foreach ($OperatorAction_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  if($fields_ks[1]=='parent_id'){
                  	 $user_tmp[]= isset($OperatorActionI18n_list[$v['OperatorAction']['parent_id']])?$OperatorActionI18n_list[$v['OperatorAction']['parent_id']]:'';
                  }else{
                  	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
                  }
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
          $nameexl = $this->ld['rights_management'].$this->ld['export'].date('Ymd').'.xls';
          $this->Phpexcel->output($nameexl, $newdatas);
          die();
      }
      
      //选择导出
      public function choice_export()
      {
      	  Configure::write('debug', 0);
      	  $this->layout="ajax";
      	  $this->OperatorAction->set_locale($this->backend_locale);
      	  $user_checkboxes = $_REQUEST['checkboxes'];
              //定义一个数组
          $fields_array = array(
                            'OperatorAction.parent_id',
         				'OperatorAction.code',
         				'OperatorAction.section',
         					'OperatorAction.status',
         				'OperatorAction.orderby','OperatorActionI18n.locale',
                            'OperatorActionI18n.name',
             			'OperatorActionI18n.operator_action_values',
                            'OperatorActionI18n.description');

                            $fields = array(
                                $this->ld['previous_operator_action'],
                           	  $this->ld['userconfig_code'],
                           	  $this->ld['version'],
                           	  $this->ld['z_status'],
                           	  $this->ld['sort_by'],$this->ld['s_language'],
                                $this->ld['z_name'],
                                $this->ld['value'],
                                $this->ld['z_description'] );
          $newdatas = array();
          $newdatas[] = $fields;
          $OperatorAction_conditions['AND']['OperatorAction.status']=1; 
          $OperatorAction_conditions['OR']['OperatorAction.parent_id']=$user_checkboxes; 
          $OperatorAction_conditions['OR']['OperatorAction.id']=$user_checkboxes; 
          $OperatorAction_info = $this->OperatorAction->find('all', array('fields'=>array('OperatorActionI18n.locale',
                            'OperatorActionI18n.name',
             			'OperatorActionI18n.operator_action_values',
                            'OperatorActionI18n.description',
                            'OperatorAction.parent_id',
         				'OperatorAction.code',
         				'OperatorAction.section',
         					'OperatorAction.status',
         				'OperatorAction.orderby'),'order' => 'OperatorAction.id desc','conditions'=>$OperatorAction_conditions));
          $OperatorActionI18n_list = $this->OperatorActionI18n->find('list', array('fields'=>array('operator_action_id','name'),'order' => 'OperatorActionI18n.id desc'));

          foreach ($OperatorAction_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  if($fields_ks[1]=='parent_id'){
                  	 $user_tmp[]= isset($OperatorActionI18n_list[$v['OperatorAction']['parent_id']])?$OperatorActionI18n_list[$v['OperatorAction']['parent_id']]:'';
                  }else{
                  	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
                  }
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
          $nameexl = $this->ld['rights_management'].$this->ld['export'].date('Ymd').'.xls';
          $this->Phpexcel->output($nameexl, $newdatas);
          die();
      }
    
    
    
}
