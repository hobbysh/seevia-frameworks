<?php
    $short = array('config' => 'short','use' => true);
    if (!defined('LOCALE')) {
        $lngsModel = ClassRegistry::init('Language');
        $lngs = $lngsModel->find('first', array('cache' => $short, 'conditions' => array('is_default' => '1'), 'fields' => array('Language.locale', 'Language.front')));
        if ($lngs['Language']['front'] == 0) {
            $lngs2 = $lngsModel->find('first', array('cache' => $short, 'conditions' => array('front' => '1'), 'fields' => array('Language.locale', 'Language.front')));
            define('LOCALE', $lngs2['Language']['locale']);
        } else {
            define('LOCALE', $lngs['Language']['locale']);
        }
    }

    Router::connect('/closed', array('controller' => 'users', 'action' => 'closed'));
    Router::parseExtensions('rss', 'xml');
    Router::connect('/sitemaps', array('controller' => 'sitemaps', 'action' => 'index'));
    Router::connect('/sitemap', array('controller' => 'sitemaps', 'action' => 'view'));
    Router::connect('/:controller/:id', array('action' => 'view'), array('pass' => array('id'), 'id' => '[0-9]+'));
    
    $RoutesModel = ClassRegistry::init('Route');
    $home_infos = $RoutesModel->find('first', array('cache' => $short, 'conditions' => array('Route.url' => '','Route.status'=>'1')));
    if (empty($home_infos)) {
        Router::connect('/', array('controller' => 'pages', 'action' => 'home'));
    } else {
        Router::connect('/', array('controller' => $home_infos['Route']['controller'], 'action' => $home_infos['Route']['action'], $home_infos['Route']['model_id']));
    }
    
    $all_infos = $RoutesModel->find('all', array('cache' => $short, 'conditions' => array('Route.status' => '1','Route.url <>'=>'')));
    foreach ($all_infos as $k => $v) {
        Router::connect('/'.trim($v['Route']['url']), array('controller' => $v['Route']['controller'], 'action' => $v['Route']['action'], $v['Route']['model_id']));
    }
    Router::connect('/api/uc', array('controller' => 'ucs', 'action' => 'index'));
    Router::connect('/wap', array('controller' => 'waps', 'action' => 'index'));
    Router::connect('/soap/:controller/:action/*', array('prefix' => 'soap', 'soap' => true));
