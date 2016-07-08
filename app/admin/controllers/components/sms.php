<?php
class SmsComponent{
	public $to_mobile = "";//手机号
	public $sms_content = "";//短信内容
	public $sms_params=array();//短信发送参数
	
	/**
	 *	短信发送
	 *
	 *	@var $to_mobile	手机号
	 *	@var $sms_content	短信内容
	 *	@var $sms_kanal	短信发送渠道
	 *	@var $sms_params	短信发送参数
	 *	@var $sending_limits	发送次数限制(3分钟)
	 */
	public function send_sms($to_mobile="",$sms_content="",$sms_kanal='0',$sms_params=array(),$sending_limits=true){
		$result=array();
		$result['code']='0';
		$result['message']='Send Error';
		$to_mobile=trim($to_mobile);
		$sms_content=trim($sms_content);
		if($to_mobile==""){
			$result['message']='手机号不能为空';
			return $result;
 		}else if($to_mobile!=""&&preg_match("/^1[34578]{1}\d{10}$/",$to_mobile)){ 
			$result['message']='手机号格式错误';
			return $result;
		}else if($sms_content==""){
			$result['message']='短信内容不能为空';
			return $result;
		}
		$this_model = new Model(false, 'sms_send_histories');
		if($sending_limits==true){
			$SmsSendQueue_info=$this_model->find('first',array('conditions'=>array('phone'=>$to_mobile,'created >='=>date("Y-m-d H:i:s",strtotime("-3 min")),'created <='=>date("Y-m-d H:i:s"))));
			if(!empty($SmsSendQueue_info)){
				$result['message']='不要重复发送';
				return $result;
			}
		}
		$this->to_mobile=$to_mobile;
		$this->sms_content=$sms_content;
		$this->sms_params=$sms_params;
		
		$mailsendqueue=array(
			'id'=>0,
			'phone'=>$to_mobile,
			'content'=>$sms_content
		);
		switch($sms_kanal){
			case "0":
				$result=$this->juchn();
				break;
			case "1":
				$result=$this->lingkai();
				break;
			case "2":
				$result=$this->yuntongxun();
				break;
			default:
				$result['message']='短信渠道不可用';
				break;
		}
		$mailsendqueue['send_date']=date("Y-m-d H:i:s");
		$mailsendqueue['flag']=$result['code'];
		$this_model->save($mailsendqueue);
		return $result;
	}
	
	/**
	 *	巨辰短信发送
	 */
	public function juchn(){
		$to_mobile=$this->to_mobile;
		$sms_content=$this->sms_content;
		$sms_params=$this->sms_params;
		
		$result=array();
		$result['code']='0';
		$result['message']='Send Error';
		$error_message=array(
			"0"=>"提交成功",
			"101"=>"无此用户",
			"102"=>"密码错",
			"103"=>"提交过快（提交速度超过流速限制）",
			"104"=>"系统忙（因平台侧原因，暂时无法处理提交的短信）",
			"105"=>"敏感短信（短信内容包含敏感词）",
			"106"=>"消息长度错（>536或<=0）",
			"107"=>"包含错误的手机号码",
			"108"=>"手机号码个数错（群发>50000或<=0;单发>200或<=0）",
			"109"=>"无发送额度（该用户可用短信数已使用完）",
			"110"=>"不在发送时间内",
			"111"=>"超出该账户当月发送额度限制",
			"112"=>"无此产品，用户没有订购该产品",
			"113"=>"extno格式错（非数字或者长度不对）",
			"115"=>"自动审核驳回",
			"116"=>"签名不合法，未带签名（用户必须带签名的前提下）",
			"117"=>"IP地址认证错,请求调用的IP地址不是系统登记的IP地址",
			"118"=>"用户没有相应的发送权限",
			"119"=>"用户已过期"
		);
		$signature = isset($sms_params['sms-signature'])?$sms_params['sms-signature']:"实玮网络";//短信签名
		$sms_content=$sms_content."【".$signature."】";
		$account=isset($sms_params['sms_parameter1'])?trim($sms_params['sms_parameter1']):"";//账号
		$pswd=isset($sms_params['sms_parameter2'])?trim($sms_params['sms_parameter2']):"";//密码
		$request_data_format="http://120.24.167.205/msg/HttpSendSM?account=%s&pswd=%s&mobile=%s&msg=%s&needstatus=true";
		$request_url=sprintf($request_data_format,$account,$pswd,$to_mobile,$sms_content);
		if(function_exists('file_get_contents')){ 
			$file_contents = file_get_contents($request_url); 
		}else{
			$ch = curl_init();
			$timeout = 5;
			curl_setopt ($ch, CURLOPT_URL, $request_url); 
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 
			$file_contents = curl_exec($ch);
			curl_close($ch);
		}
		$sms_result_arr=split("\n",$file_contents);
		$sms_result_txt=isset($sms_result_arr[0])?$sms_result_arr[0]:'';
		$sms_result=split(",",$sms_result_txt);
		$sms_send_code=isset($sms_result[1])?$sms_result[1]:"-1";
		if($sms_send_code=="0"){
			$result['code']='1';
			$result['message']='发送成功';
		}else{
			$result['message']=isset($error_message[$sms_send_code])?$error_message[$sms_send_code]:'发送失败';
		}
		return $result;
	}
	
