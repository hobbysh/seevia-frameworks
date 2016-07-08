<?php

/*****************************************************************************
 * Seevia Api对接控制器
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 ApisController 的Api对接控制器.
 */
class ApisController extends AppController
{
    /*
        *@var $name
    */
    public $name = 'Apis';
    public $components = array('RequestHandler');
    public $uses = array();
    
    public $login = '';
    public $password = '';
    public $token = '';
    
    public function beforeFilter(){
    		parent::beforeFilter();//执行APP控制器beforeFilter方法
    		if(file_exists(WWW_ROOT."data/api/config.php")){
			include_once(WWW_ROOT."data/api/config.php");//引入配置文件
		}else{
			die(json_encode(array('flag'=>'0','message'=>'API配置文件不存在')));
		}
    		$this->login=defined('api_login')?api_login:'';
    		$this->password=defined('api_password')?api_password:'';
    		$this->token=defined('api_token')?api_token:'';
    		
    		if($this->login==""||$this->password==""||$this->token==""){
    			die(json_encode(array('flag'=>'0','message'=>'API应用配置错误')));
    		}
    		if(isset($_REQUEST['token'])&&trim($_REQUEST['token'])==md5($this->token)){
    			
    		}else if(isset($_REQUEST['token'])&&trim($_REQUEST['token'])!=md5($this->token)){
    			Configure::write('debug', 0);
			$this->layout="ajax";
			die(json_encode(array('code'=>'-1','message'=>'验证码不正确')));
    		}else if(!isset($_REQUEST['token'])&&!isset($_REQUEST['login_name'])&&!isset($_REQUEST['login_password'])){
    			Configure::write('debug', 0);
			$this->layout="ajax";
			die(json_encode(array('code'=>'-1','message'=>'验证码不能为空')));
    		}
    }
    
    function UserLoad(){
    		Configure::write('debug', 1);
		$this->layout="ajax";
		$result=array();
		$result['code']='0';
		$result['message']='未知原因';
    		if ($this->RequestHandler->isPost()){
    			$login_name=isset($_POST['login_name'])?$_POST['login_name']:'';
    			$login_password=isset($_POST['login_password'])?$_POST['login_password']:'';
    			if(($login_name===$this->login)&&md5($login_password)===md5($this->password)){
    				$result['code']='1';
    				$result['message']=md5($this->token);
    			}else{
    				$result['message']='用户名或者密码错误';
    			}
    		}
    		die(json_encode($result));
    }
    
