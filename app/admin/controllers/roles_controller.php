<?php

/*****************************************************************************
 * Seevia 角色管理
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
class RolesController extends AppController
{
    public $name = 'OperatorRoles';
    public $helpers = array('Html','Pagination');
    public $components = array('Phpexcel','Phpcsv','Pagination','RequestHandler','Email'); // Added
    public $uses = array('Profile','ProfileFiled','OperatorRole','OperatorRoleI18n','Operator','OperatorAction','OperatorActionI18n','Application','Language','OperatorLog');

    public function index()
    {
        /*判断权限*/
        $this->operator_privilege('operator_roles_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        /*end*/

        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operator_roles'],'url' => '/roles/');
        $this->OperatorRole->set_locale($this->locale);
        $condition = '';
        //角色搜索筛选条件
        $role_name = '';
        if (isset($this->params['url']['role_name']) && !empty($this->params['url']['role_name'])) {
            $condition['OperatorRoleI18n.name like'] = '%'.$this->params['url']['role_name'].'%';
            $role_name = $this->params['url']['role_name'];
        }
        $total = $this->OperatorRole->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'OperatorRole';
        $page = 1;
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters = array($rownum,$page);
        $options = array();
        $page = $this->Pagination->init($condition, $parameters, $options, $total, $rownum, $sortClass);
        $res = $this->OperatorRole->find('all', array('conditions' => $condition, 'rownum' => $rownum, 'page' => $page, 'order' => 'OperatorRole.created DESC'));

        $roles = $this->Operator->find('all');
        $role_list = array();
        if (!empty($res) && sizeof($res) > 0) {
            $operactions_ids = array();
            foreach ($res as $k => $v) {
                $role_list[$v['OperatorRole']['id']]['OperatorRole'] = $v['OperatorRole'];
                if (is_array($v['OperatorRoleI18n'])) {
                    $role_list[$v['OperatorRole']['id']]['OperatorRoleI18n'] = $v['OperatorRoleI18n'];
                }
                $action_lists = explode(';', $v['OperatorRole']['actions']);
                if (!empty($action_lists) && sizeof($role_list) > 0) {
                    foreach ($action_lists as $kk => $vv) {
                        $operactions_ids[$vv] = $vv;
                    }
                }

                $i = 1;
                foreach ($roles as $key => $value) {
                    $role_id = $value['Operator']['role_id'];
                    $arr = explode(';', $role_id);
                    if (in_array($role_list[$v['OperatorRole']['id']]['OperatorRole']['id'], $arr)) {
                        ++$i;
                    }
                }
                $role_list[$v['OperatorRole']['id']]['OperatorRole']['number'] = $i;
            }

            $this->OperatorAction->set_locale($this->backend_locale);
            $actionInfos = $this->OperatorAction->find('all', array('conditions' => array('OperatorAction.id' => $operactions_ids)));
            if (!empty($actionInfos) && sizeof($actionInfos) > 0) {
                $actionlist = array();
                foreach ($actionInfos as $k => $v) {
                    $actionlist[$v['OperatorAction']['id']] = $v['OperatorActionI18n']['name'];
                }

                foreach ($res as $k => $v) {
                    $action_lists = explode(';', $v['OperatorRole']['actions']);
                    $actiontxt = '';
                    if (!empty($action_lists) && sizeof($role_list) > 0) {
                        foreach ($action_lists as $kk => $vv) {
                            $actiontxt .= isset($actionlist[$vv]) ? $actionlist[$vv].';' : '';
                        }
                    }
                    if ($actiontxt != '') {
                        $actiontxt = substr($actiontxt, 0, strlen($actiontxt) - 1);
                    }
                    $role_list[$v['OperatorRole']['id']]['OperatorRole']['actionses'] = $actiontxt;
                }
            }
        }
        $this->set('role_list', $role_list);
        $this->set('role_name', $role_name);
        $this->set('title_for_layout', $this->ld['operator_roles'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
        $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_role_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }

    public function edit($id)
    {
        /*判断权限*/
        $this->operator_privilege('operator_roles_edit');
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        /*end*/
        $this->set('title_for_layout', $this->ld['role_edit_role'].' - '.$this->ld['operator_roles'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operator_roles'],'url' => '/roles/');
        $this->navigations[] = array('name' => $this->ld['role_edit_role'],'url' => '');
        $operators = $this->Operator->find('all');//取得操作员列表
        $this->set('operators', $operators);
        $this->set('role_id', $id);
        if ($this->RequestHandler->isPost()) {
            $this->data['OperatorRole']['orderby'] = !empty($this->data['OperatorRole']['orderby']) ? $this->data['OperatorRole']['orderby'] : 50;
            if (isset($_REQUEST['competence'])) {
                $competence = $_REQUEST['competence'];
                $competence = implode(';', $competence);
                $this->data['OperatorRole']['actions'] = $competence;
            }
            $this->OperatorRole->save($this->data); //保存
                foreach ($this->data['OperatorRoleI18n'] as $v) {
                    $operatorrolei18n_info = array(
                                //   'id'=>	isset($v['id'])?$v['id']:'',
                                   'id' => $v['id'],
                                   'locale' => $v['locale'],
                                   'operator_role_id' => isset($v['operator_role_id']) ? $v['operator_role_id'] : $this->data['OperatorRole']['id'],
                                   'name' => isset($v['name']) ? $v['name'] : '',
                             );
                    $this->OperatorRoleI18n->saveall(array('OperatorRoleI18n' => $operatorrolei18n_info));//更新多语言
                }
            foreach ($operators as $k => $v) {
                if ($v['Operator']['role_id'] == 0) {
                    if (isset($_REQUEST['operators']) && count($_REQUEST['operators']) > 0) {
                        if (in_array($v['Operator']['id'], $_REQUEST['operators'])) {
                            $operators[$k]['Operator']['role_id'] = $this->data['OperatorRole']['id'];
                            $this->Operator->save($operators[$k]);
                        }
                    }
                } else {
                    $role_ids = explode(';', $v['Operator']['role_id'].';');
                    foreach ($role_ids as $key => $vaule) {
                        if (empty($vaule)) {
                            unset($role_ids[$key]);
                        }
                    }
                    if ($v['Operator']['id'] == 13) {
                    }
                    if (in_array($this->data['OperatorRole']['id'], $role_ids)) {
                        if (isset($_REQUEST['operators']) && count($_REQUEST['operators']) > 0) {
                            if (in_array($v['Operator']['id'], $_REQUEST['operators'])) {
                            } else {
                                foreach ($role_ids as $kkk => $vvv) {
                                    if ($vvv == $this->data['OperatorRole']['id']) {
                                        unset($role_ids[$kkk]);
                                    }
                                }
                                $operators[$k]['Operator']['role_id'] = implode(';', $role_ids);
                                $this->Operator->save($operators[$k]);
                            }
                        }
                    } else {
                        if (isset($_REQUEST['operators']) && count($_REQUEST['operators']) > 0) {
                            if (in_array($v['Operator']['id'], $_REQUEST['operators'])) {
                                $operators[$k]['Operator']['role_id'] .= ';'.$this->data['OperatorRole']['id'];
                                $this->Operator->save($operators[$k]);
                            }
                        }
                    }
                }
            }
            foreach ($this->data['OperatorRoleI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_role'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
                }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
                $this->redirect($back_url);
        }
        $this->data = $this->OperatorRole->localeformat($id);
        $this->OperatorAction->set_locale($this->backend_locale);
        $operatoraction = $this->OperatorAction->alltree_hasname();
        $this->set('actions_arr', explode(';', $this->data['OperatorRole']['actions']));
        $this->set('operatorrole', $this->data);
        //应用判断
        $all_infos = $this->apps['codes'];
        foreach ($operatoraction as $k => $v) {
            if ($v['OperatorAction']['app_code'] != '' && !in_array($v['OperatorAction']['app_code'], $all_infos)) {
                unset($operatoraction[$k]);
            }
            if (isset($v['SubAction']) && count($v['SubAction']) > 0) {
                foreach ($v['SubAction'] as $kk => $vv) {
                    if (isset($vv['OperatorAction'])) {
                        if ($vv['OperatorAction']['app_code'] != '' && !in_array($vv['OperatorAction']['app_code'], $all_infos)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                        if ($vv['OperatorAction']['code'] == 'applications_view' && isset($this->configs['use_app']) && $this->configs['use_app'] == 0 && (!isset($_SESSION['use_app']) || $_SESSION['use_app'] != 1)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                        if ($vv['OperatorAction']['code'] == 'languages_view' && (($this->Language->find('count')) <= 0)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                        if ($vv['OperatorAction']['code'] == 'payments_view' && (($this->Payment->find('count', array('conditions' => array('Payment.status' => 1)))) == 0)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                    }
                }
            }
        }
        $this->set('operatoraction', $operatoraction);
    }

    public function add()
    {
        /*判断权限*/
        $this->operator_privilege('operator_roles_add');
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        /*end*/
        $this->set('title_for_layout', $this->ld['role_add_role'].' - '.$this->ld['operator_roles'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operator_roles'],'url' => '/roles/');
        $this->navigations[] = array('name' => $this->ld['role_add_role'],'url' => '');
        $operators = $this->Operator->find('all');//取得操作员列表
        $this->set('operators', $operators);
        if ($this->RequestHandler->isPost()) {
            $this->data['OperatorRole']['orderby'] = !empty($this->data['OperatorRole']['orderby']) ? $this->data['OperatorRole']['orderby'] : 50;
            $this->data['OperatorRole']['store_id'] = !empty($this->data['OperatorRole']['store_id']) ? $this->data['OperatorRole']['store_id'] : 0;
            $this->data['OperatorRole']['actions'] = !empty($this->data['OperatorRole']['actions']) ? $this->data['OperatorRole']['actions'] : 0;
            if (isset($_REQUEST['competence'])) {
                $competence = $_REQUEST['competence'];
                $competence = implode(';', $competence);
                $this->data['OperatorRole']['actions'] = $competence;
            }
            $this->OperatorRole->saveall($this->data['OperatorRole']); //保存
                  $id = $this->OperatorRole->id;
                  //新增角色多语言
                  if (is_array($this->data['OperatorRoleI18n'])) {
                      foreach ($this->data['OperatorRoleI18n'] as $k => $v) {
                          $v['operator_role_id'] = $id;
                          $this->OperatorRoleI18n->id = '';
                          $this->OperatorRoleI18n->saveall(array('OperatorRoleI18n' => $v));
                      }
                  }
            if (isset($_REQUEST['operators']) && count($_REQUEST['operators']) > 0) {
                foreach ($_REQUEST['operators'] as $k => $v) {
                    $operator = $this->Operator->findbyid($v);
                    if (!empty($operator['Operator']['role_id'])) {
                        $operator['Operator']['role_id'] .= ';'.$id;
                    } else {
                        $operator['Operator']['role_id'] = $id;
                    }
                    $this->Operator->save($operator);
                }
            }
            foreach ($this->data['OperatorRoleI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_role'].':'.$userinformation_name, $this->admin['id']);
                }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
                $this->redirect($back_url);
        }
        $this->OperatorAction->set_locale($this->backend_locale);
        $operatoraction = $this->OperatorAction->alltree_hasname();
        foreach ($operatoraction as $k => $v) {
            $operatoraction[$k]['OperatorAction']['name'] = $v['OperatorActionI18n']['name'];
            if (isset($v['SubAction'])) {
                foreach ($v['SubAction'] as $kk => $vv) {
                    $operatoraction[$k]['SubAction'][$kk]['OperatorAction']['name'] = $vv['OperatorActionI18n']['name'];
                }
            }
        }
         //应用判断
        $all_infos = $this->apps['codes'];
        foreach ($operatoraction as $k => $v) {
            if ($v['OperatorAction']['app_code'] != '' && !in_array($v['OperatorAction']['app_code'], $all_infos)) {
                unset($operatoraction[$k]);
            }
            if (isset($v['SubAction']) && count($v['SubAction']) > 0) {
                foreach ($v['SubAction'] as $kk => $vv) {
                    if (isset($vv['OperatorAction'])) {
                        if ($vv['OperatorAction']['app_code'] != '' && !in_array($vv['OperatorAction']['app_code'], $all_infos)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                        if ($vv['OperatorAction']['code'] == 'applications_view' && isset($this->configs['use_app']) && $this->configs['use_app'] == 0 && (!isset($_SESSION['use_app']) || $_SESSION['use_app'] != 1)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                        if ($vv['OperatorAction']['code'] == 'languages_view' && (($this->Language->find('count')) <= 0)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                        if ($vv['OperatorAction']['code'] == 'payments_view' && (($this->Payment->find('count', array('conditions' => array('Payment.status' => 1)))) == 0)) {
                            unset($operatoraction[$k]['SubAction'][$kk]);
                        }
                    }
                }
            }
        }
        $this->set('operatoraction', $operatoraction);
    }

    public function remove($id)
    {
        /*判断权限*/
        $this->operator_privilege('operator_roles_remove');
        /*end*/
        $pn = $this->OperatorRoleI18n->find('list', array('fields' => array('OperatorRoleI18n.operator_role_id', 'OperatorRoleI18n.name'), 'conditions' => array('OperatorRoleI18n.operator_role_id' => $id, 'OperatorRoleI18n.locale' => $this->locale)));
        $this->OperatorRole->deleteAll(array('OperatorRole.id' => $id));
        $this->OperatorRoleI18n->deleteAll(array('OperatorRole.operator_role_id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_role'].':id '.' '.$pn[$id], $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }

    public function batch_operations()
    {
        $user_checkboxes = $_REQUEST['checkboxes'];
        foreach ($user_checkboxes as $k => $v) {
            $this->OperatorRole->deleteAll(array('OperatorRole.id' => $v));
            $this->OperatorRoleI18n->deleteAll(array('OperatorRoleI18n.operator_role_id' => $v));
        }
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_operator_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function getOperatorActionByRole()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $result['code'] = 0;
        $result['msg'] = 'No Data!';
        if ($this->RequestHandler->isPost()) {
            $operator_role_ids_str = $_POST['operator_role_ids'];
            $operator_role_ids = explode(';', $operator_role_ids_str);
            $this->OperatorRole->set_locale($this->backend_locale);
            $opera_data = $this->OperatorRole->find('list', array('conditions' => array('OperatorRole.id' => $operator_role_ids), 'fields' => array('OperatorRole.id', 'OperatorRole.actions')));
            $operator_action_ids_str = '';
            $operator_action_ids = array();
            foreach ($opera_data as $k => $v) {
                if (!empty($v) && $v != '') {
                    $operator_action_id = explode(';', $v);
                    foreach ($operator_action_id as $vv) {
                        $operator_action_ids[$vv] = $vv;
                    }
                }
            }
            if (!empty($operator_action_ids)) {
                $operator_action_ids_str = implode(';', $operator_action_ids);
            }
            $result['code'] = $operator_action_ids_str == '' ? 1 : 2;
            $result['msg'] = $operator_action_ids_str;
        }
        die(json_encode($result));
    }
    
    
      //角色管理上传
public function role_upload(){
	  Configure::write('debug', 0);
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/system/','sub' => '/operators/');
        /*end*/

        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operator_roles'],'url' => '/roles/');
        $this->navigations[] = array('name' => $this->ld['bulk_upload'],'url' => '');
      $this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->ld['operator_roles'].' - '.$this->ld['manage_system'].' - '.$this->configs['shop_name']);
   $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_role_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//角色管理cvs查看
 public function role_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/roles/role_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_role_export', 'Profile.status' => 1)));
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
                                      echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert(' 标题列数与内容列数不一致');window.location.href='/admin/roles/role_upload';</script>";
						die();
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/roles/role_upload';</script>";
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
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        $code_array=array();
                        $code_array=explode(';',$v['OperatorRole']['actions']);
                        foreach($code_array as $code_key=>$code_val){
                        		$OperatorAction_id_array[]=$this->OperatorAction->find('first',array('fields'=>array('OperatorAction.id'),'conditions'=>array('OperatorAction.code'=>$code_val)));
                        }
                        $OperatorAction_id_str='';
                        foreach($OperatorAction_id_array as $id_val){
                        	if(isset($id_val['OperatorAction']['id'])){
                        	$OperatorAction_id_str.=$id_val['OperatorAction']['id'].";";
                        	}
                        }
                        $OperatorRole_first = $this->OperatorRole->find('first', array('conditions' => array('OperatorRole.actions' =>$OperatorAction_id_str)));
                        $v['OperatorRole']['id']=isset($OperatorRole_first['OperatorRole']['id'])?$OperatorRole_first['OperatorRole']['id']:0;
                        $v['OperatorRole']['orderby']=isset($v['OperatorRole']['orderby'])?$v['OperatorRole']['orderby']:50;
                        $v['OperatorRole']['actions']=$OperatorAction_id_str;
                        $v['OperatorRole']['store_id']=0;
                        	if( $s1=$this->OperatorRole->save($v['OperatorRole']) ){
                        		$OperatorRole_id=$this->OperatorRole->id;
                        	}
                        	$OperatorRoleI18n_first = $this->OperatorRoleI18n->find('first', array('conditions' => array('OperatorRoleI18n.operator_role_id' =>$OperatorRole_id, 'OperatorRoleI18n.locale' => $v['OperatorRoleI18n']['locale'])));
                        $v['OperatorRoleI18n']['id']=isset($OperatorRoleI18n_first['OperatorRoleI18n']['id'])?$OperatorRoleI18n_first['OperatorRoleI18n']['id']:0;
                        $v['OperatorRoleI18n']['operator_role_id']=isset($OperatorRole_id)?$OperatorRole_id:'';
                        	$s2=$this->OperatorRoleI18n->save($v['OperatorRoleI18n']);
                        	 if( isset($s1)&&!empty($s1)&&isset($s2)&&!empty($s2)){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                    }
                   
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/roles/role_upload/'</script>";
		            die();
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/roles/role_upload/'</script>";
                    	
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



		 
//角色管理csv
public function download_role_csv_example($out_type = 'OperatorRole'){
 Configure::write('debug', 0);
     $this->layout="ajax";
     $this->OperatorRole->set_locale($this->backend_locale);
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_role_export', 'Profile.status' => 1)));
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
          $OperatorRole_info = $this->OperatorRole->find('all', array('fields'=>array('OperatorRole.actions','OperatorRole.status','OperatorRole.orderby','OperatorRoleI18n.locale','OperatorRoleI18n.name'),'order' => 'OperatorRole.id desc','limit'=>10));
		//pr($OperatorRole_info);die();
         
            
              //循环数组
              foreach($OperatorRole_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                  if($fields_ks[1]=='actions'){
	                  	 
		                  	$action_array=explode(';',$v['OperatorRole']['actions']);
		                  	foreach($action_array as $ac_val){
		                  		$OperatorAction_code_array[]=$this->OperatorAction->find('first',array('fields'=>array('OperatorAction.code'),'conditions'=>array('OperatorAction.id'=>$ac_val)));
		                  	}
		                  	$code_str='';
		                  	foreach($OperatorAction_code_array as $code_key=>$code_val){
		                  		$code_str.=$code_val['OperatorAction']['code'].";";
		                  	}
		                  	$user_tmp[] =$code_str;
	                  }else{
	                 	 	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
	                  }
	                  
	                 // pr($OperatorAction_code_array);die();
	                 // pr($action_array);die();
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
public function all_export_csv($out_type = 'OperatorRole'){
 Configure::write('debug', 0);
     $this->layout="ajax";
          $this->OperatorRole->set_locale($this->backend_locale);

     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_role_export', 'Profile.status' => 1)));
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
          $OperatorRole_info = $this->OperatorRole->find('all', array('fields'=>array('OperatorRole.actions','OperatorRole.status','OperatorRole.orderby','OperatorRoleI18n.locale','OperatorRoleI18n.name'),'order' => 'OperatorRole.id desc'));
		//pr($OperatorRole_info);die();
         
            
              //循环数组
              foreach($OperatorRole_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                  if($fields_ks[1]=='actions'){
	                  	 
		                  	$action_array=explode(';',$v['OperatorRole']['actions']);
		                  	foreach($action_array as $ac_val){
		                  		$OperatorAction_code_array[]=$this->OperatorAction->find('first',array('fields'=>array('OperatorAction.code'),'conditions'=>array('OperatorAction.id'=>$ac_val)));
		                  	}
		                  	$code_str='';
		                  	foreach($OperatorAction_code_array as $code_key=>$code_val){
		                  		$code_str.=$code_val['OperatorAction']['code'].";";
		                  	}
		                  	$user_tmp[] =$code_str;
	                  }else{
	                 	 	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
	                  }
	                  
	                 // pr($OperatorAction_code_array);die();
	                 // pr($action_array);die();
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
public function choice_export($out_type = 'OperatorRole'){
 Configure::write('debug', 0);
     $this->layout="ajax";
          $this->OperatorRole->set_locale($this->backend_locale);

     $user_checkboxes = $_REQUEST['checkboxes'];

     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'operator_role_export', 'Profile.status' => 1)));
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
          $OperatorRole_info = $this->OperatorRole->find('all', array('fields'=>array('OperatorRole.actions','OperatorRole.status','OperatorRole.orderby','OperatorRoleI18n.locale','OperatorRoleI18n.name'),'order' => 'OperatorRole.id desc','conditions'=>array('OperatorRole.id'=>$user_checkboxes)));
	//	pr($OperatorRole_info);die();
         
            
              //循环数组
              foreach($OperatorRole_info as $k=>$v){
              	  $user_tmp = array();
	              foreach ($fields_array as $ks => $vs) {
	                    //分解字符串为数组
	                  $fields_ks = explode('.', $vs);
	                  if($fields_ks[1]=='actions'){
	                  	 
		                  	$action_array=explode(';',$v['OperatorRole']['actions']);
		                  	foreach($action_array as $ac_val){
		                  		$OperatorAction_code_array[]=$this->OperatorAction->find('first',array('fields'=>array('OperatorAction.code'),'conditions'=>array('OperatorAction.id'=>$ac_val)));
		                  	}
		                  	$code_str='';
		                  	foreach($OperatorAction_code_array as $code_key=>$code_val){
		                  		$code_str.=$code_val['OperatorAction']['code'].";";
		                  	}
		                  	$user_tmp[] =$code_str;
	                  }else{
	                 	 	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
	                  }
	                  
	                 // pr($OperatorAction_code_array);die();
	                 // pr($action_array);die();
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
