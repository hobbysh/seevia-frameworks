<?php

uses('sanitize');
/**
 *这是一个名为 PagesController 的页面控制器.
 *
 *@var
 *@var
 *@var
 *@var
 *@var
 *@var
 */
class PagesController extends AppController
{
    public $name = 'Pages';
    public $helpers = array('Html','Flash','Cache');
    public $uses = array('MailTemplate','Page','PageI18n');
    public $components = array('RequestHandler','Cookie','Session','Captcha','Email');
    /**
     *主页.
     */
    public function home()
    {
        $this->page_init();
        $this->pageTitle = $this->configs['shop_title'];
    }

    /**
     *关闭.
     */
    public function closed()
    {
        if ($this->configs['shop_temporal_closed'] == 1) {
            //用户关店
            $this->page_init();
            $this->set('shop_logo', $this->configs['shop_logo']);
            $this->pageTitle = $this->ld['shop_closed'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['shop_closed'].'  '.$this->configs['closed_reason'], '/', 999999999999999);
        } elseif ($this->configs['shop_temporal_closed'] == 2) {
            //系统关店
            $this->layout = '';
            $this->set('closed_reason', $this->configs['closed_reason']);
        }
    }
	
	/**
     *显示静态页面.
     */
    public function view($id = 0)
    {
        $this->layout = 'default';
        if (!is_numeric($id) || $id < 1) {
            $this->pageTitle = $this->ld['invalid_id'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['invalid_id'], '/', 5);
            return;
        }
        $conditions = array('Page.id' => $id,'Page.status' => '1');
        $this->Page->set_locale($this->locale);
        $page = $this->Page->find('first', array('conditions' => $conditions));
        //pr($page);
        if (empty($page)) {
            $this->pageTitle = $this->ld['page'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['page'].$this->ld['not_exist'], '/', 5);

            return;
        } elseif (!empty($topic)) {
            $this->pageTitle = $page['PageI18n']['title'].' - '.$this->configs['shop_title'];
        }
        $this->set('page', $page);
        $this->set('meta_description', $page['PageI18n']['meta_description'].' '.$this->configs['seo-des']);
        $this->set('meta_keywords', $page['PageI18n']['meta_keywords'].' '.$this->configs['seo-key']);
        $this->ur_heres[] = array('name' => $page['PageI18n']['title'],'url' => '');
        $this->pageTitle = $page['PageI18n']['title'].' - '.$this->configs['shop_title'];
    }

    /**
     *关闭.
     */
    public function homepage()
    {
        //取当前模板
        $this->page_init();
        $this->pageTitle = $this->configs['shop_title'];
        $this->layout = 'default_page';
    }

    /**
     *定制首页.
     */
    public function custom_made()
    {
        $this->pageTitle = $this->configs['shop_title'];
        $this->layout = 'default_full';
        $article_ids = $this->Comment->find('list', array('conditions' => array('Comment.status' => 1, 'Comment.type' => 'A'), 'fields' => 'Comment.type_id'));
        $articles = $this->Article->find('all', array('conditions' => $article_ids, 'fields' => array('Article.id', 'ArticleI18n.title')));
        $article_titles = array();
        foreach ($articles as $a) {
            $article_titles[$a['Article']['id']] = $a['ArticleI18n']['title'];
        }
        $this->set('article_titles', $article_titles);
        $this->page_init();
    }
    
    public function jsdemo()
    {
        $this->page_init();
        $this->pageTitle = $this->configs['shop_title'];
        $this->layout = 'default_page';
    }
}