    function import_vendor_product(){
    		Configure::write('debug', 1);
		$this->layout="ajax";
		$result=array();
		$result['code']='0';
		$result['message']='未知原因';
    		if ($this->RequestHandler->isPost()){
    			$this->loadModel('Product');
    			$this->loadModel('ProductI18n');
    			$this->loadModel('Brand');
    			$this->loadModel('CategoryProduct');
    			$this->loadModel('ProductAttribute');
    			$this->loadModel('Attribute');
    			$this->loadModel('ProductType');
    			$this->loadModel('ProductGallery');
    			
    			$postdata=isset($_POST)?$_POST:array();
    			$postfile=isset($_FILES)?$_FILES:array();
    			$ProductAttribute_info=isset($postdata['ProductAttribute'])&&trim($postdata['ProductAttribute'])!=""?json_decode($postdata['ProductAttribute']):array();
    			$postdata['ProductAttribute']=$ProductAttribute_info;
    			$product_code=isset($postdata['code'])?trim($postdata['code']):'';
    			$product_name=isset($postdata['product_name'])?trim($postdata['product_name']):'';
    			if($product_code!=""&&$product_name!=""){
    				$product_info=$this->Product->find('first',array('conditions'=>array("Product.code"=>$product_code)));
    				if(empty($product_info)){
    					$result['data']=array_merge($postdata,$postfile);
    					
    					$error_msg="";
    					$brand_code=isset($postdata['brand_code'])?trim($postdata['brand_code']):'';
    					$category_code=isset($postdata['category_code'])?trim($postdata['category_code']):'';
    					$product_type_code=isset($postdata['product_type_code'])?trim($postdata['product_type_code']):'';
					$brand_id=0;
					$category_id=0;
					$product_type_id=0;
					if($brand_code!=""){
						$brand_data=$this->Brand->find('first',array('fields'=>array("Brand.id","Brand.code","BrandI18n.name"),'conditions'=>array("Brand.code"=>$brand_code,'Brand.status'=>'1')));
						$brand_id=isset($brand_data['Brand'])?$brand_data['Brand']['id']:0;
					}
					if($category_code!=""){
						$category_data=$this->CategoryProduct->find('first',array('fields'=>array("CategoryProduct.id","CategoryProduct.code","CategoryProductI18n.name"),'conditions'=>array("CategoryProduct.code"=>$category_code,'CategoryProduct.status'=>'1')));
						$category_id=isset($category_data['CategoryProduct'])?$category_data['CategoryProduct']['id']:0;
					}
					if($product_type_code!=""){
						$product_type_data=$this->ProductType->find('first',array('fields'=>array("ProductType.id","ProductType.code","ProductTypeI18n.name"),'conditions'=>array("ProductType.code"=>$product_type_code,'ProductType.status'=>'1')));
						$product_type_id=isset($product_type_data['ProductType'])?$product_type_data['ProductType']['id']:0;
					}
					
    					$product_data=array();
    					$product_data['Product']['id']=0;
    					$product_data['Product']['option_type_id']=0;//默认单品
    					$product_data['Product']['code']=$product_code;
    					$product_data['Product']['brand_id']=$brand_id;
    					$product_data['Product']['category_id']=$category_id;
    					$product_data['Product']['product_type_id']=$product_type_id;
    					$product_data['Product']['shop_price']=isset($postdata['retail_price'])?trim($postdata['retail_price']):'0';
    					$product_data['Product']['purchase_price']=isset($postdata['domestically_product_cost'])?trim($postdata['domestically_product_cost']):'0';
    					
    					if(isset($postfile['img_original'])&&!empty($postfile['img_original'])){
						$iamge_data=$this->product_image_process($postfile['img_original'],$product_code,$category_id);
						if($iamge_data['code']=='1'){
							$product_data['Product']['product_image2']=$iamge_data['data']['img_original'];
						}else{
							$error_msg .= $iamge_data['data'];
						}
					}
					
    					if(isset($postfile['product_image'])&&!empty($postfile['product_image'])){
						$iamge_data=$this->product_image_process($postfile['product_image'],$product_code,$category_id);
						if($iamge_data['code']=='1'){
							$product_data['Product']['product_image1']=$iamge_data['data']['img_original'];
						}else{
							$error_msg .= $iamge_data['data'];
						}
					}
					$ProductGallery_data=array();
					if(isset($postfile['product_image2'])&&!empty($postfile['product_image2'])){
						$iamge_data=$this->product_image_process($postfile['product_image2'],$product_code,$category_id);
						if($iamge_data['code']=='1'){
							$product_data['Product']['img_thumb']=$iamge_data['data']['img_thumb'];
							$product_data['Product']['img_detail']=$iamge_data['data']['img_detail'];
							$product_data['Product']['img_big']=$iamge_data['data']['img_big'];
							$product_data['Product']['img_original']=$iamge_data['data']['img_original'];
							$ProductGallery_data[]=$iamge_data['data'];
						}else{
							$error_msg .= $iamge_data['data'];
						}
					}
					
					if(isset($postfile['product_image3'])&&!empty($postfile['product_image3'])){
						$iamge_data=$this->product_image_process($postfile['product_image3'],$product_code,$category_id);
						if($iamge_data['code']=='1'){
							$ProductGallery_data[]=$iamge_data['data'];
						}else{
							$error_msg .= $iamge_data['data'];
						}
					}
					
					if(isset($postfile['product_image4'])&&!empty($postfile['product_image4'])){
						$iamge_data=$this->product_image_process($postfile['product_image4'],$product_code,$category_id);
						if($iamge_data['code']=='1'){
							$ProductGallery_data[]=$iamge_data['data'];
						}else{
							$error_msg .= $iamge_data['data'];
						}
					}
					
					if(isset($postfile['product_image5'])&&!empty($postfile['product_image5'])){
						$iamge_data=$this->product_image_process($postfile['product_image5'],$product_code,$category_id);
						if($iamge_data['code']=='1'){
							$ProductGallery_data[]=$iamge_data['data'];
						}else{
							$error_msg .= $iamge_data['data'];
						}
					}
					
					if(isset($postfile['product_image6'])&&!empty($postfile['product_image6'])){
						$iamge_data=$this->product_image_process($postfile['product_image6'],$product_code,$category_id);
						if($iamge_data['code']=='1'){
							$ProductGallery_data[]=$iamge_data['data'];
						}else{
							$error_msg .= $iamge_data['data'];
						}
					}
					
    					$this->Product->save($product_data['Product']);
    					$product_id=$this->Product->id;
    					
    					$product_data['ProductI18n']['id']=0;
    					$product_data['ProductI18n']['locale']=LOCALE;
    					$product_data['ProductI18n']['product_id']=$product_id;
    					$product_data['ProductI18n']['name']=$product_name;
					$product_data['ProductI18n']['description']=isset($postdata['description'])?trim($postdata['description']):'';
					$product_data['ProductI18n']['description02']=isset($postdata['description02'])?trim($postdata['description02']):'';
					$this->ProductI18n->save($product_data['ProductI18n']);
					
					if(!empty($ProductGallery_data)){
						foreach($ProductGallery_data as $k=>$v){
							$v['id']=0;
							$v['product_id']=$product_id;
							$v['orderby']=$k+1;
							$this->ProductGallery->save($v);
						}
					}
					
					if(!empty($ProductAttribute_info)){
						$attribute_codes=array();
						foreach($ProductAttribute_info as $k=>$v){
							$attribute_codes[]=$k;
						}
						$attribute_datas=$this->Attribute->find('list',array("fields"=>array("Attribute.code","Attribute.id"),"conditions"=>array("Attribute.code"=>$attribute_codes,"Attribute.status"=>'1')));
						foreach($ProductAttribute_info as $k=>$v){
							if(!isset($attribute_datas[$k]))continue;
							$product_attribute_data=array();
							$product_attribute_data['id']=0;
							$product_attribute_data['locale']=LOCALE;
							$product_attribute_data['product_id']=$product_id;
							$product_attribute_data['attribute_id']=$attribute_datas[$k];
							$product_attribute_data['attribute_value']=$v;
							$this->ProductAttribute->save($product_attribute_data);
						}
					}
					$result['code']='1';
					$result['message']='商品:'.$product_code."导入成功".$error_msg;
    				}else{
    					$result['message']='该商品货号已存在:'.$product_code;
    				}
    			}else{
    				$result['message']='商品货号、名称不能为空';
    			}
    		}
    		die(json_encode($result));
    }
    
    
    private function product_image_process($image_file,$code="",$category_id=0){
		$this->loadModel('PhotoCategoryGallery');
		$result=array();
		$result['code']='0';
		$result['data']="";
		$error_msg="";
		
		//列表缩略图宽度
		$thumbl_image_width = isset($this->configs['small_img_width']) ? $this->configs['small_img_width'] : 160;
		//列表缩略图高度
		$thumb_image_height = isset($this->configs['small_img_height']) ? $this->configs['small_img_height'] : 160;
		//中图宽度
		$image_width = isset($this->configs['mid_img_width']) ? $this->configs['mid_img_width'] : 400;
		//中图高度
		$image_height = isset($this->configs['mid_img_height']) ? $this->configs['mid_img_height'] : 400;
		//大图宽度
		$image_width_big = isset($this->configs['big_img_width']) ? $this->configs['big_img_width'] : 800;
		//大图高度
		$image_height_big = isset($this->configs['big_img_height']) ? $this->configs['big_img_height'] : 800;
		
		$imgaddr_original = WWW_ROOT.'/media/photos/'.date('Ym').'/'.$category_id.'/original/';
		$imgaddr_detail = WWW_ROOT.'/media/photos/'.date('Ym').'/'.$category_id.'/detail/';
		$imgaddr_big = WWW_ROOT.'/media/photos/'.date('Ym').'/'.$category_id.'/big/';
		$imgaddr_small = WWW_ROOT.'/media/photos/'.date('Ym').'/'.$category_id.'/small/';
		
		$this->mkdirs($imgaddr_original);
		$this->mkdirs($imgaddr_detail);
		$this->mkdirs($imgaddr_big);
		$this->mkdirs($imgaddr_small);
		
		$imgname_arr=array();
		$imgname_arr[0] = substr($image_file['name'], 0, strripos($image_file['name'], '.'));
		$imgname_arr[1] = substr($image_file['name'], strripos($image_file['name'], '.') + 1);
		$img_thumb_watermark_name = md5($imgname_arr[0].time());
		$image_name = $img_thumb_watermark_name.'.'.$imgname_arr[1];//要改成的文件名
		
		move_uploaded_file($image_file['tmp_name'], iconv('UTF-8', 'GBK//IGNORE', $imgaddr_original.$image_file['name']));
		$upload_img_src = $imgaddr_original;
		
		//重新命名图片名称
		rename(iconv('UTF-8', 'GBK//IGNORE', $upload_img_src.$image_file['name']), $imgaddr_original.$image_name);
		$upload_img_src = $imgaddr_original.$image_name;
		$img_original = $imgaddr_original.$image_name;//原图地址
		$img_detail = $imgaddr_detail.$image_name;//详细图 中图地址
		$img_thumb = $imgaddr_small.$image_name;//缩略图地址
		$img_big = $imgaddr_big.$image_name;//大图地址
		
		//商品缩略图
            $image_name = $this->make_thumb($img_original, $thumbl_image_width, $thumb_image_height, '#FFFFFF', $img_thumb_watermark_name, $imgaddr_small, $imgname_arr[1]);
            $image_name = $this->make_thumb($img_original, $image_width, $image_height, '#FFFFFF', $img_thumb_watermark_name, $imgaddr_detail, $imgname_arr[1]);
            $image_name = $this->make_thumb($img_original, $image_width_big, $image_height_big, '#FFFFFF', $img_thumb_watermark_name, $imgaddr_big, $imgname_arr[1]);
            
            //保存到数据库
            $photo_img_small = str_replace(WWW_ROOT, '', $img_thumb);
            $photo_img_detail = str_replace(WWW_ROOT, '', $img_detail);
            $photo_img_original = str_replace(WWW_ROOT, '', $img_original);
            $photo_img_big = str_replace(WWW_ROOT, '', $img_big);
            $photo_img_original_info = getimagesize($imgaddr_original.$image_name);
            
            $photo_name=array();
            $photo_name[0] = substr($image_file['name'], 0, strripos($image_file['name'], '.'));
            $photo_name[1] = substr($image_file['name'], strripos($image_file['name'], '.') + 1);
             
		$photo_category_galleries = array(
			'id'=>'0',
			'photo_category_id' => '0',
			'name' => preg_replace('/\W/', '', $photo_name[0]),
			'type' => $photo_name[1],
			'original_size' => intval($image_file['size'] / 1024),
			'original_pixel' => $photo_img_original_info[0].'*'.$photo_img_original_info[1],
			'img_small' => $photo_img_small,
			'img_detail' => $photo_img_detail,
			'img_original' => $photo_img_original,
			'img_big' => $photo_img_big,
			'orderby' => '50'
		);
		
		//保存到图片空间表
		$this->PhotoCategoryGallery->save($photo_category_galleries);
		
		$image_data=array();
		$image_data['img_thumb'] = isset($photo_img_small) ? $photo_img_small : '';
		$image_data['img_detail'] = isset($photo_img_detail) ? $photo_img_detail : '';
		$image_data['img_original'] = isset($photo_img_original) ? $photo_img_original : '';
		$image_data['img_big'] = isset($photo_img_big) ? $photo_img_big : '';
		
		$result['error'] = false;
		if (!file_exists($img_thumb)) {
			$error_msg .= $this->ld['code'].' '.$code.' '.$this->ld['picture'].$photo_img_original.$this->ld['thumbnail_generate_failed'].'\\r\\n';
		}
		if (!file_exists($img_detail)) {
			$error_msg .= $this->ld['code'].' '.$code.' '.$this->ld['picture'].$photo_img_original.$this->ld['detail_image_generate_failed'].'\\r\\n';
		}
		if (!file_exists($img_original)) {
			$error_msg .= $this->ld['code'].' '.$code.' '.$this->ld['picture'].$photo_img_original.$this->ld['original_image_build_failure'].'\\r\\n';
		}
		if (!file_exists($img_big)) {
			$error_msg .= $this->ld['code'].' '.$code.' '.$this->ld['picture'].$photo_img_original.$this->ld['big_image_generate_failure'].'\\r\\n';
		}
		
		if($error_msg==""){
			$result['code']='1';
			$result['data']=$image_data;
		}else{
			$result['data']=$error_msg;
		}
		return $result;
	}
	
