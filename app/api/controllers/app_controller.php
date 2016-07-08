<?php

/*****************************************************************************
 * Seevia 前台控制
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为AppController的控制器
 *应用程序控制器.
 */
class AppController extends Controller
{
    /*
     *@var $view 对应视图
     *@var $locale 所用语言
     *@var $useDbConfig 数据库配置
     *@var $information_info 资源信息
     *@var $helpers 引用的帮助类
     *@var $uses	引用的数据库模型
     *@var $configs 系统参数
     *@var $languages 多语言信息
     *@var $navigations 导航
     *@var $components 引用的组件
     */
    public $view = 'Theme';
    public $locale = '';
    public $useDbConfig = 'default';
    public $server_host = '';

    public $languages = array();
    public $information_resources = array();
    public $system_resources = array();
    public $configs = array();
    public $ur_heres = array();
    public $components = array('RequestHandler','Cookie','Session');
    public $helpers = array('Cache');
    public $uses = array('Language','Config','LanguageDictionary','SystemResource','InformationResource');
    public $short = array('config' => 'short','use' => true);

    /**
     *调用action之前.
     */
    public function beforeFilter()
    {
        @session_start();
        //session有效域
        $this->Session->path = '/';
        //时区设置	todo
        @$time_zone = include ROOT.'time_zone.php';
        @ini_set('date.timezone', empty($time_zone[$this->configs['default_timezone']]) ? 'Asia/Shanghai' : $time_zone[$this->configs['default_timezone']]);
        unset($time_zone);
        //当前域名
        $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        $this->server_host = 'http://'.$host;
        $this->set('server_host', $this->server_host);
        $this->webroot = str_replace('\\','/',dirname($this->base).'/');
        $this->webroot = str_replace('cn','',$this->webroot);
        $this->webroot = str_replace('en','',$this->webroot);
        $this->webroot = str_replace('jpn','',$this->webroot);
        $this->webroot = str_replace('///','/',$this->webroot);
        $this->webroot = str_replace('//','/',$this->webroot);
        $this->set('webroot', $this->webroot);
        $this->set('web_base', $this->base);
        $this->set('webroot', $this->webroot);
        $this->set('web_base', $this->base);
        // 订单来源
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer_arr = array();
            $referer_arr = explode('/', $_SERVER['HTTP_REFERER']);
            if (!in_array($host, $referer_arr)) {
                $this->Cookie->write('referer', $_SERVER['HTTP_REFERER'], false, time() + 3600 * 24 * 365);
            }
        }
        //取商店配置
        $this->configs = $this->Config->getformatcode();
        $this->set_locale();//多语言设定语言
        $this->set('meta_description', $this->configs['seo-des']);
        $this->set('meta_keywords', $this->configs['seo-key']);
        $shop_default_img = '/themed/default/img/default.jpg';
        if (isset($this->configs['shop_default_img']) && $this->configs['shop_default_img'] != '') {
            $shop_default_img = $this->configs['shop_default_img'];
        }
        if (!isset($this->configs['products_default_image']) || $this->configs['products_default_image'] == '') {
            $this->configs['products_default_image'] = $shop_default_img;
        }
        $this->set('configs', $this->configs);
        $this->set('shop_default_img', $shop_default_img);
        Configure::write('shop_default_img', $shop_default_img);
        
        $this->themes_host = Configure::read('themes_host');
        $this->set('themes_host', $this->themes_host);
	
	$this->theme = 'default';
        App::build(array(
        	'views' => array(ROOT.'/api/views/themed/'.$this->theme.'/'),
    	));
	
        Configure::write('seo_url', (isset($this->configs['seo_url'])) ? $this->configs['seo_url'] : 0);
        
        $this->information_info = $this->InformationResource->information_formated(true, $this->locale);
        $this->set('information_info', $this->information_info);

        //字典语言 todo
        $this->ld = $this->LanguageDictionary->getformatcode(LOCALE);
        $this->set('ld', $this->ld);

        //定义首页ur_here
        $this->ur_heres[0] = array('name' => $this->ld['home'],'url' => '/');
        //高级系统资源库 内定
        $this->system_resources = $this->SystemResource->resource_formated(true, LOCALE);
        $this->set('system_resources', $this->system_resources);

