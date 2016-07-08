INSERT INTO `svsys_profiles_fields` (`id`, `profile_id`, `code`, `format`, `orderby`, `status`, `created`, `modified`) VALUES ('689', '83', 'ProfileI18n.locale', 'varchar', '1', '1', '2008-01-01 00:00:00', '2008-01-01 00:00:00');

INSERT INTO `svsys_profiles_field_i18ns` (`id`, `locale`, `profiles_field_id`, `name`, `description`, `created`, `modified`) VALUES ('824', 'chi', '689', '语言(chi:中文,eng:英语)', '语言(chi:中文,eng:英语)', '2008-01-01 00:00:00', '2008-01-01 00:00:00'), ('825', 'eng', '689', 'Locale(chi:Chinese,eng:English)', 'Locale(chi:Chinese,eng:English)', '2008-01-01 00:00:00', '2008-01-01 00:00:00');

INSERT INTO `svsys_operator_menus` (`id`, `parent_id`, `operator_action_code`, `type`, `app_code`, `link`, `level`, `section`, `status`, `orderby`, `created`, `modified`) VALUES ('490', '484', 'open_users_view', '', NULL, '/open_messages/', '0', 'O2O', '1', '4', '2008-01-01 00:00:00', '2008-01-01 00:00:00');

INSERT INTO `svsys_operator_menu_i18ns` (`id`, `locale`, `operator_menu_id`, `name`, `created`, `modified`) VALUES ('845', 'chi', '490', '消息管理', '2008-01-01 00:00:00', '2008-01-01 00:00:00'), ('846', 'eng', '490', 'Message Management', '2008-01-01 00:00:00', '2008-01-01 00:00:00');

UPDATE `svoms_payments` SET `config` = 'a:4:{s:7:"account";s:0:"";s:3:"key";s:0:"";s:7:"partner";s:0:"";s:3:"foo";s:1:"1";}' WHERE `svoms_payments`.`code` ='alipay';
UPDATE `svoms_payments` SET `config` = 'a:4:{s:5:"APPID";s:0:"";s:5:"MCHID";s:0:"";s:3:"KEY";s:0:"";s:9:"APPSECRET";s:0:"";}' WHERE `svoms_payments`.`code` ='weixinpay';

UPDATE `svsys_operator_menus` SET `parent_id` = '8' WHERE `svsys_operator_menus`.`id` =521;


--
-- 转存表中的数据 `svsys_configs`
--

INSERT INTO `svsys_configs` (`id`, `store_id`, `group_code`, `subgroup_code`, `code`, `type`, `readonly`, `section`, `status`, `orderby`, `created`, `modified`) VALUES
(735, 0, 'website', 'website_sms', 'sms-signature', 'text', 0, 'O2O', '1', 50, '2016-02-23 14:06:15', '2016-02-23 14:06:15');


--
-- 转存表中的数据 `svsys_config_i18ns`
--

INSERT INTO `svsys_config_i18ns` (`id`, `locale`, `config_id`, `config_code`, `name`, `default_value`, `value`, `options`, `description`, `param01`, `param02`, `created`, `modified`) VALUES
(3333, 'chi', 735, 'sms-signature', '签名', '', '', '实玮网络', '最大长度限制4位', NULL, NULL, '2016-02-23 14:06:15', '2016-02-23 14:06:40'),
(3334, 'eng', 735, 'sms-signature', 'Signature', '', '', '实玮网络', 'The maximum length limit 4', NULL, NULL, '2016-02-23 14:06:15', '2016-02-23 14:06:40');

--
-- 转存表中的数据 `svsys_resources`
--

INSERT INTO `svsys_resources` (`id`, `parent_id`, `code`, `resource_value`, `status`, `section`, `orderby`, `created`, `modified`) VALUES
(550, 246, '', 'article_template1', '0', '免费版', 1, '2016-03-16 09:14:04', '2016-03-16 09:14:04'),
(551, 246, '', 'article_template2', '0', '免费版', 2, '2016-03-16 09:23:06', '2016-03-16 09:23:06'),
(552, 246, '', 'article_template3', '0', '免费版', 3, '2016-03-16 09:24:02', '2016-03-16 09:24:02'),
(553, 246, '', 'article_template4', '0', '免费版', 4, '2016-03-16 09:25:05', '2016-03-16 09:25:05');

--
-- 转存表中的数据 `svsys_resource_i18ns`
--

INSERT INTO `svsys_resource_i18ns` (`id`, `locale`, `resource_id`, `name`, `description`, `created`, `modified`) VALUES
(932, 'chi', 553, '文章模板4', '', '2016-03-16 09:25:05', '2016-03-16 09:25:05'),
(933, 'eng', 553, 'Article Tempate4', '', '2016-03-16 09:25:05', '2016-03-16 09:25:05'),
(930, 'chi', 552, '文章模板3', '', '2016-03-16 09:24:02', '2016-03-16 09:24:02'),
(931, 'eng', 552, 'Article Tempate3', '', '2016-03-16 09:24:02', '2016-03-16 09:24:02'),
(928, 'chi', 551, '文章模板2', '', '2016-03-16 09:23:06', '2016-03-16 09:23:06'),
(929, 'eng', 551, 'Article Tempate2', '', '2016-03-16 09:23:06', '2016-03-16 09:23:06'),
(926, 'chi', 550, '文章模板1', '', '2016-03-16 09:14:04', '2016-03-16 09:14:04'),
(927, 'eng', 550, 'Article Tempate1', '', '2016-03-16 09:14:04', '2016-03-16 09:14:04');


--
-- 转存表中的数据 `svsys_profiles_fields`
--

