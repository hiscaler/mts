-- --------------------------------------------------------
-- 主机:                           127.0.0.1
-- 服务器版本:                        5.6.12 - MySQL Community Server (GPL)
-- 服务器操作系统:                      Win64
-- HeidiSQL 版本:                  9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 导出  表 mts.mts_ad 结构
CREATE TABLE IF NOT EXISTS `mts_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `space_id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `url` varchar(200) NOT NULL,
  `type` smallint(6) NOT NULL,
  `file_path` varchar(100) DEFAULT NULL,
  `text` text,
  `begin_datetime` int(11) NOT NULL,
  `end_datetime` int(11) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `views_count` int(11) NOT NULL DEFAULT '0',
  `clicks_count` int(11) NOT NULL DEFAULT '0',
  `status` smallint(6) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_ad_space 结构
CREATE TABLE IF NOT EXISTS `mts_ad_space` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` smallint(6) NOT NULL DEFAULT '0',
  `alias` varchar(60) NOT NULL,
  `name` varchar(30) NOT NULL,
  `width` smallint(6) NOT NULL,
  `height` smallint(6) NOT NULL,
  `description` varchar(255) NOT NULL,
  `ads_count` int(11) NOT NULL DEFAULT '0',
  `status` smallint(6) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `tenant_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_archive 结构
CREATE TABLE IF NOT EXISTS `mts_archive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_id` int(11) NOT NULL,
  `model_name` varchar(30) NOT NULL,
  `title` varchar(255) NOT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` text,
  `tags` varchar(200) DEFAULT NULL,
  `has_thumbnail` tinyint(1) NOT NULL DEFAULT '0',
  `thumbnail` varchar(100) DEFAULT NULL,
  `author` varchar(20) NOT NULL,
  `source` varchar(30) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `published_datetime` int(11) NOT NULL,
  `clicks_count` int(11) NOT NULL DEFAULT '0',
  `enabled_comment` tinyint(1) NOT NULL DEFAULT '0',
  `comments_count` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `tenant_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_archive_content 结构
CREATE TABLE IF NOT EXISTS `mts_archive_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `archive_id` int(11) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_archive_label 结构
CREATE TABLE IF NOT EXISTS `mts_archive_label` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `archive_id` int(11) NOT NULL,
  `model_name` varchar(60) NOT NULL,
  `label_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_article 结构
CREATE TABLE IF NOT EXISTS `mts_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` text,
  `content` text NOT NULL,
  `picture_path` varchar(100) DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `status` smallint(6) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `tenant_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_file_upload_config 结构
CREATE TABLE IF NOT EXISTS `mts_file_upload_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `model_name` varchar(30) NOT NULL,
  `attribute` varchar(30) NOT NULL,
  `extensions` varchar(30) NOT NULL,
  `min_size` smallint(6) NOT NULL DEFAULT '0',
  `max_size` smallint(6) NOT NULL DEFAULT '0',
  `thumb_width` smallint(6) DEFAULT '0',
  `thumb_height` smallint(6) DEFAULT '0',
  `tenant_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_friendly_link 结构
CREATE TABLE IF NOT EXISTS `mts_friendly_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` smallint(6) NOT NULL DEFAULT '0',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `url` varchar(160) NOT NULL,
  `url_open_target` varchar(6) NOT NULL,
  `logo_path` varchar(100) DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `status` smallint(6) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `tenant_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_grid_column_config 结构
CREATE TABLE IF NOT EXISTS `mts_grid_column_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `attribute` varchar(30) NOT NULL,
  `css_class` varchar(255) DEFAULT NULL,
  `css_style` varchar(255) DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `user_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_group_option 结构
CREATE TABLE IF NOT EXISTS `mts_group_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(30) NOT NULL,
  `text` varchar(30) NOT NULL,
  `value` varchar(30) NOT NULL,
  `alias` varchar(30) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `defaulted` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` smallint(6) NOT NULL DEFAULT '0',
  `description` varchar(100) DEFAULT NULL,
  `tenant_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_label 结构
CREATE TABLE IF NOT EXISTS `mts_label` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `frequency` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `tenant_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_lookup 结构
CREATE TABLE IF NOT EXISTS `mts_lookup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(30) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `value` text NOT NULL,
  `return_type` smallint(6) NOT NULL DEFAULT '1',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `tenant_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_migration 结构
CREATE TABLE IF NOT EXISTS `mts_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_node 结构
CREATE TABLE IF NOT EXISTS `mts_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `model_name` varchar(60) NOT NULL,
  `parameters` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `parent_ids` varchar(255) DEFAULT NULL,
  `parent_names` varchar(255) DEFAULT NULL,
  `level` smallint(6) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `direct_data_count` int(11) NOT NULL DEFAULT '0',
  `relation_data_count` int(11) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `entity_status` smallint(6) NOT NULL DEFAULT '1',
  `entity_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `tenant_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_node_closure 结构
CREATE TABLE IF NOT EXISTS `mts_node_closure` (
  `parent_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_slide 结构
CREATE TABLE IF NOT EXISTS `mts_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` smallint(6) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `url` varchar(200) NOT NULL,
  `url_open_target` varchar(6) NOT NULL DEFAULT '_blank',
  `picture` varchar(100) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `status` smallint(6) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `tenant_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_tenant 结构
CREATE TABLE IF NOT EXISTS `mts_tenant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `language` varchar(10) NOT NULL,
  `timezone` varchar(20) NOT NULL,
  `date_format` varchar(20) NOT NULL,
  `time_format` varchar(20) NOT NULL,
  `datetime_format` varchar(20) NOT NULL,
  `domain_name` varchar(100) NOT NULL,
  `description` text,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_tenant_module 结构
CREATE TABLE IF NOT EXISTS `mts_tenant_module` (
  `tenant_id` int(11) NOT NULL,
  `module_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_tenant_user 结构
CREATE TABLE IF NOT EXISTS `mts_tenant_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` smallint(6) NOT NULL DEFAULT '0',
  `rule_id` int(11) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `user_group_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_tenant_user_group 结构
CREATE TABLE IF NOT EXISTS `mts_tenant_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `tenant_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_user 结构
CREATE TABLE IF NOT EXISTS `mts_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(5) NOT NULL DEFAULT '1',
  `username` varchar(20) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(60) NOT NULL,
  `password_reset_token` varchar(60) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `status` smallint(5) NOT NULL DEFAULT '0',
  `register_ip` int(11) NOT NULL,
  `login_count` int(11) NOT NULL DEFAULT '0',
  `last_login_ip` int(11) DEFAULT NULL,
  `last_login_datetime` int(11) DEFAULT NULL,
  `last_change_password_time` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` int(11) NOT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_user_auth_node 结构
CREATE TABLE IF NOT EXISTS `mts_user_auth_node` (
  `user_id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_user_login_log 结构
CREATE TABLE IF NOT EXISTS `mts_user_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `login_ip` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `client_informations` varchar(255) NOT NULL,
  `login_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.mts_workflow_rule 结构
CREATE TABLE IF NOT EXISTS `mts_workflow_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `tenant_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。


-- 导出  表 mts.workflow_definition_table 结构
CREATE TABLE IF NOT EXISTS `workflow_definition_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule_id` int(11) NOT NULL,
  `ordering` smallint(6) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 数据导出被取消选择。
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
