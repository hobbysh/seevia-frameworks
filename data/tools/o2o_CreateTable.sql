-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014-10-27 09:12:25
-- 服务器版本: 5.5.40-0ubuntu0.14.04.1
-- PHP 版本: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 数据库: `framework`
--

--
-- 表的结构 `svcms_advertisements`
--

DROP TABLE IF EXISTS `svcms_advertisements`;
CREATE TABLE IF NOT EXISTS `svcms_advertisements` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `advertisement_position_id` int(11) NOT NULL DEFAULT '0' COMMENT '0站外广告 从1开始代表的是该广告所处的广告位 同表svcart_advertisement_positions 中的字段id的值',
  `code` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告位置标识符',
  `media_type` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '\r\n\r\n广告类型，0，图片；1，flash;2,代码；3，文字',
  `contact_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告联系人',
  `contact_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告联系人的邮箱',
  `contact_tele` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告联系人的电话',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `click_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `advertisement_position_id` (`advertisement_position_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_advertisement_effects`
--

DROP TABLE IF EXISTS `svcms_advertisement_effects`;
CREATE TABLE IF NOT EXISTS `svcms_advertisement_effects` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `advertisements_id` int(11) NOT NULL DEFAULT '0' COMMENT '父级id',
  `locale` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言',
  `type` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '类型',
  `configs` text COLLATE utf8_unicode_ci NOT NULL COMMENT '配置',
  `images` varchar(800) COLLATE utf8_unicode_ci NOT NULL COMMENT '图片',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `created` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '创建',
  `modified` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '修改',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_advertisement_effects_defaults`
--

DROP TABLE IF EXISTS `svcms_advertisement_effects_defaults`;
CREATE TABLE IF NOT EXISTS `svcms_advertisement_effects_defaults` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `locale` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言',
  `type` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '类型',
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '特效名称',
  `show_link` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '展示链\r\n\r\n接',
  `configs` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '配置',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `created` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '2008-01-01 00:00:00 	' COMMENT '创建',
  `modified` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '2008-01-01 00:00:00 	' COMMENT '修改',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_advertisement_i18ns`
--

DROP TABLE IF EXISTS `svcms_advertisement_i18ns`;
CREATE TABLE IF NOT EXISTS `svcms_advertisement_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `advertisement_id` int(11) NOT NULL DEFAULT '0' COMMENT '广告编号',
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '该条广告记录的广告名称',
  `description` text COLLATE utf8_unicode_ci COMMENT '广告描述',
  `url` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告链接地址',
  `url_type` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '链接类型：0直接连接，1间接链接',
  `start_time` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '广告开始时间',
  `end_time` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '广告结束时间',
  `code` text COLLATE utf8_unicode_ci COMMENT '广告链接的表现，文字广告就是文字或图片和flash就是它们的地址，代码广告就是代码内容',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`advertisement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_advertisement_positions`
--

DROP TABLE IF EXISTS `svcms_advertisement_positions`;
CREATE TABLE IF NOT EXISTS `svcms_advertisement_positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '广告位编号',
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告位名称',
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告位置',
  `template_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '对应哪个模板name',
  `ad_width` smallint(5) NOT NULL DEFAULT '0' COMMENT '广告位宽度',
  `ad_height` smallint(5) NOT NULL DEFAULT '0' COMMENT '广告位高度',
  `position_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告位描述',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_documents`
--

DROP TABLE IF EXISTS `svcms_documents`;
CREATE TABLE IF NOT EXISTS `svcms_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文件编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名称',
  `type` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '文件类型',
  `file_size` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '文件大小',
  `file_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '文件路径',
  `file_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '文件物理路径',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_links`
--

DROP TABLE IF EXISTS `svcms_links`;
CREATE TABLE IF NOT EXISTS `svcms_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '友情链接编号',
  `type` smallint(1) NOT NULL DEFAULT '1' COMMENT '1.友情链接 2.赞助商 3.合作伙伴',
  `contact_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系人',
  `contact_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Email地址',
  `contact_tele` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系电话',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `target` enum('_self','_blank') COLLATE utf8_unicode_ci NOT NULL DEFAULT '_self' COMMENT '打开位置',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='网址连接' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_link_i18ns`
--