        //普通资源库
        $this->information_resources = $this->InformationResource->information_formated(true,LOCALE);
        $this->set('information_resources', $this->information_info);
    }

    public function afterFilter()
    {
    		
    }

    /**
     *页面初始化todo.
     */
    public function page_init($params = '')
    {
    		
    }

    /**
     *调用action之后，调用ctp之前.
     */
    public function beforeRender()
    {
        if (!empty($this->pageTitle)) {
            $this->set('title_for_layout', $this->pageTitle);
        }
        $this->set('ur_heres', $this->ur_heres);
	
        //所有查到的商品初始化（商品多语言，价格,及set）
	
        if (isset($this->product_order_field)) {
            $this->set('product_order_field', $this->product_order_field);
        }

        //页面压缩
        if (@$this->gzip_enabled() && false) {
            @$this->set('gzip_is_start', 1);
            @ob_start('ob_gzhandler');
        } else {
            @$this->set('gzip_is_start', 0);
            @ob_start();
        }

        //性能统计，占用内存
        $this->data['memory_useage'] = number_format((memory_get_usage() / 1024), 3, '.', '');
        $db = &ConnectionManager::getDataSource($this->useDbConfig);
        $this->set('queriesCnt', $db->_queriesCnt);
        $this->set('queriesTime', $db->_queriesTime);
        $this->_setErrorLayout();

        //判断是都是模块化的页面
    }

    public function render($action = null, $layout = null, $file = null)
    {
    	if(isset($this->render)&&!empty($this->render)){
		if (isset($this->render['layout'])) {
			$layout = $this->render['layout'];
		}
		
		if (isset($this->render['action'])) {
			$action = $this->render['action'];
		}
    	}else if (isset($_SESSION['custom_page']) && $_SESSION['custom_page']) {
            // if in mobile mode, check for a valid view and use it
                if ($this->is_mobile) {
                    $layout = '/mobile/default_full';
                    $action = DS.strtolower($this->name).'/mobile/'.$this->action;
                   //     $this->render(DS.strtolower($this->name).'/mobile/'.$this->action);
                } else {
                    if (isset($this->page_layout)) {
                        //pr($this->page_layout);
                            $this->layout = $this->page_layout;
                    } else {
                        $this->layout = 'default_full';
                    }
                    $action = '/elements/custom_page';
                //		$this->render('/elements/custom_page');
                }
        }

        return parent::render($action, $layout);
    }

    /**
     * 获得系统是否启用了 gzip.
     *
     *@return $enabled_gzip 输出
     */
    public function gzip_enabled()
    {
        static $enabled_gzip = null;
        if ($enabled_gzip === null) {
            $enabled_gzip = ($this->configs['enable_gzip'] && function_exists('ob_gzhandler'));
        }

        return $enabled_gzip;
    }
    
    /**
     *登录验证.
     */
    public function checkSessionUser()
    {
        if (!isset($_SESSION['User'])) {
            $user_id = $_SESSION['User']['User']['id'];
            $this->UserRankLog->checkUserRank($user_id);
            $_SESSION['login_back'] = $this->here;
            $this->redirect('/users/login');
        }
    }

    /*
     *设定语言
     *
     */
    public function set_locale()
    {
        $this->languages = $this->Language->findalllang();
        foreach ($this->languages as $k => $v) {
            if ($v['Language']['is_default'] == 1) {
                $default_lng = $v['Language']['locale'];
            }
        }
        if (!isset($default_lng)) {
            foreach ($this->languages as $k => $v) {
                if ($v['Language']['front'] == 1) {
                    $default_lng = $v['Language']['locale'];
                }
                break;
            }
        }
        $this->languages_assoc = $this->Language->findalllang_assoc();
        if ($this->Cookie->read('lng') != '' && !in_array($this->params['controller'], array('products'))) {
        }
        if (!defined('LOCALE')) {
            define('LOCALE', $default_lng);
        } elseif (defined('LOCALE') && in_array(LOCALE, $this->languages_assoc)) {        //目录式多语言
        } else {
            echo '这个语言未开通';
            die();
        }
        $this->locale = LOCALE;
        $this->Cookie->write('lng', LOCALE, time() + 3600 * 24 * 365);
        //设置当前路径的不同语言链接
        $request_uri = $_SERVER['REQUEST_URI'];
        if (!empty($this->languages[LOCALE]['Language']['map'])) {
            $request_uri = str_replace('/'.$this->languages[LOCALE]['Language']['map'].'/', '', $request_uri);
        }
        foreach ($this->languages as $k => $v) {
            $this->languages[$k]['Language']['url'] = $this->server_host.(empty($v['Language']['map']) ? '' : '/'.$v['Language']['map'].'/').$request_uri;
        }
        $this->set('languages', $this->languages);
        //单语言模型处理
        if (isset($this->configs['default_language_model']) && $this->configs['default_language_model'] != '') {
            $default_language_model = $this->configs['default_language_model'];
        } else {
            $default_language_model = '';
        }
    }

    public function _setErrorLayout()
    {
        if ($this->name == 'CakeError') {
            $this->beforeFilter();
            if (Configure::read('debug') == 0) {
                $this->layout = 'error';
            }
            //$this->render('/errors/missing_controller.ctp');
        }
    }
    
    //zhou add to change the ld
    public function change_ld_wap($locale = 'chi')
    {
        $ld = $this->LanguageDictionary->getformatcodewap($locale);
        $this->set('ld', $ld);
    }
	
    public function is_thm_sb()
    {
        $thm = Configure::read('themes_host');
        $uthm = isset($_GET['themes']) ? $_GET['themes'] : $_SESSION['template_use'];
        $url = $thm.'/themed/'.$uthm.'/css/common.css';
        $Headers = get_headers($url);
        if (@preg_match('|404|', $Headers[0])) {
            //off
            return false;
        } else {
            //in
            return true;
        }
    }
    
    function clean_xss($mix){
	    	if(is_array($mix)){
	    		foreach($mix as $k=> $v){
	    			$str = $this->clean_xss($v);
	    			$mix[$k]=$str;
	    		}
			return $mix;
		}else if(is_string($mix)){
			$str = trim($mix);  //清理空格
			$str = strip_tags($str);   //过滤html标签
			$str = htmlspecialchars($str,ENT_QUOTES);   //过滤html标签'
			return $str;
		}else if(is_numeric($mix)){
			return $mix;
		}
	}
	
}
?>