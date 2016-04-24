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
  `model_name` varchar(255) NOT NULL,
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
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` int(11) NOT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `deleted_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
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
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