INSERT INTO `svsys_profiles_fields` (`id`, `profile_id`, `code`, `format`, `orderby`, `status`, `created`, `modified`) VALUES
(735, 5, 'Product.product_image2', 'varchar', 19, '1', '2016-03-20 14:44:36', '2016-03-20 14:44:36'),
(734, 5, 'Product.product_image1', 'varchar', 19, '1', '2016-03-20 14:42:43', '2016-03-20 14:42:43');


--
-- 转存表中的数据 `svsys_profiles_field_i18ns`
--

INSERT INTO `svsys_profiles_field_i18ns` (`id`, `locale`, `profiles_field_id`, `name`, `description`, `created`, `modified`) VALUES
(922, 'chi', 734, '原比例小图', '原比例小图', '2016-03-20 14:42:43', '2016-03-20 14:42:43'),
(923, 'eng', 734, 'Aspect thumbnail', 'Aspect thumbnail', '2016-03-20 14:42:43', '2016-03-20 14:42:43'),
(924, 'chi', 735, '原比例大图', '原比例大图', '2016-03-20 14:44:36', '2016-03-20 14:44:36'),
(925, 'eng', 735, 'Aspect enlarge', 'Aspect enlarge', '2016-03-20 14:44:36', '2016-03-20 14:44:36');

--
-- 转存表中的数据 `svsys_resources`
--

INSERT INTO `svsys_resources` (`id`, `parent_id`, `code`, `resource_value`, `status`, `section`, `orderby`, `created`, `modified`) VALUES
(554, 516, '', '1', '1', '免费版', 1, '2016-03-17 13:05:56', '2016-03-17 13:05:56'),
(555, 516, '', '2', '1', '免费版', 2, '2016-03-17 13:06:13', '2016-03-17 13:06:13');

--
-- 转存表中的数据 `svsys_resource_i18ns`
--

INSERT INTO `svsys_resource_i18ns` (`id`, `locale`, `resource_id`, `name`, `description`, `created`, `modified`) VALUES
(934, 'chi', 554, '预约', '', '2016-03-17 13:05:56', '2016-03-17 13:05:56'),
(935, 'chi', 555, '报名', '', '2016-03-17 13:06:13', '2016-03-17 13:06:13');

--
-- 转存表中的数据 `svsys_resources`
--

INSERT INTO `svsys_resources` (`id`, `parent_id`, `code`, `resource_value`, `status`, `section`, `orderby`, `created`, `modified`) VALUES
(556, 0, 'article_category_template', '', '1', '免费版', 50, '2016-03-21 13:38:19', '2016-03-21 13:38:19'),
(557, 556, '', 'text_list', '1', '免费版', 1, '2016-03-21 13:40:59', '2016-03-21 13:40:59'),
(558, 556, '', 'photo_list', '1', '免费版', 2, '2016-03-21 13:41:50', '2016-03-21 13:41:50');

--
-- 转存表中的数据 `svsys_resource_i18ns`
--

INSERT INTO `svsys_resource_i18ns` (`id`, `locale`, `resource_id`, `name`, `description`, `created`, `modified`) VALUES
(936, 'chi', 556, '文章分类页模板', '', '2016-03-21 13:38:19', '2016-03-21 13:38:19'),
(937, 'eng', 556, 'Article Category Tempate', '', '2016-03-21 13:38:19', '2016-03-21 13:38:19'),
(938, 'chi', 557, '文本列表', '', '2016-03-21 13:40:59', '2016-03-21 13:40:59'),
(939, 'eng', 557, 'Text List', '', '2016-03-21 13:40:59', '2016-03-21 13:40:59'),
(940, 'chi', 558, '图文列表', '', '2016-03-21 13:41:50', '2016-03-21 13:41:50'),
(941, 'eng', 558, 'Photo List', '', '2016-03-21 13:41:50', '2016-03-21 13:41:50');


--
-- 转存表中的数据 `svsys_resources`
--

INSERT INTO `svsys_resources` (`id`, `parent_id`, `code`, `resource_value`, `status`, `section`, `orderby`, `created`, `modified`) VALUES
(559, 556, '', 'advisory_list', '1', '免费版', 2, '2016-03-21 13:41:50', '2016-03-21 13:41:50');

--
-- 转存表中的数据 `svsys_resource_i18ns`
--

INSERT INTO `svsys_resource_i18ns` (`id`, `locale`, `resource_id`, `name`, `description`, `created`, `modified`) VALUES
(942, 'chi', 559, '咨询列表', '', '2016-03-21 13:41:50', '2016-03-21 13:41:50'),
(943, 'eng', 559, 'Advisory List', '', '2016-03-21 13:41:50', '2016-03-21 13:41:50');


--
-- 转存表中的数据 `svsys_configs`
--

INSERT INTO `svsys_configs` (`id`, `store_id`, `group_code`, `subgroup_code`, `code`, `type`, `readonly`, `section`, `status`, `orderby`, `created`, `modified`) VALUES
(737, 0, 'user', 'user_advance', 'user_register_mode', 'radio', 0, 'O2O', '1', 1, '2016-03-21 16:31:05', '2016-03-21 16:31:05');

--
-- 转存表中的数据 `svsys_config_i18ns`
--

INSERT INTO `svsys_config_i18ns` (`id`, `locale`, `config_id`, `config_code`, `name`, `default_value`, `value`, `options`, `description`, `param01`, `param02`, `created`, `modified`) VALUES
(3337, 'chi', 737, 'user_register_mode', '会员注册方式', '0', '1', '0:邮箱\r\n1:手机', '', NULL, NULL, '2016-03-21 16:31:05', '2016-03-21 16:31:05'),
(3338, 'eng', 737, 'user_register_mode', 'User Registration Information', '0', '1', '0:Email\r\n1:Mobile', '', NULL, NULL, '2016-03-21 16:31:05', '2016-03-21 16:31:05');