	    public function mkdirs($path, $mode = 0777)
	    {
	        $dirs = explode('/', $path);
	        $pos = strrpos($path, '.');
	        if ($pos === false) {
	            $subamount = 0;
	        } else {
	            $subamount = 1;
	        }
	        for ($c = 0;$c < count($dirs) - $subamount; ++$c) {
	            $thispath = '';
	            for ($cc = 0; $cc <= $c; ++$cc) {
	                $thispath .= $dirs[$cc].'/';
	            }
	            if (!file_exists($thispath)) {
	                mkdir($thispath, $mode);
	            }
	        }
	    }

	    /**
	     * 创建图片的缩略图.
	     *
	     * @param string $img          原始图片的路径
	     * @param int    $thumb_width  缩略图宽度
	     * @param int    $thumb_height 缩略图高度
	     * @param int    $filename     图片名..
	     * @param strint $dir          指定生成图片的目录名
	     *
	     * @return mix 如果成功返回缩略图的路径，失败则返回false
	     */
	    public function make_thumb($img, $thumb_width = 0, $thumb_height = 0, $bgcolor = '#FFFFFF', $filename, $dir, $imgname)
	    {
	        //echo $filename;
	        /* 检查缩略图宽度和高度是否合法 */
	        if ($thumb_width == 0 && $thumb_height == 0) {
	            return false;
	        }
	        /* 检查原始文件是否存在及获得原始文件的信息 */
	        $org_info = @getimagesize($img);
	        if (!$org_info) {
	            return false;
	        }

	        $img_org = $this->img_resource($img, $org_info[2]);
	        /* 原始图片以及缩略图的尺寸比例 */
	        $scale_org = $org_info[0] / $org_info[1];
	        /* 处理只有缩略图宽和高有一个为0的情况，这时背景和缩略图一样大 */
	        if ($thumb_width == 0) {
	            $thumb_width = $thumb_height * $scale_org;
	        }
	        if ($thumb_height == 0) {
	            $thumb_height = $thumb_width / $scale_org;
	        }

	        /* 创建缩略图的标志符 */
	        $img_thumb = @imagecreatetruecolor($thumb_width, $thumb_height);//真彩

	        /* 背景颜色 */

	        if (empty($bgcolor)) {
	            $bgcolor = $bgcolor;
	        }
	        $bgcolor = trim($bgcolor, '#');
	        sscanf($bgcolor, '%2x%2x%2x', $red, $green, $blue);
	        $clr = imagecolorallocate($img_thumb, $red, $green, $blue);
	        imagefilledrectangle($img_thumb, 0, 0, $thumb_width, $thumb_height, $clr);

	        if ($org_info[0] / $thumb_width > $org_info[1] / $thumb_height) {
	            $lessen_width = $thumb_width;
	            $lessen_height = $thumb_width / $scale_org;
	        } else {
	            /* 原始图片比较高，则以高度为准 */
	            $lessen_width = $thumb_height * $scale_org;
	            $lessen_height = $thumb_height;
	        }
	        $dst_x = ($thumb_width  - $lessen_width)  / 2;
	        $dst_y = ($thumb_height - $lessen_height) / 2;

	        /* 将原始图片进行缩放处理 */
	        imagecopyresampled($img_thumb, $img_org, $dst_x, $dst_y, 0, 0, $lessen_width, $lessen_height, $org_info[0], $org_info[1]);
	        /* 生成文件 */
	        if (function_exists('imagejpeg')) {
	            $filename .= '.'.$imgname;
	            imagejpeg($img_thumb, $dir.$filename, 100);
	            /*pr($img_thumb);
	            pr($filename);die;*/
	        } elseif (function_exists('imagegif')) {
	            $filename .= '.'.$imgname;
	            imagegif($img_thumb, $dir.$filename, 100);
	        } elseif (function_exists('imagepng')) {
	            $filename .= '.'.$imgname;
	            imagepng($img_thumb, $dir.$filename, 100);
	        } else {
	            return false;
	        }
	        imagedestroy($img_thumb);
	        imagedestroy($img_org);
	        //确认文件是否生成
	        if (file_exists($dir.$filename)) {
	            return  $filename;
	        } else {
	            return false;
	        }
	    }

