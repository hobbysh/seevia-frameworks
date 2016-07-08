<?php

/*****************************************************************************
 * Seevia Api前台控制器
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 ApidocsController 的Api对接控制器.
 */
 class ApidocsController extends AppController
 {
 	public $name = 'Apidocs';
 	public $components = array('RequestHandler','Cookie','Session','Captcha','Email','Pagination');
	public $helpers = array('Html','Flash','Cache','Pagination');
	public $uses = array('Operator','Config','Application','ApiMethod','Route','ApiProject','ApiCategory','ApiObject','ApiObjectField','ApiMethodRequest','ApiMethodResponse','ApiMethodResponseExample','ApiMethodRequestExample','ApiMethodResponseException','ApiMethodErrorCode','ApiErrorCodeInterpretation','ApiMethodFaq','ApiProjectCommonParameter');

	//项目列表页面
	public function index(){
		$project_list = $this->ApiProject->find('list', array('fields'=>array('ApiProject.code','ApiProject.name'),'order'=>'ApiProject.created  ASC'));
		$this->set('project_list', $project_list);
	}
	
	//类目列表页面	
 	public function api(){
 		//当前项目的详细信息
		$project_info=$this->ApiProject->find('first',array('conditions'=>array('ApiProject.code'=>$this->params['url']['project_code'])));
 		$this->set('project_info', $project_info);
 		
 		//类目列表
		$category_list=$this->ApiCategory->find('list',array('fields'=>array('ApiCategory.code','ApiCategory.name'),'conditions'=>array('ApiCategory.api_project_code'=>$this->params['url']['project_code']),'order'=>'ApiCategory.orderby ASC'));
		$this->set('category_list',$category_list);
		
		//判断url是否存在category_code
		 if( isset( $this->params['url']['project_code']) && isset( $this->params['url']['category_code']) ){
		 	 
		//取当前项目下，第一个category_code值
		$category_info=$this->ApiCategory->find('first',array('conditions'=>array('ApiCategory.api_project_code'=>$this->params['url']['project_code']),'order'=>'ApiCategory.orderby ASC'));
		$this->set('category_info',$category_info);
		
		//继续判断category_code是否为空
		$category_code=($this->params['url']['category_code']=='')?$category_info['ApiCategory']['code']:$this->params['url']['category_code'];
		
		//根据条件查询，当前类目下的方法列表
	   	$method_condition = '';
 	 	$method_condition['AND']['ApiMethod.api_category_code']= $category_code;
 	 	$method_condition['AND']['ApiMethod.api_project_code']= $this->params['url']['project_code'];
		$method_infos= $this->ApiMethod->find('all', array('conditions' => $method_condition,'order' => 'ApiMethod.orderby ASC'));
		$this->set('method_infos', $method_infos);
		
		//根据条件查询，当前类目的描述
		$category_condition = '';
         	$category_condition['AND']['ApiCategory.code']= $category_code;
         	$category_condition['AND']['ApiCategory.api_project_code']= $this->params['url']['project_code'];
		$category_info= $this->ApiCategory->find('first', array('conditions' => $category_condition));
		$this->set('category_info',$category_info );
      
		}

 	}
 	
 	//方法详细列表
 	public function detail(){
 		//当前项目的详细信息
		$project_info=$this->ApiProject->find('first',array('conditions'=>array('ApiProject.code'=>$this->params['url']['project_code'])));
 		$this->set('project_info', $project_info);
	      
	      //类目列表	
 		$category_list=$this->ApiCategory->find('list',array('fields'=>array('ApiCategory.code','ApiCategory.name'),'order'=>'ApiCategory.code ASC'));
		$this->set('category_list',$category_list);
		
		//当前项目、当前类目下，方法列表
 		$category_condition='';
 		$category_condition['AND']['ApiMethod.api_project_code']=$this->params['url']['project_code'];
 		$category_condition['AND']['ApiMethod.api_category_code']=$this->params['url']['category_code'];
		$method_infos= $this->ApiMethod->find('all', array('conditions' => $category_condition,'order' => 'ApiMethod.api_category_code ASC'));
	      $this->set('method_infos', $method_infos);
		
 		 //根据当前方法Id，获得当前方法的详细信息  
		$method_info=$this->ApiMethod->find('first',array('conditions'=>array('ApiMethod.id'=>$this->params['url']['method_id'])));
 		$this->set('method_info', $method_info);
 		
 		//当前项目下公共参数详细信息
 		$project_commonparameter_condition='';
 		$project_commonparameter_condition['AND']['ApiProjectCommonParameter.api_project_code']=$this->params['url']['project_code'];
 		$project_commonparameter_infos=$this->ApiProjectCommonParameter->find('all',array('conditions'=>$project_commonparameter_condition,'order'=>'ApiProjectCommonParameter.api_project_code ASC'));
 		$this->set('project_commonparameter_infos', $project_commonparameter_infos);

 		//请求参数
 		$method_request_condition='';
 		$method_request_condition['AND']['ApiMethodRequest.api_project_code']=$this->params['url']['project_code'];
 		$method_request_condition['AND']['ApiMethodRequest.api_category_code']=$this->params['url']['category_code'];
 		$method_request_condition['AND']['ApiMethodRequest.api_method_code']=$method_info['ApiMethod']['code'];
 		$method_request_infos=$this->ApiMethodRequest->find('all',array('conditions'=>$method_request_condition,'order'=>'ApiMethodRequest.orderby ASC'));
 		$this->set('method_request_infos', $method_request_infos);
 		
 		//响应参数
 		$method_response_condition='';
 		$method_response_condition['AND']['ApiMethodResponse.api_project_code']=$this->params['url']['project_code'];
 		$method_response_condition['AND']['ApiMethodResponse.api_category_code']=$this->params['url']['category_code'];
 		$method_response_condition['AND']['ApiMethodResponse.api_method_code']=$method_info['ApiMethod']['code'];
 		$method_response_infos=$this->ApiMethodResponse->find('all',array('conditions'=>$method_response_condition,'order'=>'ApiMethodResponse.customer_case_id ASC'));
 		$this->set('method_response_infos', $method_response_infos);
 		//对象字段
 		$object_field_condition='';
           	$object_field_condition['AND']['ApiObjectField.api_project_code']  = $this->params['url']['project_code'];
           //	$object_field_condition['AND']['ApiObjectField.api_object_code']  = $object_info['ApiObject']['code'];
	  	$object_field_infos=$this->ApiObjectField->find('all',array('conditions'=>$object_field_condition));
         	$this->set('object_field_infos',$object_field_infos);
 		//响应参数中类型包含对象的折叠面板
 		//重组新数组，对象作为键值，把该对象下的字段
 		$object_field_info=array();
 		foreach($method_response_infos as $method_response_val){
 			if($method_response_val['ApiMethodResponse']['type']==$object_field_val['ApiObjectField']['api_object_code']){
 			$object_field_info[$method_response_val['ApiMethodResponse']['type']][]=$object_field_val;
 		}
 		}
 		$this->set('object_field_info',$object_field_info);
 		//请求示例
 		$method_request_example_condition='';
 		$method_request_example_condition['AND']['ApiMethodRequestExample.api_project_code']=$this->params['url']['project_code'];
 		$method_request_example_condition['AND']['ApiMethodRequestExample.api_category_code']=$this->params['url']['category_code'];
 		$method_request_example_condition['AND']['ApiMethodRequestExample.api_method_code']=$method_info['ApiMethod']['code'];
 		$method_request_example_infos=$this->ApiMethodRequestExample->find('all',array('conditions'=>$method_request_example_condition,'order'=>'ApiMethodRequestExample.api_method_code ASC'));
 		$this->set('method_request_example_infos', $method_request_example_infos);
 		
 		//响应示例
 		$method_response_example_condition='';
 		$method_response_example_condition['AND']['ApiMethodResponseExample.api_project_code']=$this->params['url']['project_code'];
 		$method_response_example_condition['AND']['ApiMethodResponseExample.api_category_code']=$this->params['url']['category_code'];
 		$method_response_example_condition['AND']['ApiMethodResponseExample.api_method_code']=$method_info['ApiMethod']['code'];
 		$method_response_example_infos=$this->ApiMethodResponseExample->find('all',array('conditions'=>$method_response_example_condition,'order'=>'ApiMethodResponseExample.api_method_code ASC'));
 		$this->set('method_response_example_infos', $method_response_example_infos);
 		
 		//异常示例
 		$method_response_exception_condition='';
 		$method_response_exception_condition['AND']['ApiMethodResponseException.api_project_code']=$this->params['url']['project_code'];
 		$method_response_exception_condition['AND']['ApiMethodResponseException.api_category_code']=$this->params['url']['category_code'];
 		$method_response_exception_condition['AND']['ApiMethodResponseException.api_method_code']=$method_info['ApiMethod']['code'];
 		$method_response_exception_infos=$this->ApiMethodResponseException->find('all',array('conditions'=>$method_response_exception_condition,'order'=>'ApiMethodResponseException.api_method_code ASC'));
 		$this->set('method_response_exception_infos', $method_response_exception_infos);
 		
 		//错误代码
 		$method_error_code_condition='';
 		$method_error_code_condition['AND']['ApiMethodErrorCode.api_project_code']=$this->params['url']['project_code'];
 		$method_error_code_condition['AND']['ApiMethodErrorCode.api_method_code']=$method_info['ApiMethod']['code'];
 		$method_error_code_infos=$this->ApiMethodErrorCode->find('all',array('conditions'=>$method_error_code_condition,'order'=>'ApiMethodErrorCode.api_method_code ASC'));
 		$this->set('method_error_code_infos', $method_error_code_infos);
 		
 		//错误代码解释
 		$error_code_interpretation_condition='';
 		$error_code_interpretation_condition['AND']['ApiErrorCodeInterpretation.api_project_code']=$this->params['url']['project_code'];
		$error_code_interpretation_infos=$this->ApiErrorCodeInterpretation->find('all',array('conditions'=>$error_code_interpretation_condition,'order'=>'ApiErrorCodeInterpretation.api_project_code ASC'));
 		$this->set('error_code_interpretation_infos', $error_code_interpretation_infos);
 		
 		//FAQ
 		$method_faq_condition='';
 		$method_faq_condition['AND']['ApiMethodFaq.api_project_code']=$this->params['url']['project_code'];
 		$method_faq_condition['AND']['ApiMethodFaq.api_category_code']=$this->params['url']['category_code'];
 		$method_faq_condition['AND']['ApiMethodFaq.api_method_code']=$method_info['ApiMethod']['code'];
 		$method_faq_infos=$this->ApiMethodFaq->find('all',array('conditions'=>$method_faq_condition,'order'=>'ApiMethodFaq.api_method_code ASC'));
 		$this->set('method_faq_infos', $method_faq_infos);

	}

 }
 
 ?>