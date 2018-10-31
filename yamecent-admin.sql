-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-10-31 10:54:53
-- 服务器版本： 5.7.18-log
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yamecent-admin`
--

-- --------------------------------------------------------

--
-- 表的结构 `v_admin_config`
--

CREATE TABLE `v_admin_config` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `config_key` varchar(255) DEFAULT NULL,
  `config_value` text,
  `type` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `v_admin_menu`
--

CREATE TABLE `v_admin_menu` (
  `id` int(11) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `url` varchar(50) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '权重',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `v_admin_menu`
--

INSERT INTO `v_admin_menu` (`id`, `pid`, `name`, `url`, `icon`, `sort`, `created_at`, `updated_at`) VALUES
(8, 7, '视频审核列表', '/admin/video', NULL, 0, '2018-10-30 08:40:49', '2018-10-30 08:46:30'),
(9, 7, '回调地址管理', 'set_callback_url', NULL, 0, '2018-10-31 00:48:16', '2018-10-31 01:44:22'),
(10, 7, '转码模板管理', 'transcoding', 'mdi-autorenew', 0, '2018-10-31 01:01:49', '2018-10-31 01:01:49'),
(11, 7, '水印模板管理', '/watermark', 'mdi-watermark', 0, '2018-10-31 01:03:11', '2018-10-31 01:03:11');

-- --------------------------------------------------------

--
-- 表的结构 `v_admin_permission`
--

CREATE TABLE `v_admin_permission` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `route` varchar(1000) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `v_admin_permission`
--

INSERT INTO `v_admin_permission` (`id`, `name`, `route`, `created_at`, `updated_at`) VALUES
(3, '所有权限', 'admin/menu/list,admin/menu/add,admin/menu/update/{id},admin/menu/del/{id},admin/role/list,admin/permission/list,admin/permission/add,api/set_callback_url,admin/role/add,admin/role/update/{id},admin/role/del/{id},admin/permission/update/{id},admin/permission/del/{id},admin/administrator/list,admin/administrator/add,admin/administrator/update/{id},admin/administrator/del/{id},admin/upload,admin/403,login,set_callback_url,/,console,403,edit/info/{id},logout', '2018-10-30 03:13:20', '2018-10-30 08:18:53'),
(5, '视频审核员', 'logout,login,edit/info/{id},admin/upload,/,403,console', '2018-10-30 08:45:37', '2018-10-31 01:07:47');

-- --------------------------------------------------------

--
-- 表的结构 `v_admin_role`
--

CREATE TABLE `v_admin_role` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `des` varchar(100) DEFAULT NULL COMMENT '描述',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `v_admin_role`
--

INSERT INTO `v_admin_role` (`id`, `name`, `des`, `created_at`, `updated_at`) VALUES
(1, '超级管理员', '系统最高权限', '2018-10-30 03:43:03', '2018-10-30 07:25:03'),
(3, '视频审核员', '审核视频', '2018-10-30 08:46:10', '2018-10-30 08:46:10');

-- --------------------------------------------------------

--
-- 表的结构 `v_admin_role_menu`
--

CREATE TABLE `v_admin_role_menu` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='菜单-权限关系表';

--
-- 转存表中的数据 `v_admin_role_menu`
--

INSERT INTO `v_admin_role_menu` (`id`, `role_id`, `menu_id`) VALUES
(14, 1, 8),
(15, 3, 8),
(18, 1, 10),
(19, 1, 11),
(21, 1, 9);

-- --------------------------------------------------------

--
-- 表的结构 `v_admin_role_permission`
--

CREATE TABLE `v_admin_role_permission` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色权限关系表';

--
-- 转存表中的数据 `v_admin_role_permission`
--

INSERT INTO `v_admin_role_permission` (`id`, `role_id`, `permission_id`) VALUES
(3, 2, 3),
(4, 2, 4),
(5, 1, 3),
(6, 3, 5);

-- --------------------------------------------------------

--
-- 表的结构 `v_admin_user`
--

CREATE TABLE `v_admin_user` (
  `id` int(11) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `account` varchar(30) NOT NULL,
  `password` varchar(500) NOT NULL,
  `clear_password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员表';

--
-- 转存表中的数据 `v_admin_user`
--

INSERT INTO `v_admin_user` (`id`, `avatar`, `nickname`, `account`, `password`, `clear_password`) VALUES
(1, '/uploads/avatar/20181031/5bd90252493d1.jpg', '最牛逼的程序员', 'admin', '$2y$10$1TcmSI4IoVBBKSaj1JLCwO2fVS2Yl4Qdp2NN7MRH497cEsRsUjKi6', 'yamecent666'),
(3, '/uploads/avatar/20181031/5bd9028051930.jpg', '吴二狗', 'wqg', '$2y$10$yO3X6.AnxWfq/wROixwzdOMMpy/dLkk1fUNlQjg4.K66H9NKKtSfe', 'yamecent'),
(4, '/uploads/avatar/20181031/5bd9026ca5b71.jpg', '徐大王', 'xjj', '$2y$10$n7iDufJKCQU4Ay81okUjN.3IHzViDbSL/f8wewNn0Dix87.mhmOjG', 'yamecent');

-- --------------------------------------------------------

--
-- 表的结构 `v_admin_user_role`
--

CREATE TABLE `v_admin_user_role` (
  `id` int(11) NOT NULL,
  `admin_user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员-角色关系表';

--
-- 转存表中的数据 `v_admin_user_role`
--

INSERT INTO `v_admin_user_role` (`id`, `admin_user_id`, `role_id`) VALUES
(1, 1, 1),
(2, 3, 3),
(3, 4, 3);

-- --------------------------------------------------------

--
-- 表的结构 `v_config`
--

CREATE TABLE `v_config` (
  `id` int(11) UNSIGNED NOT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` text,
  `type` varchar(255) DEFAULT NULL,
  `des` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `v_config`
--

INSERT INTO `v_config` (`id`, `key`, `value`, `type`, `des`) VALUES
(1, 'upload_callback', 'https://www.baidu.com/', 'string', '视频上传回调地址'),
(2, 'transcode_callback', 'https://www.baidu.com/', 'string', '视频转码回调地址');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `v_admin_config`
--
ALTER TABLE `v_admin_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_admin_menu`
--
ALTER TABLE `v_admin_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_admin_permission`
--
ALTER TABLE `v_admin_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_admin_role`
--
ALTER TABLE `v_admin_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_admin_role_menu`
--
ALTER TABLE `v_admin_role_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_admin_role_permission`
--
ALTER TABLE `v_admin_role_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_admin_user`
--
ALTER TABLE `v_admin_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_admin_user_role`
--
ALTER TABLE `v_admin_user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_config`
--
ALTER TABLE `v_config`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `v_admin_config`
--
ALTER TABLE `v_admin_config`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `v_admin_menu`
--
ALTER TABLE `v_admin_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- 使用表AUTO_INCREMENT `v_admin_permission`
--
ALTER TABLE `v_admin_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `v_admin_role`
--
ALTER TABLE `v_admin_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `v_admin_role_menu`
--
ALTER TABLE `v_admin_role_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- 使用表AUTO_INCREMENT `v_admin_role_permission`
--
ALTER TABLE `v_admin_role_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `v_admin_user`
--
ALTER TABLE `v_admin_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `v_admin_user_role`
--
ALTER TABLE `v_admin_user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `v_config`
--
ALTER TABLE `v_config`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