	    /**
	     * 根据来源文件的文件类型创建一个图像操作的标识符.
	     *
	     * @param string $img_file  图片文件的路径
	     * @param string $mime_type 图片文件的文件类型
	     *
	     * @return resource 如果成功则返回图像操作标志符，反之则返回错误代码
	     */
	    public function img_resource($img_file, $mime_type)
	    {
	        switch ($mime_type) {

	            case 1:
	            case 'image/gif':
	            $res = imagecreatefromgif($img_file);
	            break;

	            case 2:
	            case 'image/pjpeg':
	            case 'image/jpeg':
	            $res = imagecreatefromjpeg($img_file);
	            break;

	            case 3:
	            case 'image/x-png':
	            case 'image/png':
	            $res = imagecreatefrompng($img_file);
	            break;

	            default:
	            return false;
	        }

	        return $res;
	    }

	    /*
	        遍历目录下文件
	    */
	    public function traverse($path = '.')
	    {
	        $file_data = array();
	        $current_dir = opendir($path);//opendir()返回一个目录句柄,失败返回false
	          while (($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
	              $sub_dir = $path.DIRECTORY_SEPARATOR.$file;    //构建子目录路径
	              if ($file == '.' || $file == '..') {
	                  continue;
	              } elseif (is_dir($sub_dir)) {    //如果是目录,进行递归
	                   continue;
	              } else {
	                  //如果是文件,直接输出
	                     $file_data[] = $file;
	              }
	          }

	        return $file_data;
	    }
}