DROP TABLE IF EXISTS `svcms_link_i18ns`;
CREATE TABLE IF NOT EXISTS `svcms_link_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '友情链接编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `link_id` int(11) NOT NULL DEFAULT '0' COMMENT '友情链接编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '友情链接名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '友情链接描述',
  `url` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '友情链接地址',
  `click_stat` int(11) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `img01` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片',
  `img02` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片2',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`link_id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_navigations`
--

DROP TABLE IF EXISTS `svcms_navigations`;
CREATE TABLE IF NOT EXISTS `svcms_navigations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '导航编号',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级导航',
  `type` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '导航类型[H;T;M;B...]',
  `orderby` tinyint(4) NOT NULL DEFAULT '10' COMMENT '排序',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `icon` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '缩略图',
  `target` enum('_self','_blank') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '_self' COMMENT '打开位置',
  `controller` enum('pages','categories','brands','products','articles','cars','static_pages') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pages' COMMENT '系统内容',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `type` (`type`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_navigation_i18ns`
--

DROP TABLE IF EXISTS `svcms_navigation_i18ns`;
CREATE TABLE IF NOT EXISTS `svcms_navigation_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '导航多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `navigation_id` mediumint(9) NOT NULL DEFAULT '0' COMMENT '导航编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '导航栏名称',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'URL链接',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '描述',
  `img01` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片1',
  `img02` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片2',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_2` (`locale`,`navigation_id`),
  KEY `locale` (`locale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_pages`
--

DROP TABLE IF EXISTS `svcms_pages`;
CREATE TABLE IF NOT EXISTS `svcms_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0:无效;1:有效;2:删除',
  `operator_id` int(11) NOT NULL COMMENT '操作员id',
  `showtime` datetime DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_page_i18ns`
--

DROP TABLE IF EXISTS `svcms_page_i18ns`;
CREATE TABLE IF NOT EXISTS `svcms_page_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '页面多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `page_id` int(11) NOT NULL DEFAULT '0' COMMENT '页面编号 取页面page主表自增ID',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '页面标题',
  `subtitle` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '副标题',
  `meta_keywords` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '页面的关键字',
  `meta_description` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '页面描述',
  `content` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '页面内容',
  `img01` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片1',
  `img02` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片2',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_2` (`locale`,`page_id`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_photo_categories`
--

DROP TABLE IF EXISTS `svcms_photo_categories`;
CREATE TABLE IF NOT EXISTS `svcms_photo_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '相册分类编号',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `custom` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0：系统尺寸，1：自定义尺寸',
  `cat_small_img_height` int(11) NOT NULL DEFAULT '140' COMMENT '分类下小图默认高度',
  `cat_small_img_width` int(11) NOT NULL DEFAULT '140' COMMENT '分类下小图默认宽度',
  `cat_mid_img_height` int(11) NOT NULL DEFAULT '400' COMMENT '分类下中图默认高度',
  `cat_mid_img_width` int(11) NOT NULL DEFAULT '400' COMMENT '分类下中图默认宽度',
  `cat_big_img_height` int(11) NOT NULL DEFAULT '800' COMMENT '分类下大图默认高度',
  `cat_big_img_width` int(11) NOT NULL DEFAULT '800' COMMENT '分类下大图默认宽度',
  `img01` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '图片1',
  `img02` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '图片2',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_photo_category_galleries`
--

DROP TABLE IF EXISTS `svcms_photo_category_galleries`;
CREATE TABLE IF NOT EXISTS `svcms_photo_category_galleries` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '图片编号',
  `photo_category_id` int(11) NOT NULL DEFAULT '0' COMMENT '相册分类编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片名称',
  `type` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '图片类型',
  `original_size` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '原图大小',
  `original_pixel` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '原图尺寸',
  `img_small` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '小图',
  `img_detail` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '相册中图',
  `img_big` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '相册大图',
  `img_original` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '原始图',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `photo_category_id` (`photo_category_id`),
  KEY `name` (`name`),
  KEY `created` (`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_photo_category_i18ns`
--

DROP TABLE IF EXISTS `svcms_photo_category_i18ns`;
CREATE TABLE IF NOT EXISTS `svcms_photo_category_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '相册分类多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `photo_category_id` int(11) NOT NULL DEFAULT '0' COMMENT '相册分类编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '相册分类名称',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`photo_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svcms_templates`
--

DROP TABLE IF EXISTS `svcms_templates`;
CREATE TABLE IF NOT EXISTS `svcms_templates` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '模板名',
  `description` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模版的名称',
  `template_style` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '模版的颜色样式',
  `template_img` varchar(900) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'http://www.seevia.cn/' COMMENT '作者地址',
  `show_css` text COLLATE utf8_unicode_ci COMMENT '模板样式',
  `mobile_css` text COLLATE utf8_unicode_ci COMMENT '手机模板样式',
  `mobile_status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '是否启用手机版',
  `status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '是否有效',
  `is_default` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否默认',
  `author` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '作者',
  `version` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '版本',
  `style` varchar(55) COLLATE utf8_unicode_ci NOT NULL COMMENT '模板样式',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_categories`
--

DROP TABLE IF EXISTS `svedi_api_categories`;
CREATE TABLE IF NOT EXISTS `svedi_api_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `api_project_code` varchar(50) NOT NULL COMMENT 'API项目代码',
  `code` varchar(50) NOT NULL COMMENT '分类代码',
  `name` varchar(200) NOT NULL COMMENT '分类名称',
  `description` varchar(200) NOT NULL COMMENT '描述',
  `orderby` int(11) NOT NULL DEFAULT '50' COMMENT '排序(默认:50)',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='API项目分类表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_error_code_interpretations`
--

DROP TABLE IF EXISTS `svedi_api_error_code_interpretations`;
CREATE TABLE IF NOT EXISTS `svedi_api_error_code_interpretations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `api_project_code` varchar(50) NOT NULL COMMENT 'API项目代码',
  `code` varchar(50) NOT NULL COMMENT '代码',
  `description` varchar(200) NOT NULL COMMENT '描述',
  `solution` varchar(200) NOT NULL COMMENT '解决方案',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='API方法响应异常示例表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_methods`
--

DROP TABLE IF EXISTS `svedi_api_methods`;
CREATE TABLE IF NOT EXISTS `svedi_api_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `api_project_code` varchar(50) NOT NULL COMMENT 'API项目代码',
  `api_category_code` varchar(50) NOT NULL COMMENT 'API项目分类代码',
  `code` varchar(50) NOT NULL COMMENT '方法代码',
  `type` char(8) NOT NULL DEFAULT '0' COMMENT '类型[0:基础,1:免费] ',
  `name` varchar(200) NOT NULL COMMENT '分类名称',
  `description` text NOT NULL COMMENT '描述',
  `orderby` int(12) NOT NULL DEFAULT '50' COMMENT '默认：50',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='API方法表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_method_error_codes`
--

DROP TABLE IF EXISTS `svedi_api_method_error_codes`;
CREATE TABLE IF NOT EXISTS `svedi_api_method_error_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `api_project_code` varchar(50) NOT NULL COMMENT 'API项目代码',
  `api_method_code` varchar(50) NOT NULL COMMENT 'API方法代码',
  `error_code` varchar(50) NOT NULL COMMENT 'API错误代码',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='API方法响应异常示例表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_method_faqs`
--

DROP TABLE IF EXISTS `svedi_api_method_faqs`;
CREATE TABLE IF NOT EXISTS `svedi_api_method_faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `api_project_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'API项目代码',
  `api_category_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'API项目分类代码',
  `api_method_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'API方法代码',
  `question` text COLLATE utf8_unicode_ci NOT NULL COMMENT '问题',
  `answer` text COLLATE utf8_unicode_ci NOT NULL COMMENT '答案',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='API方法FAQ表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_method_requests`
--

DROP TABLE IF EXISTS `svedi_api_method_requests`;
CREATE TABLE IF NOT EXISTS `svedi_api_method_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `api_project_code` varchar(50) NOT NULL COMMENT 'API项目代码',
  `api_category_code` varchar(50) NOT NULL COMMENT 'API项目分类代码',
  `api_method_code` varchar(50) NOT NULL COMMENT 'API方法代码',
  `name` varchar(200) NOT NULL COMMENT '分类名称',
  `type` varchar(50) NOT NULL COMMENT '类型',
  `required` char(1) NOT NULL COMMENT '是否必须[0:否,1:是]',
  `defualt` varchar(50) NOT NULL COMMENT '分类名称',
  `description` text NOT NULL COMMENT '描述',
  `orderby` int(11) NOT NULL DEFAULT '50' COMMENT '排序(默认:50)',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='API方法请求参数表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_method_request_examples`
--

DROP TABLE IF EXISTS `svedi_api_method_request_examples`;
CREATE TABLE IF NOT EXISTS `svedi_api_method_request_examples` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `api_project_code` varchar(50) NOT NULL COMMENT 'API项目代码',
  `api_category_code` varchar(50) NOT NULL COMMENT 'API项目分类代码',
  `api_method_code` varchar(50) NOT NULL COMMENT 'API方法代码',
  `type` varchar(50) NOT NULL DEFAULT '0' COMMENT '类型',
  `description` text NOT NULL COMMENT '描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='API方法请求示例表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_method_responses`
--

DROP TABLE IF EXISTS `svedi_api_method_responses`;
CREATE TABLE IF NOT EXISTS `svedi_api_method_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `api_project_code` varchar(50) NOT NULL COMMENT 'API项目代码',
  `api_category_code` varchar(50) NOT NULL COMMENT 'API项目分类代码',
  `api_method_code` varchar(50) NOT NULL COMMENT 'API方法代码',
  `name` varchar(200) NOT NULL COMMENT '名称',
  `type` varchar(50) NOT NULL COMMENT '类型',
  `samples` varchar(200) NOT NULL COMMENT '示例值',
  `description` text NOT NULL COMMENT '描述',
  `customer_case_id` int(11) NOT NULL DEFAULT '50' COMMENT '排序(默认:50)',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='API方法响应参数表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_method_response_examples`
--

DROP TABLE IF EXISTS `svedi_api_method_response_examples`;
CREATE TABLE IF NOT EXISTS `svedi_api_method_response_examples` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `api_project_code` varchar(50) NOT NULL COMMENT 'API项目代码',
  `api_category_code` varchar(50) NOT NULL COMMENT 'API项目分类代码',
  `api_method_code` varchar(50) NOT NULL COMMENT 'API方法代码',
  `type` varchar(50) NOT NULL COMMENT '类型',
  `description` text NOT NULL COMMENT '描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='API方法响应示例表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_method_response_exceptions`
--

DROP TABLE IF EXISTS `svedi_api_method_response_exceptions`;
CREATE TABLE IF NOT EXISTS `svedi_api_method_response_exceptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `api_project_code` varchar(50) NOT NULL COMMENT 'API项目代码',
  `api_category_code` varchar(50) NOT NULL COMMENT 'API项目分类代码',
  `api_method_code` varchar(50) NOT NULL COMMENT 'API方法代码',
  `type` varchar(50) NOT NULL COMMENT '类型',
  `description` text NOT NULL COMMENT '描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='API方法响应异常示例表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_objects`
--

DROP TABLE IF EXISTS `svedi_api_objects`;
CREATE TABLE IF NOT EXISTS `svedi_api_objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `api_project_code` varchar(50) NOT NULL COMMENT 'API项目代码',
  `name` varchar(200) NOT NULL COMMENT '名称',
  `code` varchar(200) NOT NULL COMMENT '代码',
  `type` varchar(50) NOT NULL DEFAULT '0' COMMENT '类型',
  `description` varchar(200) NOT NULL COMMENT '描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='API对象表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_object_fields`
--

DROP TABLE IF EXISTS `svedi_api_object_fields`;
CREATE TABLE IF NOT EXISTS `svedi_api_object_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `api_project_code` varchar(50) NOT NULL COMMENT 'API项目代码',
  `api_object_code` varchar(50) NOT NULL COMMENT 'API项目分类代码',
  `name` varchar(200) NOT NULL COMMENT '名称',
  `type` varchar(50) NOT NULL DEFAULT '0' COMMENT '类型',
  `samples` varchar(200) NOT NULL COMMENT '示例值',
  `description` varchar(200) NOT NULL COMMENT '描述',
  `orderby` int(11) NOT NULL DEFAULT '50' COMMENT '排序(默认:50)',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='API对象字段表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_projects`
--

DROP TABLE IF EXISTS `svedi_api_projects`;
CREATE TABLE IF NOT EXISTS `svedi_api_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `code` varchar(50) NOT NULL COMMENT 'API项目代码',
  `name` varchar(200) NOT NULL COMMENT 'API项目名称',
  `http_address` varchar(200) NOT NULL COMMENT 'HTTP请求地址',
  `sandbox_http_address` varchar(200) NOT NULL COMMENT '沙箱HTTP请求地址',
  `https_address` varchar(200) NOT NULL COMMENT 'HTTPS请求地址',
  `sandbox_https_address` varchar(200) NOT NULL COMMENT '沙箱HTTPS请求地址',
  `status` char(4) NOT NULL DEFAULT '1' COMMENT '状态[0:无效 1:有效 2:停用 3:删除 ]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='API项目表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_project_apps`
--

DROP TABLE IF EXISTS `svedi_api_project_apps`;
CREATE TABLE IF NOT EXISTS `svedi_api_project_apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `api_project_code` varchar(50) NOT NULL COMMENT 'API项目代码',
  `app_key` varchar(200) NOT NULL COMMENT 'APP KEY',
  `app_secret` varchar(200) NOT NULL COMMENT 'API SECRET',
  `authority_type_code` varchar(200) NOT NULL COMMENT '权限',
  `status` char(1) NOT NULL DEFAULT '1' COMMENT '状态[0:无效 1:有效 2:停用 3:删除 ]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='API项目应用表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_project_app_authority_types`
--

DROP TABLE IF EXISTS `svedi_api_project_app_authority_types`;
CREATE TABLE IF NOT EXISTS `svedi_api_project_app_authority_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `api_project_app_id` int(11) NOT NULL COMMENT 'API项目应用id',
  `code` varchar(200) NOT NULL COMMENT '授权类型代码',
  `authority_method_codes` text NOT NULL COMMENT '权限方法代码表',
  `status` char(1) NOT NULL DEFAULT '1' COMMENT '状态[0:无效 1:有效 2:停用 3:删除 ]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='API项目应用表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svedi_api_project_common_parameters`
--

DROP TABLE IF EXISTS `svedi_api_project_common_parameters`;
CREATE TABLE IF NOT EXISTS `svedi_api_project_common_parameters` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `api_project_code` varchar(50) NOT NULL COMMENT 'API项目代码',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `type` char(20) NOT NULL DEFAULT '' COMMENT '类型 ',
  `required` char(1) NOT NULL COMMENT '是否必须[0:否,1:是]',
  `description` varchar(200) NOT NULL COMMENT '描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='API项目公共请求参数表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_applications`
--

DROP TABLE IF EXISTS `svsys_applications`;
CREATE TABLE IF NOT EXISTS `svsys_applications` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '应用id',
  `groupby` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '应用类别',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '应用排序',
  `code` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '参数',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '是否有效 1有效0停用',
  `end_time` datetime NOT NULL COMMENT '有效期至',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_application_configs`
--

DROP TABLE IF EXISTS `svsys_application_configs`;
CREATE TABLE IF NOT EXISTS `svsys_application_configs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `app_id` int(11) NOT NULL COMMENT '应用ID',
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '属性的格式',
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '代码',
  `group_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'defult_app' COMMENT '参数分类',
  `subgroup_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_application_config_i18ns`
--

DROP TABLE IF EXISTS `svsys_application_config_i18ns`;
CREATE TABLE IF NOT EXISTS `svsys_application_config_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `app_id` int(11) NOT NULL COMMENT '应用ID',
  `app_config_id` int(11) NOT NULL,
  `config_code` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `description` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '属性描述',
  `remark` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '属性备\r\n\r\n注',
  `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '属性值',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_application_i18ns`
--

DROP TABLE IF EXISTS `svsys_application_i18ns`;
CREATE TABLE IF NOT EXISTS `svsys_application_i18ns` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '应用id',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `app_id` int(11) NOT NULL COMMENT 'app id',
  `name` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '应用名称',
  `unit_name` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '月' COMMENT '单位名称',
  `tags` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '关键字',
  `directory` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '应用描述',
  `copyright` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT ' ' COMMENT '版权信息',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_configs`
--

DROP TABLE IF EXISTS `svsys_configs`;
CREATE TABLE IF NOT EXISTS `svsys_configs` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '参数ID',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '商店编号[0:系统]',
  `group_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '设置参数组',
  `subgroup_code` varchar(50) NOT NULL,
  `code` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '参数名\r\n\r\n称',
  `type` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '参数类型',
  `readonly` int(1) NOT NULL DEFAULT '0' COMMENT '是否只读，0不是，1是',
  `section` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '版本标识',
  `status` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态：0.无效;1.有效',
  `orderby` int(4) unsigned NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `type` (`type`),
  KEY `store_id` (`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_config_i18ns`
--

DROP TABLE IF EXISTS `svsys_config_i18ns`;
CREATE TABLE IF NOT EXISTS `svsys_config_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '配置多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `config_id` int(11) NOT NULL DEFAULT '0' COMMENT '配送编号',
  `config_code` varchar(60) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '配送名称',
  `default_value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '默认值',
  `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '值',
  `options` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '可选值',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '描述',
  `param01` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '参数1',
  `param02` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '参数2',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_3` (`locale`,`config_id`),
  KEY `locale` (`locale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_cronjobs`
--

DROP TABLE IF EXISTS `svsys_cronjobs`;
CREATE TABLE IF NOT EXISTS `svsys_cronjobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '任务名称',
  `task_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态',
  `last_time` datetime NOT NULL COMMENT '上次运行时间',
  `next_time` datetime NOT NULL COMMENT '下次运行时间',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `interval_time` datetime NOT NULL,
  `app_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `param01` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `param02` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `remark` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_name` (`task_name`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_dictionaries`
--

DROP TABLE IF EXISTS `svsys_dictionaries`;
CREATE TABLE IF NOT EXISTS `svsys_dictionaries` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言代码',
  `location` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'front' COMMENT '前后台参数区分',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `type` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '类型',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '描述',
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '内容',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`name`,`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_information_resources`
--

DROP TABLE IF EXISTS `svsys_information_resources`;
CREATE TABLE IF NOT EXISTS `svsys_information_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资源ID',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '资源上级ID',
  `code` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '资源代码',
  `information_value` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '资源代码的值',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '1' COMMENT '状态0:无效1:\r\n\r\n有效',
  `orderby` tinyint(3) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_information_resource_i18ns`
--

DROP TABLE IF EXISTS `svsys_information_resource_i18ns`;
CREATE TABLE IF NOT EXISTS `svsys_information_resource_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资源多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言编码',
  `information_resource_id` int(11) NOT NULL DEFAULT '0' COMMENT '资源编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '资源名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`information_resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_languages`
--

DROP TABLE IF EXISTS `svsys_languages`;
CREATE TABLE IF NOT EXISTS `svsys_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言代码',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言',
  `charset` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '字符集',
  `map` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '系统映射',
  `img01` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片01',
  `img02` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '图片02',
  `front` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '前台显示',
  `backend` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '后台显示',
  `is_default` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '1为默认',
  `google_translate_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'google 翻译接口',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `front` (`front`),
  KEY `backend` (`backend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_mail_send_histories`
--

DROP TABLE IF EXISTS `svsys_mail_send_histories`;
CREATE TABLE IF NOT EXISTS `svsys_mail_send_histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `sender_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '发送人姓名',
  `receiver_email` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '接收人地址',
  `cc_email` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '抄送地址',
  `bcc_email` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '暗送人地址',
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '主题',
  `html_body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '邮件内容',
  `text_body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '邮件内容',
  `sendas` char(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text' COMMENT 'html,text',
  `flag` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '1.发送成功，0.发送失败',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_mail_send_queues`
--

DROP TABLE IF EXISTS `svsys_mail_send_queues`;
CREATE TABLE IF NOT EXISTS `svsys_mail_send_queues` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `sender_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '发送人姓名',
  `receiver_email` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '接收人地址',
  `cc_email` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '抄送地址',
  `bcc_email` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '暗送人地址',
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '主题',
  `html_body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '邮件内容',
  `text_body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '邮件内容',
  `sendas` char(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text' COMMENT 'html,text',
  `flag` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0.未发送 1234.发送失败生发超过5删除',
  `pri` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '优先级 0 普通， 1 高 ',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_mail_statistics`
--

DROP TABLE IF EXISTS `svsys_mail_statistics`;
CREATE TABLE IF NOT EXISTS `svsys_mail_statistics` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `mail_date` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '产生日期',
  `type` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '类型',
  `value` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '内容',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  `start_date` datetime NOT NULL COMMENT '开始时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_mail_templates`
--

DROP TABLE IF EXISTS `svsys_mail_templates`;
CREATE TABLE IF NOT EXISTS `svsys_mail_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '邮件模板编号',
  `code` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '编号',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `last_send` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '最后发送时间',
  `type` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '模板类型',
  `user_email_flag` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0:无1：待发送2：已发送',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_mail_template_i18ns`
--

DROP TABLE IF EXISTS `svsys_mail_template_i18ns`;
CREATE TABLE IF NOT EXISTS `svsys_mail_template_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '邮件模板多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `mail_template_id` int(11) NOT NULL DEFAULT '0' COMMENT '邮件模板编号',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '邮件模板名称',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模板说明',
  `text_body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '邮件模板text模板',
  `html_body` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '邮件模板HTML模板',
  `sms_body` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`mail_template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operators`
--

DROP TABLE IF EXISTS `svsys_operators`;
CREATE TABLE IF NOT EXISTS `svsys_operators` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员名称',
  `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员密码',
  `session` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '会话',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员邮件',
  `mobile` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员手机',
  `type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S' COMMENT 'S:系统 D：经销商',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '经销商编号',
  `department_id` int(10) NOT NULL DEFAULT '0' COMMENT '部门ID',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '商店编号[0:系统管理员]',
  `role_id` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '角色编号',
  `actions` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '功能权限',
  `default_lang` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'zh_cn' COMMENT '管理员默认语言',
  `template_code` varchar(50) NOT NULL DEFAULT 'default' COMMENT '操作员模板',
  `desktop` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '桌面设置',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '状态[0:无效;1:有效;2:冻结]',
  `log_flag` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '日记记录标志位(0:无效，1：有效)',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登入时间',
  `last_login_ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '最后登入IP',
  `time_zone` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '-8' COMMENT '时区',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `store_id` (`store_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_actions`
--

DROP TABLE IF EXISTS `svsys_operator_actions`;
CREATE TABLE IF NOT EXISTS `svsys_operator_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '功能编号',
  `level` tinyint(4) NOT NULL DEFAULT '0' COMMENT '功能等级',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父编号',
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '代码',
  `app_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '应用code',
  `section` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'allinone' COMMENT '版本标识',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '状态[0:无效 1:有效]',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `level` (`level`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_action_i18ns`
--

DROP TABLE IF EXISTS `svsys_operator_action_i18ns`;
CREATE TABLE IF NOT EXISTS `svsys_operator_action_i18ns` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `operator_action_id` int(11) NOT NULL DEFAULT '0' COMMENT '功能编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '功能名称',
  `operator_action_values` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '值',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '功能描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时\r\n\r\n\r\n\r\n间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时\r\n\r\n\r\n\r\n间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale_2` (`locale`,`operator_action_id`),
  KEY `locale` (`locale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_logs`
--

DROP TABLE IF EXISTS `svsys_operator_logs`;
CREATE TABLE IF NOT EXISTS `svsys_operator_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志编号',
  `operator_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员编号',
  `session_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ipaddress` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'IP地址',
  `action_url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '访问地址',
  `info` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '备注',
  `remark` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '存放post和get的参数',
  `type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '类型',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `admin_id` (`operator_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_menus`
--

DROP TABLE IF EXISTS `svsys_operator_menus`;
CREATE TABLE IF NOT EXISTS `svsys_operator_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '菜单编号',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级菜单编号',
  `operator_action_code` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '权限代码',
  `type` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '类型',
  `app_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '应用code',
  `link` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '连接地址',
  `level` int(1) NOT NULL DEFAULT '0' COMMENT '等级 1:免费版2:付费版3:权限版',
  `section` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '版本标识',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_menu_i18ns`
--

DROP TABLE IF EXISTS `svsys_operator_menu_i18ns`;
CREATE TABLE IF NOT EXISTS `svsys_operator_menu_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '菜单多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `operator_menu_id` int(11) NOT NULL DEFAULT '0' COMMENT '菜单编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`operator_menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_oauths`
--

DROP TABLE IF EXISTS `svsys_operator_oauths`;
CREATE TABLE IF NOT EXISTS `svsys_operator_oauths` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operator_id` int(11) NOT NULL COMMENT '关联operators表',
  `app_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'web app_key',
  `app_secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'App Secret',
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '用于调用access_token，接口获取授权后的access token。 ',
  `access_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'access_token',
  `uid` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '我的用户id',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0:无效 1:有效',
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `account` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(11) NOT NULL,
  `oauth_token` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `oauth_token_secret` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_roles`
--

DROP TABLE IF EXISTS `svsys_operator_roles`;
CREATE TABLE IF NOT EXISTS `svsys_operator_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员角色编号',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '商店编号',
  `actions` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '功能权限',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '状态[0:无效;1:有效;2:冻结]',
  `orderby` smallint(4) NOT NULL DEFAULT '500' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_operator_role_i18ns`
--

DROP TABLE IF EXISTS `svsys_operator_role_i18ns`;
CREATE TABLE IF NOT EXISTS `svsys_operator_role_i18ns` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `operator_role_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员角色编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `locale` (`locale`),
  KEY `locale_2` (`locale`,`operator_role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_page_actions`
--

DROP TABLE IF EXISTS `svsys_page_actions`;
CREATE TABLE IF NOT EXISTS `svsys_page_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `action` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_type_id` int(11) DEFAULT NULL COMMENT 'page_type表id',
  `layout` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '使用的layout名称',
  `status` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 有效 0 无效',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_page_modules`
--

DROP TABLE IF EXISTS `svsys_page_modules`;
CREATE TABLE IF NOT EXISTS `svsys_page_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '模块ID',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父编号',
  `code` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '模块编码',
  `page_action_id` int(11) DEFAULT NULL COMMENT 'page_action表id',
  `position` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模块位置',
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '模块类型',
  `type_id` int(11) DEFAULT NULL COMMENT '相关信息',
  `model` varchar(55) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '模型名',
  `function` varchar(55) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'get_module_infos' COMMENT '方法名称',
  `parameters` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '参数id',
  `file_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '文件名称',
  `css` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '模块css',
  `width` smallint(5) DEFAULT '0' COMMENT '模块宽度',
  `height` smallint(5) DEFAULT '0' COMMENT '模块高度',
  `float` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '0' COMMENT '浮动 0.正行浮动 1.左浮动 2.右浮动',
  `limit` int(11) DEFAULT '10' COMMENT '取值数量',
  `orderby_type` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '排序方式',
  `orderby` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模块排序方式',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '模块状态',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `page_style_code` (`page_action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_page_module_i18ns`
--

DROP TABLE IF EXISTS `svsys_page_module_i18ns`;
CREATE TABLE IF NOT EXISTS `svsys_page_module_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `module_id` int(11) NOT NULL COMMENT '模块ID',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '模块名称',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模块标题',
  `created` datetime NOT NULL COMMENT '创建时间',
  `modified` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_id` (`module_id`,`locale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_page_types`
--

DROP TABLE IF EXISTS `svsys_page_types`;
CREATE TABLE IF NOT EXISTS `svsys_page_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0:电脑;1:手机',
  `css` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `status` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `remark` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_profiles`
--

DROP TABLE IF EXISTS `svsys_profiles`;
CREATE TABLE IF NOT EXISTS `svsys_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '编码',
  `group` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '档案配置分类',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_profiles_fields`
--

DROP TABLE IF EXISTS `svsys_profiles_fields`;
CREATE TABLE IF NOT EXISTS `svsys_profiles_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品编号',
  `profile_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品编号',
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '代码',
  `format` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '格式',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态[0:无效;1:有效;]',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_profiles_field_i18ns`
--

DROP TABLE IF EXISTS `svsys_profiles_field_i18ns`;
CREATE TABLE IF NOT EXISTS `svsys_profiles_field_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '档案配置字段多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `profiles_field_id` int(11) NOT NULL DEFAULT '0' COMMENT '档案配置字段编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '档案配置字段名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '档案配置字段描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`profiles_field_id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_profile_i18ns`
--

DROP TABLE IF EXISTS `svsys_profile_i18ns`;
CREATE TABLE IF NOT EXISTS `svsys_profile_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '档案配置多语言编号',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '语言编码',
  `profile_id` int(11) NOT NULL DEFAULT '0' COMMENT '档案配置编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '档案配置名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '档案配置描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`profile_id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_resources`
--

DROP TABLE IF EXISTS `svsys_resources`;
CREATE TABLE IF NOT EXISTS `svsys_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '资源上级ID',
  `code` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '资源代码',
  `resource_value` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '资源代码的值',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '1' COMMENT '状态0:无效1:\r\n\r\n有效',
  `section` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '版本标识',
  `orderby` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_resource_i18ns`
--

DROP TABLE IF EXISTS `svsys_resource_i18ns`;
CREATE TABLE IF NOT EXISTS `svsys_resource_i18ns` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增ID',
  `locale` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '语言编码',
  `resource_id` int(11) NOT NULL DEFAULT '0' COMMENT '资源编号',
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '资源名称',
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `locale` (`locale`,`resource_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_routes`
--

DROP TABLE IF EXISTS `svsys_routes`;
CREATE TABLE IF NOT EXISTS `svsys_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `controller` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `model_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '模型id',
  `options` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '参数，以;分割',
  `status` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0无效1有效',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_sessions`
--

DROP TABLE IF EXISTS `svsys_sessions`;
CREATE TABLE IF NOT EXISTS `svsys_sessions` (
  `id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '序列化后的sessionid',
  `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '序列化后的session数据',
  `expires` int(11) DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_sms_send_histories`
--

DROP TABLE IF EXISTS `svsys_sms_send_histories`;
CREATE TABLE IF NOT EXISTS `svsys_sms_send_histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号码',
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '短信内容',
  `send_date` datetime NOT NULL COMMENT '发送时间',
  `flag` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0;未发送',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_sms_send_queues`
--

DROP TABLE IF EXISTS `svsys_sms_send_queues`;
CREATE TABLE IF NOT EXISTS `svsys_sms_send_queues` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号码',
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '短信内容',
  `send_date` datetime NOT NULL COMMENT '发送时间',
  `flag` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '0;未发送',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_sms_words`
--

DROP TABLE IF EXISTS `svsys_sms_words`;
CREATE TABLE IF NOT EXISTS `svsys_sms_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `word` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '敏感字',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `svsys_system_logs`
--

DROP TABLE IF EXISTS `svsys_system_logs`;
CREATE TABLE IF NOT EXISTS `svsys_system_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统日志Id',
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'error' COMMENT '类型',
  `log_text` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '日志描述',
  `created` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '创建时间',
  `modified` datetime NOT NULL DEFAULT '2008-01-01 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统日志表' AUTO_INCREMENT=1 ;