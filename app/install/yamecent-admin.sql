-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-11-03 14:51:01
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

--
-- 转存表中的数据 `v_admin_config`
--

INSERT INTO `v_admin_config` (`id`, `name`, `config_key`, `config_value`, `type`, `created_at`, `updated_at`) VALUES
(7, '后台管理LOGO', 'admin_logo', '/uploads/config/20181031/5bd91d0bcfd6f.png', 'image', '2018-10-31 03:03:52', '2018-10-31 03:03:52');

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
(3, '所有权限', 'admin/menu/list,admin/menu/add,admin/menu/update/{id},admin/menu/del/{id},admin/role/list,admin/permission/list,admin/permission/add,api/set_callback_url,admin/role/add,admin/role/update/{id},admin/role/del/{id},admin/permission/update/{id},admin/permission/del/{id},admin/administrator/list,admin/administrator/add,admin/administrator/update/{id},admin/administrator/del/{id},admin/upload,admin/403,login,set_callback_url,/,console,403,edit/info/{id},logout', '2018-10-30 03:13:20', '2018-10-30 08:18:53');

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
(1, '超级管理员', '系统最高权限', '2018-10-30 03:43:03', '2018-10-30 07:25:03');

-- --------------------------------------------------------

--
-- 表的结构 `v_admin_role_menu`
--

CREATE TABLE `v_admin_role_menu` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='菜单-权限关系表';

-- --------------------------------------------------------

--
-- 表的结构 `v_admin_role_permission`
--

CREATE TABLE `v_admin_role_permission` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色权限关系表';

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
(1, '/uploads/avatar/20181031/5bd90252493d1.jpg', '最牛逼的程序员', 'admin', '$2y$10$1TcmSI4IoVBBKSaj1JLCwO2fVS2Yl4Qdp2NN7MRH497cEsRsUjKi6', 'yamecent666');

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
(1, 1, 1);

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
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `v_admin_config`
--
ALTER TABLE `v_admin_config`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `v_admin_menu`
--
ALTER TABLE `v_admin_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