	/**
	 *	凌凯短信发送
	 */
	public function lingkai(){
		$to_mobile=$this->to_mobile;
		$sms_content=$this->sms_content;
		$sms_params=$this->sms_params;
		
		$result=array();
		$result['code']='0';
		$result['message']='Send Error';
		$error_message=array(
			"-1"=>"账号未注册",
			"-2"=>"其他错误",
			"-3"=>"帐号或密码错误",
			"-5"=>"余额不足，请充值",
			"-6"=>"定时发送时间不是有效的时间格式",
			"-7"=>"提交信息末尾未签名，请添加中文的企业签名【 】",
			"-8"=>"发送内容需在1到300字之间",
			"-9"=>"发送号码为空",
			"-10"=>"定时时间不能小于系统当前时间",
			"-100"=>"IP黑名单",
			"-102"=>"账号黑名单",
			"-103"=>"IP未导白"
		);
		$signature = isset($sms_params['sms-signature'])?$sms_params['sms-signature']:"实玮网络";//短信签名
		$sms_content=$sms_content."【".$signature."】";
		$CorpID=isset($sms_params['sms_parameter1'])?trim($sms_params['sms_parameter1']):"";//账号
		$Pwd=isset($sms_params['sms_parameter2'])?trim($sms_params['sms_parameter2']):"";//密码
		try{
			$client = new SoapClient("http://mb345.com:999/ws/LinkWS.asmx?wsdl",array('encoding'=>'UTF-8'));
			$sendParam = array(
				'CorpID'=>$CorpID,
				'Pwd'=>$Pwd,
				'Mobile'=>$to_mobile,
				'Content'=>$sms_content,
				'Cell'=>'',
				'SendTime'=>''
			);
			$sms_result = $client->__call('BatchSend2',array("BatchSend2"=>$sendParam));
			$sms_send_code=isset($sms_result->BatchSend2Result)?intval($sms_result->BatchSend2Result):0;
			if($sms_send_code>0){
				$result['code']='1';
				$result['message']='发送成功';
			}else{
				$result['message']=isset($error_message[$sms_send_code])?$error_message[$sms_send_code]:'发送失败';
			}
		}catch(Exception $e){
			$result['message']='发送失败';
			$this->log("Sms lingkai send:".$e->getMessage());
		}
		return $result;
	}
	
	public function yuntongxun(){
		$to_mobile=$this->to_mobile;
		$sms_content=$this->sms_content;
		$sms_params=$this->sms_params;
		App::import('Vendor', 'Sms', array('file' => 'CCPRestSmsSDK.php'));
		$result=array();
		$result['code']='0';
		$result['message']='Send Error';
		if(!class_exists("REST")){
			$result['message']='Yuntongxun SDK not found';
			return $result;
		}
		$error_message=array(
			"000000"=>"发送成功",
			"111141"=>"主账户不存在",
			"111109"=>"请求地址Sig校验失败",
			"111181"=>"应用不存在",
			"112300"=>"接收短信的手机号码为空",
			"112301"=>"短信正文为空",
			"112302"=>"群发短信已暂停",
			"112303"=>"应用未开通短信功能",
			"112304"=>"短信内容的编码转换有误",
			"112305"=>"应用未上线，短信接收号码外呼受限",
			"112306"=>"接收模板短信的手机号码为空",
			"112307"=>"模板短信模板ID为空",
			"112308"=>"模板短信模板data参数为空",
			"112309"=>"模板短信内容的编码转换有误",
			"112310"=>"应用未上线，模板短信接收号码外呼受限",
			"112311"=>"短信模板不存在",
			"160000"=>"系统错误",
			"160031"=>"参数解析失败",
			"160032"=>"短信模板无效",
			"160033"=>"短信存在黑词",
			"160034"=>"号码黑名单",
			"160035"=>"短信下发内容为空",
			"160036"=>"短信模板类型未知",
			"160037"=>"短信内容长度限制",
			"160038"=>"短信验证码发送过频繁",
			"160039"=>"超出同模板同号天发送次数上限",
			"160040"=>"验证码超出同模板同号码天发送上限",
			"160041"=>"通知超出同模板同号码天发送上限",
			"160042"=>"号码格式有误",
			"160043"=>"应用与模板id不匹配",
			"160050"=>"短信发送失败",
			"172006"=>"主帐号为空",
			"172007"=>"主帐号令牌为空",
			"172012"=>"应用ID为空"
		);
		
		//主帐号,对应开官网发者主账号下的 ACCOUNT SID
		$accountSid= isset($sms_params['sms_parameter1'])?trim($sms_params['sms_parameter1']):"";
		//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
		$accountToken= isset($sms_params['sms_parameter2'])?trim($sms_params['sms_parameter2']):"";
		//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
		//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
		$appId=isset($sms_params['sms_parameter3'])?trim($sms_params['sms_parameter3']):"";
		//请求地址
		//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
		//生产环境（用户应用上线使用）：app.cloopen.com
		$serverIP='app.cloopen.com';
		//请求端口，生产环境和沙盒环境一致
		$serverPort='8883';
		//REST版本号，在官网文档REST介绍中获得。
		$softVersion='2013-12-26';
		$rest = new REST($serverIP,$serverPort,$softVersion);
		$rest->setAccount($accountSid,$accountToken);
		$rest->setAppId($appId);
		$templateId=isset($sms_params['templateId'])?trim($sms_params['templateId']):"1";
		$other_request=isset($sms_params['other_request'])?trim($sms_params['templateId']):array($to_mobile,'3');
		$sms_result = $rest->sendTemplateSMS($to_mobile,$other_request,$templateId);
		$sms_send_code=isset($sms_result->statusCode)?$sms_result->statusCode:'-1';
		if($sms_send_code=='0'){
			$result['code']='1';
			$result['message']='发送成功';
		}else{
			$result['message']=isset($error_message[$sms_send_code])?$error_message[$sms_send_code]:'发送失败';
		}
		return $result;
	}
}
?>