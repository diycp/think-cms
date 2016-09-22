-- Created at 22.9.2016 17:57 using David Grudl MySQL Dump Utility
-- Host: cms.wxhand.com
-- MySQL Server: 5.5.40
-- Database: cms

SET NAMES utf8;
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
SET FOREIGN_KEY_CHECKS=0;
-- --------------------------------------------------------

DROP TABLE IF EXISTS `nd_manage_action_log`;

CREATE TABLE `nd_manage_action_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_uid` int(11) NOT NULL COMMENT '用户ID',
  `log_method` varchar(15) NOT NULL COMMENT '请求类型',
  `log_module` varchar(20) NOT NULL COMMENT '模块',
  `log_controller` varchar(40) NOT NULL COMMENT '控制器',
  `log_action` varchar(60) NOT NULL COMMENT '操作',
  `log_data` text NOT NULL,
  `log_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `log_uid` (`log_uid`) USING BTREE,
  KEY `log_method` (`log_method`),
  KEY `log_module` (`log_module`),
  KEY `log_controller` (`log_controller`),
  KEY `log_action` (`log_action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `nd_manage_config`;

CREATE TABLE `nd_manage_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_name` varchar(30) NOT NULL COMMENT '配置名称',
  `config_value` varchar(1000) NOT NULL DEFAULT '' COMMENT '配置值',
  `config_type` varchar(10) NOT NULL COMMENT '配置类型',
  `config_title` varchar(30) NOT NULL DEFAULT '' COMMENT '配置标题',
  `config_group` varchar(10) NOT NULL DEFAULT '' COMMENT '配置分组',
  `config_extra` text NOT NULL COMMENT '额外配置',
  `config_sort` int(11) NOT NULL DEFAULT '0' COMMENT '配置排序',
  `config_remark` varchar(150) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `config_name` (`config_name`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

INSERT INTO `nd_manage_config` (`id`, `config_name`, `config_value`, `config_type`, `config_title`, `config_group`, `config_extra`, `config_sort`, `config_remark`, `create_time`, `update_time`) VALUES
(1,	'app_debug',	'0',	'radio',	'调试模式',	'核心',	'0:关闭|1:开启',	1,	'',	1474430754,	1474506766),
(2,	'app_trace',	'0',	'radio',	'应用Trace',	'核心',	'0:关闭|1:开启',	2,	'',	1474435997,	1474506766),
(3,	'url_convert',	'0',	'radio',	'转换URL',	'核心',	'0:不转换|1:转换',	3,	'',	1474430370,	1474506766),
(4,	'url_route_on',	'1',	'radio',	'开启路由',	'核心',	'0:关闭|1:开启',	4,	'',	1474435482,	1474506766),
(5,	'url_route_must',	'0',	'radio',	'强制路由',	'核心',	'0:不强制|1:强制使用',	5,	'',	1474435583,	1474506766),
(6,	'url_domain_deploy',	'1',	'radio',	'域名部署',	'核心',	'0:否|1:是',	6,	'',	1474430531,	1474506766),
(7,	'url_domain_root',	'cms.newday.me',	'text',	'网站域名',	'核心',	'',	7,	'',	1474430459,	1474506766),
(8,	'log',	'{\"type\":\"File\",\"path\":\"{LOG_PATH}\",\"level\":\"\"}',	'array',	'日志设置',	'核心',	'type,path,level',	8,	'',	1474435664,	1474506766),
(9,	'trace',	'{\"type\":\"Html\"}',	'array',	'Trace设置',	'核心',	'type',	9,	'',	1474435855,	1474506766),
(10,	'cache',	'{\"type\":\"File\",\"path\":\"{CACHE_PATH}\",\"prefix\":\"\",\"expire\":0}',	'array',	'缓存设置',	'核心',	'type,path,prefix,expire',	10,	'',	1474435908,	1474506766),
(11,	'session',	'{\"id\":\"\",\"var_session_id\":\"\",\"prefix\":\"think\",\"type\":\"\",\"auto_start\":1}',	'array',	'会话设置',	'核心',	'id,var_session_id,prefix,type,auto_start',	11,	'',	1474462820,	1474506766),
(12,	'cookie',	'{\"prefix\":\"\",\"expire\":0,\"path\":\"\\/\",\"domain\":\"\",\"secure\":0,\"httponly\":\"\",\"setcookie\":1}',	'array',	'Cookie设置',	'核心',	'prefix,expire,path,domain,secure,httponly,setcookie',	12,	'',	1474465033,	1474506766),
(13,	'paginate',	'{\"type\":\"bootstrap\",\"var_page\":\"page\",\"list_rows\":15}',	'array',	'分页配置',	'核心',	'type,var_page,list_rows',	13,	'',	1474465205,	1474506766),
(14,	'log_level_manage',	'0',	'select',	'日志等级',	'后台',	'0:不记录|1:ajax、post方法，有提交数据|2:ajax、post方法|3:有提交数据|4:记录所有',	51,	'',	1474429684,	1474537762),
(15,	'login_driver_manage',	'\\app\\common\\driver\\login\\CookieLogin',	'radio',	'登录驱动',	'后台',	'\\app\\common\\driver\\login\\CookieLogin:Cookie驱动|\\app\\common\\driver\\login\\SessionLogin:Session驱动',	52,	'',	1474421089,	1474537762),
(16,	'public_action_manage',	'[\"manage.start.*\",\"index.index.*\"]',	'array',	'公共行为',	'后台',	'',	53,	'',	1474434515,	1474537762),
(17,	'site_title',	'NewdayCms - 哩呵后台管理系统',	'text',	'网站标题',	'网站',	'',	100,	'',	1469798091,	1474534155),
(18,	'site_version',	'20160921',	'text',	'网站版本',	'网站',	'',	101,	'',	1474127435,	1474534155),
(19,	'site_compress',	'1',	'radio',	'HTML压缩',	'网站',	'0:不压缩|1:压缩',	102,	'',	1474174078,	1474534155),
(20,	'site_base',	'/',	'text',	'网站目录',	'网站',	'',	103,	'',	1474429365,	1474534155),
(21,	'site_keyword',	'哩呵,CMS,ThinkPHP,后台,管理系统',	'textarea',	'网站关键字',	'网站',	'',	104,	'',	1474176263,	1474534155),
(22,	'site_description',	'NewdayCms ，简单的方式管理数据。期待你的参与，共同打造一个功能更强大的通用后台管理系统。',	'textarea',	'网站描述',	'网站',	'',	105,	'',	1474176302,	1474534155),
(23,	'bakup_path',	'{ROOT_PATH}bakup',	'text',	'备份路径',	'网站',	'',	106,	'',	1474468252,	1474534155),
(24,	'upload_driver',	'\\app\\common\\driver\\upload\\LocalUpload',	'radio',	'上传驱动',	'上传',	'\\app\\common\\driver\\upload\\UpyunUpload:又拍云|\\app\\common\\driver\\upload\\LocalUpload:本地',	200,	'',	1474174215,	1474476452),
(25,	'upload_type',	'{\"image\":[\"jpg\",\"png\",\"gif\",\"bmp\"],\"audio\":[\"mp3\",\"ogg\",\"m4a\",\"wav\",\"ape\",\"flac\"],\"video\":[\"mp4\",\"mov\",\"mpg\",\"flv\",\"mkv\",\"avi\"],\"compress\":[\"zip\",\"rar\",\"7z\"],\"document\":[\"doc\",\"xls\",\"ppt\",\"docx\",\"xlsx\",\"pptx\",\"pdf\"]}',	'array',	'上传类型',	'上传',	'image,audio,video,compress,document',	201,	'',	1474174295,	1474476452),
(26,	'local_config',	'{\"local_root\":\"{WEB_PATH}\\/upload\\/\",\"local_url\":\"http:\\/\\/cms.newday.me\\/upload\\/\"}',	'array',	'本地配置',	'上传',	'local_root,local_url',	202,	'',	1474174580,	1474476452),
(27,	'upyun_config',	'{\r\n    \"upyun_bucket\": \"newday-static\",\r\n    \"upyun_user\": \"\",\r\n    \"upyun_pass\": \"\",\r\n    \"upyun_root\": \"/upload/\",\r\n    \"upyun_url\": \"http://static.newday.me/upload/\",\r\n    \"upyun_key\": \"\",\r\n    \"upyun_size\": 2,\r\n    \"upyun_maxsize\": 3,\r\n    \"upyun_return\": \"\",\r\n    \"upyun_notify\": \"\"\r\n}',	'array',	'又拍云配置',	'上传',	'upyun_bucket,upyun_user,upyun_pass,upyun_root,upyun_url,upyun_key,upyun_size,upyun_maxsize,upyun_return,upyun_notify',	203,	'',	1474174375,	1474476452),
(28,	'verify_code_manage',	'0',	'radio',	'开启验证码',	'后台',	'0:关闭|1:开启',	50,	'',	1474537662,	1474537762);


-- --------------------------------------------------------

DROP TABLE IF EXISTS `nd_manage_database`;

CREATE TABLE `nd_manage_database` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dump_uid` int(11) NOT NULL COMMENT '用户ID',
  `dump_size` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `dump_file` varchar(150) NOT NULL COMMENT '导出文件',
  `dump_time` int(11) NOT NULL DEFAULT '0' COMMENT '导出时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `nd_manage_file`;

CREATE TABLE `nd_manage_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_hash` varchar(32) NOT NULL COMMENT '文件哈希',
  `file_type` varchar(10) NOT NULL DEFAULT '' COMMENT '文件类型',
  `file_size` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `file_url` varchar(100) NOT NULL DEFAULT '' COMMENT '文件链接',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_hash` (`file_hash`) USING BTREE,
  KEY `file_type` (`file_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

DROP TABLE IF EXISTS `nd_manage_member`;

CREATE TABLE `nd_manage_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `user_name` char(16) NOT NULL COMMENT '用户名',
  `user_passwd` char(32) NOT NULL COMMENT '密码',
  `user_nick` varchar(150) NOT NULL DEFAULT '',
  `group_id` int(11) NOT NULL COMMENT '所属分组',
  `user_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户状态',
  `login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `login_ip` varchar(20) NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `login_count` int(11) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`user_name`),
  KEY `status` (`user_status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户表';

INSERT INTO `nd_manage_member` (`id`, `user_name`, `user_passwd`, `user_nick`, `group_id`, `user_status`, `login_time`, `login_ip`, `login_count`, `create_time`, `update_time`) VALUES
(1,	'admin',	'88508ca442ed2483821f46190e86b2b1',	'管理员',	1,	1,	1474538268,	'127.0.0.1',	8,	1469798091,	1474538268),
(2,	'demo',	'88508ca442ed2483821f46190e86b2b1',	'游客',	3,	1,	1474538234,	'127.0.0.1',	3,	1474475386,	1474538234);


-- --------------------------------------------------------

DROP TABLE IF EXISTS `nd_manage_member_group`;

CREATE TABLE `nd_manage_member_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `group_name` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `group_info` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `home_page` varchar(150) NOT NULL DEFAULT '' COMMENT '登录首页',
  `group_menus` varchar(1000) NOT NULL DEFAULT '' COMMENT '用户组允许访问的菜单',
  `group_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `nd_manage_member_group` (`id`, `group_name`, `group_info`, `home_page`, `group_menus`, `group_status`, `create_time`, `update_time`) VALUES
(1,	'管理员',	'管理网站',	'manage/index/index',	'1,2,13,39,3,4,14,15,16,17,5,18,19,20,21,22,6,7,23,24,25,26,27,8,28,29,30,31,32,9,33,10,34,11,35,36,37,12,38',	1,	1473874915,	1474476957),
(2,	'站长',	'管理内容',	'manage/index/index',	'1,2,13,39,3,4,5,6,7,8,9,33,10,11,35,36,37,12',	1,	1473874915,	1474476974),
(3,	'游客',	'游客',	'manage/index/index',	'1,2,13,3,4,5,6,7,8,9,10,11,12',	1,	1474476991,	1474477002);


-- --------------------------------------------------------

DROP TABLE IF EXISTS `nd_manage_menu`;

CREATE TABLE `nd_manage_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `menu_name` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `menu_pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `menu_sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `menu_url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `menu_group` varchar(50) NOT NULL DEFAULT '' COMMENT '分组',
  `menu_flag` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单标识',
  `menu_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`menu_pid`),
  KEY `status` (`menu_status`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

INSERT INTO `nd_manage_menu` (`id`, `menu_name`, `menu_pid`, `menu_sort`, `menu_url`, `menu_group`, `menu_flag`, `menu_status`, `create_time`, `update_time`) VALUES
(1,	'系统',	0,	0,	'',	'',	'manage/index/index',	1,	1473913119,	1474137726),
(2,	'控制台',	1,	0,	'manage/index/index',	'网站',	'manage/index/index',	1,	1473913119,	1474137908),
(3,	'网站设置',	1,	1,	'manage/config/setting',	'网站',	'manage/config/setting',	1,	1474135187,	1474137743),
(4,	'配置管理',	1,	2,	'manage/config/index',	'网站',	'manage/config/index',	1,	1474122400,	1474137745),
(5,	'菜单管理',	1,	3,	'manage/menu/index',	'网站',	'manage/menu/index',	1,	1473913119,	1474219246),
(6,	'操作日志',	1,	4,	'manage/actionLog/index',	'网站',	'manage/actionLog/index',	1,	1473926393,	1474219248),
(7,	'用户群组',	1,	10,	'manage/memberGroup/index',	'用户',	'manage/memberGroup/index',	1,	1473913119,	1474137479),
(8,	'用户管理',	1,	11,	'manage/member/index',	'用户',	'manage/member/index',	1,	1473913119,	1474137485),
(9,	'上传文件',	1,	40,	'manage/file/upload',	'文件',	'manage/file/upload',	1,	1474218531,	1474218595),
(10,	'附件管理',	1,	41,	'manage/file/index',	'文件',	'manage/file/index',	1,	1474218590,	1474218600),
(11,	'数据备份',	1,	50,	'manage/database/index',	'数据库',	'manage/database/index',	1,	1473956210,	1474218550),
(12,	'备份记录',	1,	51,	'manage/database/bakupLog',	'数据库',	'manage/database/bakupLog',	1,	1473959958,	1474218554),
(13,	'账户设置',	2,	0,	'manage/index/account',	'',	'manage/index/account',	1,	1473918347,	1473918347),
(14,	'添加配置',	4,	0,	'manage/config/addConfig',	'',	'manage/config/addConfig',	1,	1474126097,	1474126097),
(15,	'编辑配置',	4,	0,	'manage/config/editConfig',	'',	'manage/config/editConfig',	1,	1474126112,	1474126112),
(16,	'更改配置',	4,	0,	'manage/config/modifyConfig',	'',	'manage/config/modifyConfig',	1,	1474126150,	1474126150),
(17,	'删除配置',	4,	0,	'manage/config/delConfig',	'',	'manage/config/delConfig',	1,	1474126175,	1474126175),
(18,	'新增菜单',	5,	0,	'manage/menu/addMenu',	'',	'manage/menu/addMenu',	1,	1473913119,	1473919027),
(19,	'编辑菜单',	5,	0,	'manage/menu/editMenu',	'',	'manage/menu/editMenu',	1,	1473913119,	1473919027),
(20,	'删除菜单',	5,	0,	'manage/menu/delMenu',	'',	'manage/menu/delMenu',	1,	1473913119,	1473919027),
(21,	'更改排序',	5,	0,	'manage/menu/modifySort',	'',	'manage/menu/modifySort',	1,	1473913119,	1473913322),
(22,	'更改状态',	5,	0,	'manage/menu/modifyStatus',	'',	'manage/menu/modifyStatus',	1,	1473913119,	1473913316),
(23,	'添加群组',	7,	0,	'manage/memberGroup/addGroup',	'',	'manage/memberGroup/addGroup',	1,	1473913119,	1473919027),
(24,	'编辑群组',	7,	0,	'manage/memberGroup/editGroup',	'',	'manage/memberGroup/editGroup',	1,	1473913119,	1473913068),
(25,	'群组权限',	7,	0,	'manage/memberGroup/editAuth',	'',	'manage/memberGroup/editAuth',	1,	1473913119,	1473913148),
(26,	'删除群组',	7,	0,	'manage/memberGroup/delGroup',	'',	'manage/memberGroup/delGroup',	1,	1473913135,	1473913135),
(27,	'更改状态',	7,	0,	'manage/memberGroup/modifyStatus',	'',	'manage/memberGroup/modifyStatus',	1,	1473913166,	1473913297),
(28,	'添加用户',	8,	0,	'manage/member/addMember',	'',	'manage/member/addMember',	1,	1473913204,	1473913204),
(29,	'编辑用户',	8,	0,	'manage/member/editMember',	'',	'manage/member/editMember',	1,	1473913215,	1473913215),
(30,	'删除用户',	8,	0,	'manage/member/delMember',	'',	'manage/member/delMember',	1,	1473913245,	1473913245),
(31,	'更改群组',	8,	0,	'manage/member/modifyGroup',	'',	'manage/member/modifyGroup',	1,	1473913260,	1473913260),
(32,	'更改状态',	8,	0,	'manage/member/modifyStatus',	'',	'manage/member/modifyStatus',	1,	1473913278,	1473913278),
(33,	'文件上传',	9,	0,	'admin/upload/upload',	'',	'http:/cms',	1,	1474469128,	1474469128),
(34,	'删除附件',	10,	0,	'manage/file/delFile',	'',	'manage/file/delFile',	1,	1474392650,	1474392650),
(35,	'备份数据库',	11,	0,	'manage/database/bakup',	'',	'manage/database/bakup',	1,	1473962050,	1473962050),
(36,	'优化数据库',	11,	0,	'manage/database/optimize',	'',	'manage/database/optimize',	1,	1473962107,	1474392764),
(37,	'修复数据库',	11,	0,	'manage/database/repair',	'',	'manage/database/repair',	1,	1473962139,	1474392770),
(38,	'删除记录',	12,	0,	'manage/database/delBakup',	'',	'manage/database/delBakup',	1,	1473962185,	1473962185),
(39,	'保存账号',	2,	0,	'manage/index/saveAccount',	'',	'http:/cms',	1,	1474476155,	1474476155);


-- THE END
