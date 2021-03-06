<?php

/*****************************************************************************
 * svcms  多语言数据字典模型
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
class LanguageDictionary extends AppModel
{
    public $useDbConfig = 'default';
    public $useTable = 'dictionaries';
    /*
     * @var $name LanguageDictionary 语言代码表
     */

    public $name = 'LanguageDictionary';
    /**
     * getformatcode方法，获语言种类代码.
     *
     * @param $locale 输入语言代码
     *
     * @return $languages_formatcode 返回语言内容
     */
    public function getformatcode($locale)
    {
        $node['config'] = 'node';
        $node['use'] = true;
            //$languages = $this->findallbylocale($locale);
            $languages = $this->find('all', array('cache' => $node, 'fields' => array('LanguageDictionary.name', 'LanguageDictionary.value'), 'conditions' => array('locale' => $locale, 'location' => 'backend')));
        $languages_formatcode = array();
        if (is_array($languages)) {
            foreach ($languages as $v) {
                $languages_formatcode[$v['LanguageDictionary']['name']] = $v['LanguageDictionary']['value'];
            }
        }

        return $languages_formatcode;
    }
}
