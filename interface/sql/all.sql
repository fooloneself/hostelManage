/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.5.53 : Database - koala_hostel
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`koala_hostel` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `koala_hostel`;

/*Table structure for table `t_admin` */

DROP TABLE IF EXISTS `t_admin`;

CREATE TABLE `t_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理账号ID',
  `mch_id` int(11) DEFAULT '0' COMMENT '-1 本系统管理员 >-1 商户管理员',
  `expire` int(11) DEFAULT '0' COMMENT '过期时间',
  `last_login_time` int(11) DEFAULT '0' COMMENT '最后登录时间',
  `is_super` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0否 1是',
  `user_name` varchar(100) NOT NULL COMMENT '账号',
  `password` varchar(100) NOT NULL COMMENT '密码',
  `token` varchar(100) DEFAULT NULL COMMENT '会话令牌',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='后台账户表';

/*Table structure for table `t_admin_info` */

DROP TABLE IF EXISTS `t_admin_info`;

CREATE TABLE `t_admin_info` (
  `admin_id` int(11) NOT NULL COMMENT '账号ID',
  `birthday` int(11) DEFAULT NULL COMMENT '生日',
  `sex` tinyint(1) DEFAULT '0' COMMENT '0保密1男2女',
  `mobile` varchar(50) DEFAULT NULL COMMENT '手机号',
  `name` varchar(100) DEFAULT NULL COMMENT '姓名',
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理账号补充信息';

/*Table structure for table `t_admin_role` */

DROP TABLE IF EXISTS `t_admin_role`;

CREATE TABLE `t_admin_role` (
  `admin_id` int(11) NOT NULL COMMENT '管理账号ID',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`admin_id`,`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员角色表';

/*Table structure for table `t_attachment` */

DROP TABLE IF EXISTS `t_attachment`;

CREATE TABLE `t_attachment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `type` tinyint(1) DEFAULT '1' COMMENT '类型 1图片',
  `path` varchar(200) DEFAULT NULL COMMENT '存放路径',
  `hash` varchar(100) DEFAULT NULL COMMENT 'hash值',
  `save_name` varchar(200) DEFAULT NULL COMMENT '保存名',
  `old_name` varchar(200) DEFAULT NULL COMMENT '原名',
  `mini_type` varchar(100) DEFAULT NULL COMMENT '小类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件';

/*Table structure for table `t_channel` */

DROP TABLE IF EXISTS `t_channel`;

CREATE TABLE `t_channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `mch_id` int(11) DEFAULT NULL COMMENT '商户ID',
  `commission` decimal(10,2) DEFAULT '0.00' COMMENT '佣金',
  `name` varchar(100) DEFAULT NULL COMMENT '渠道名称',
  `introduce` varchar(500) DEFAULT NULL COMMENT '渠道说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='渠道';

/*Table structure for table `t_dictionary` */

DROP TABLE IF EXISTS `t_dictionary`;

CREATE TABLE `t_dictionary` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `label` varchar(100) NOT NULL COMMENT '字典名称',
  `code` varchar(100) NOT NULL COMMENT '唯一标识',
  `introduce` varchar(500) NOT NULL COMMENT '字典说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='字典';

/*Table structure for table `t_dictionary_item` */

DROP TABLE IF EXISTS `t_dictionary_item`;

CREATE TABLE `t_dictionary_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `code` varchar(100) DEFAULT NULL COMMENT '字典编码',
  `key` varchar(200) DEFAULT NULL COMMENT '数据项',
  `value` varchar(200) DEFAULT NULL COMMENT '数据值',
  `order` int(11) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='字典项';

/*Table structure for table `t_feedback` */

DROP TABLE IF EXISTS `t_feedback`;

CREATE TABLE `t_feedback` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `mch_id` int(11) NOT NULL COMMENT '商户id',
  `create_time` int(11) DEFAULT NULL COMMENT '反馈时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '处理状态 0未处理 1已处理 2未解决',
  `content` text COMMENT '反馈信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商户反馈表';

/*Table structure for table `t_linkage_menu` */

DROP TABLE IF EXISTS `t_linkage_menu`;

CREATE TABLE `t_linkage_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `label` varchar(200) DEFAULT NULL COMMENT '菜单名称',
  `code` varchar(200) DEFAULT NULL COMMENT '唯一编码',
  `introduce` text COMMENT '菜单说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='联动菜单组';

/*Table structure for table `t_linkage_menu_item` */

DROP TABLE IF EXISTS `t_linkage_menu_item`;

CREATE TABLE `t_linkage_menu_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) DEFAULT '0' COMMENT '父ID',
  `order` int(11) DEFAULT '1' COMMENT '排序',
  `code` varchar(200) DEFAULT NULL COMMENT '菜单组编码',
  `label` varchar(100) DEFAULT NULL COMMENT '标注',
  `introduce` varchar(500) DEFAULT NULL COMMENT '说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='联动菜单子菜单';

/*Table structure for table `t_mch_module` */

DROP TABLE IF EXISTS `t_mch_module`;

CREATE TABLE `t_mch_module` (
  `mch_id` int(11) NOT NULL COMMENT '商户ID',
  `module_code` varchar(100) NOT NULL COMMENT '模块编码',
  PRIMARY KEY (`mch_id`,`module_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商户模块表';

/*Table structure for table `t_member` */

DROP TABLE IF EXISTS `t_member`;

CREATE TABLE `t_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员id',
  `create_time` int(11) DEFAULT NULL COMMENT '登记时间',
  `birthday` int(11) DEFAULT '0' COMMENT '生日',
  `sex` tinyint(1) DEFAULT '0' COMMENT '性别 0保密 1男 2女',
  `number_type` tinyint(2) DEFAULT NULL COMMENT '1 身份证 2 学生证 3 军官证 4残疾证',
  `consumption_amount` decimal(10,2) DEFAULT '0.00' COMMENT '会员平台总消费金额',
  `name` varchar(100) DEFAULT NULL COMMENT '姓名',
  `mobile` varchar(50) DEFAULT NULL COMMENT '手机号',
  `number` varchar(100) DEFAULT NULL COMMENT '证件号',
  `wx_account` varchar(200) DEFAULT NULL COMMENT '微信账号',
  `mark` text COMMENT '备注',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`number`,`number_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员登记表';

/*Table structure for table `t_member_rank_divide` */

DROP TABLE IF EXISTS `t_member_rank_divide`;

CREATE TABLE `t_member_rank_divide` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '等级ID',
  `mch_id` int(11) NOT NULL COMMENT '商户ID',
  `create_time` int(11) DEFAULT NULL COMMENT '设立时间',
  `min_integral` int(11) DEFAULT NULL COMMENT '最小积分',
  `min_consumption_amount` decimal(10,2) DEFAULT NULL COMMENT '最小消费金额',
  `name` varchar(100) NOT NULL COMMENT '等级名称',
  `mark` varchar(1000) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员等级划分';

/*Table structure for table `t_merchant` */

DROP TABLE IF EXISTS `t_merchant`;

CREATE TABLE `t_merchant` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商户ID',
  `city` int(11) NOT NULL COMMENT '城市',
  `create_time` int(11) DEFAULT NULL COMMENT '注册时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '0注销 1在营',
  `type` tinyint(1) DEFAULT NULL COMMENT '1酒店 2旅行社 3个人',
  `contact_name` varchar(100) DEFAULT NULL COMMENT '联系人姓名',
  `name` varchar(200) DEFAULT NULL COMMENT '商户名称',
  `mobile` varchar(50) DEFAULT NULL COMMENT '手机',
  `telephone` varchar(50) DEFAULT NULL COMMENT '座机',
  `address` varchar(500) DEFAULT NULL COMMENT '办公地址',
  `introduce` text COMMENT '介绍',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='商户表';

/*Table structure for table `t_merchant_member` */

DROP TABLE IF EXISTS `t_merchant_member`;

CREATE TABLE `t_merchant_member` (
  `mch_id` int(11) NOT NULL COMMENT '商户ID',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `rank` int(11) DEFAULT NULL COMMENT '会员等级',
  `consumption_amount` decimal(10,2) DEFAULT '0.00' COMMENT '会员在商户总消费金额',
  PRIMARY KEY (`mch_id`,`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户会员信息';

/*Table structure for table `t_merchant_set` */

DROP TABLE IF EXISTS `t_merchant_set`;

CREATE TABLE `t_merchant_set` (
  `mch_id` int(11) NOT NULL COMMENT '商户id',
  `auto_close_switch` tinyint(1) DEFAULT '0' COMMENT '自动结算开关 0否 1是',
  `reserve_switch` tinyint(1) DEFAULT '0' COMMENT '预定开关 0 否 1 是',
  `hour_room_switch` tinyint(1) DEFAULT '0' COMMENT '钟点房开关 0关闭 1开启',
  `reserve_retention_time` int(11) DEFAULT '0' COMMENT '预定房预留时间',
  `check_out_time` varchar(10) DEFAULT NULL COMMENT '每日退房时间',
  `hour_room_start_time` varchar(20) DEFAULT '0' COMMENT '每日钟点房开始时间',
  `hour_room_end_time` varchar(20) DEFAULT '0' COMMENT '每日钟点房结束时间',
  PRIMARY KEY (`mch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商家设置';

/*Table structure for table `t_occupancy_record` */

DROP TABLE IF EXISTS `t_occupancy_record`;

CREATE TABLE `t_occupancy_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `room_id` int(11) NOT NULL COMMENT '房间ID',
  `mch_id` int(11) NOT NULL COMMENT '商户ID',
  `city` int(11) DEFAULT NULL COMMENT '城市',
  `premises_id` int(11) DEFAULT NULL COMMENT '场所ID',
  `actual_in_time` int(11) DEFAULT '0' COMMENT '实际入住时间',
  `actual_out_time` int(11) DEFAULT NULL COMMENT '实际退房时间',
  `number_type` tinyint(1) DEFAULT NULL COMMENT '1 身份证 2 学生证 3 军官证 4残疾证',
  `number` varchar(100) COLLATE utf8_estonian_ci DEFAULT NULL COMMENT '证件号',
  `person_name` varchar(100) COLLATE utf8_estonian_ci DEFAULT NULL COMMENT '入住人姓名',
  `room_floor` tinyint(2) DEFAULT NULL COMMENT '楼层数',
  `room_number` tinyint(3) DEFAULT NULL COMMENT '房间号',
  `address` text COLLATE utf8_estonian_ci COMMENT '地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci COMMENT='房间入住信息表';

/*Table structure for table `t_order` */

DROP TABLE IF EXISTS `t_order`;

CREATE TABLE `t_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `mch_id` int(11) NOT NULL COMMENT '商家ID',
  `place_time` int(11) NOT NULL COMMENT '下单时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0待支付 1已结算 2异常订单 3退单',
  `number_type` tinyint(2) DEFAULT NULL COMMENT '1 身份证 2 学生证 3 军官证 4残疾证',
  `amount_payable` decimal(10,2) DEFAULT '0.00' COMMENT '应付金额',
  `amount_paid` decimal(10,2) DEFAULT '0.00' COMMENT '实付金额',
  `amount_deffer` decimal(10,2) DEFAULT '0.00' COMMENT '差额=应付-实付',
  `order_no` varchar(20) NOT NULL COMMENT '订单编号',
  `channel` varchar(3) DEFAULT NULL COMMENT '订单来源渠道',
  `number` varchar(50) DEFAULT NULL COMMENT '证件号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';

/*Table structure for table `t_order_cost_detail` */

DROP TABLE IF EXISTS `t_order_cost_detail`;

CREATE TABLE `t_order_cost_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '明细ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `room_id` int(11) NOT NULL COMMENT '房间ID',
  `number` int(11) DEFAULT '0' COMMENT '数量',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '金额',
  `charging_item` varchar(100) NOT NULL COMMENT '收费项目名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='房间费用收取明细表';

/*Table structure for table `t_order_pay_detail` */

DROP TABLE IF EXISTS `t_order_pay_detail`;

CREATE TABLE `t_order_pay_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '支付明细ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `channel` varchar(2) DEFAULT NULL COMMENT '支付渠道',
  `resource_no` varchar(50) DEFAULT NULL COMMENT '资源编号（如折扣券编号、支付宝订单号）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单费用支付明细';

/*Table structure for table `t_order_room` */

DROP TABLE IF EXISTS `t_order_room`;

CREATE TABLE `t_order_room` (
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `room_id` int(11) NOT NULL COMMENT '房间ID',
  `plan_in_time` int(11) NOT NULL COMMENT '预定入住时间',
  `plan_out_time` int(11) NOT NULL COMMENT '预定退房时间',
  `amount` decimal(10,2) DEFAULT NULL COMMENT '房间价格',
  `occupancy_status` tinyint(1) DEFAULT '0' COMMENT '入住状态 0未入住 1已入住',
  PRIMARY KEY (`order_id`,`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='预定房间信息';

/*Table structure for table `t_premises` */

DROP TABLE IF EXISTS `t_premises`;

CREATE TABLE `t_premises` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '建筑id',
  `mch_id` int(11) NOT NULL COMMENT '商户id',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `city` int(11) NOT NULL COMMENT '城市',
  `floor` tinyint(3) DEFAULT '0' COMMENT '楼层数',
  `number` float(10,4) DEFAULT NULL COMMENT '门牌号(小数位为附号)',
  `longitude` decimal(10,6) DEFAULT '0.000000' COMMENT '经度',
  `latitude` decimal(10,6) DEFAULT '0.000000' COMMENT '纬度',
  `name` varchar(200) DEFAULT NULL COMMENT '建筑名称',
  `address` varchar(200) DEFAULT NULL COMMENT '具体位置',
  `street` varchar(200) DEFAULT NULL COMMENT '街道名',
  `introduce` text COMMENT '建筑介绍',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='建筑信息表';

/*Table structure for table `t_role` */

DROP TABLE IF EXISTS `t_role`;

CREATE TABLE `t_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `mch_id` int(11) NOT NULL COMMENT '商户id',
  `name` varchar(100) NOT NULL COMMENT '角色名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色管理';

/*Table structure for table `t_role_privilege` */

DROP TABLE IF EXISTS `t_role_privilege`;

CREATE TABLE `t_role_privilege` (
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `module_code` varchar(100) NOT NULL COMMENT '模块编码',
  `privilege_code` varchar(100) NOT NULL COMMENT '权限编码',
  PRIMARY KEY (`role_id`,`privilege_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色权限表';

/*Table structure for table `t_room` */

DROP TABLE IF EXISTS `t_room`;

CREATE TABLE `t_room` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '房间ID',
  `premises_id` int(11) NOT NULL COMMENT '建筑ID',
  `number` int(11) NOT NULL COMMENT '房间编号',
  `create_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `mch_id` int(11) NOT NULL COMMENT '商家ID',
  `bed_num` tinyint(1) DEFAULT '1' COMMENT '床位',
  `floor` tinyint(2) DEFAULT NULL COMMENT '楼层数',
  `status` tinyint(1) DEFAULT '1' COMMENT '0 空房 1脏房 2锁房',
  `type` int(11) DEFAULT '0' COMMENT '房间类型',
  `blair_said` varchar(100) DEFAULT NULL COMMENT '房间雅称',
  `pic` varchar(400) DEFAULT NULL COMMENT '房间图片（多图以，分割）',
  `cover` varchar(100) DEFAULT NULL COMMENT '房间封面图片',
  `introduce` text COMMENT '房间介绍',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='房间信息表';

/*Table structure for table `t_room_day_price` */

DROP TABLE IF EXISTS `t_room_day_price`;

CREATE TABLE `t_room_day_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `room_id` int(11) DEFAULT NULL COMMENT '房间ID',
  `year` int(11) DEFAULT NULL COMMENT '年',
  `month` tinyint(2) DEFAULT NULL COMMENT '月',
  `day` tinyint(2) DEFAULT NULL COMMENT '日',
  `price` decimal(10,2) DEFAULT NULL COMMENT '日价格 (0免费 >0设置价格)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='房间日价格';

/*Table structure for table `t_room_type` */

DROP TABLE IF EXISTS `t_room_type`;

CREATE TABLE `t_room_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `mch_id` int(11) NOT NULL COMMENT '商户id',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `allow_hour_room` tinyint(1) DEFAULT '0' COMMENT '是否允许钟点房 0否 1是',
  `default_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '房间价格',
  `hour_room_price` decimal(10,2) DEFAULT '0.00' COMMENT '钟点房价格',
  `name` varchar(100) NOT NULL COMMENT '类型名',
  `introduce` text COMMENT '房型说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='房间类型说明';

/*Table structure for table `t_room_week_price` */

DROP TABLE IF EXISTS `t_room_week_price`;

CREATE TABLE `t_room_week_price` (
  `room_id` int(11) NOT NULL COMMENT '房间ID',
  `monday` decimal(10,2) DEFAULT '-1.00' COMMENT '周一价格(-1 不设置 0免费 >0设置价格)',
  `tuesday` decimal(10,2) DEFAULT '-1.00' COMMENT '周二价格(-1 不设置 0免费 >0设置价格)',
  `wensday` decimal(10,2) DEFAULT '-1.00' COMMENT '周三价格(-1 不设置 0免费 >0设置价格)',
  `thursday` decimal(10,2) DEFAULT '-1.00' COMMENT '周四价格(-1 不设置 0免费 >0设置价格)',
  `friday` decimal(10,2) DEFAULT '-1.00' COMMENT '周五价格(-1 不设置 0免费 >0设置价格)',
  `saturday` decimal(10,2) DEFAULT '-1.00' COMMENT '周六价格(-1 不设置 0免费 >0设置价格)',
  `sunday` decimal(10,2) DEFAULT '-1.00' COMMENT '周日价格(-1 不设置 0免费 >0设置价格)',
  PRIMARY KEY (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='房间周价格表';

/*Table structure for table `t_user` */

DROP TABLE IF EXISTS `t_user`;

CREATE TABLE `t_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员id',
  `user_name` varchar(100) NOT NULL COMMENT '账号',
  `password` varchar(100) NOT NULL COMMENT '密码',
  `push_id` varchar(100) DEFAULT NULL COMMENT '手机终端推送码',
  `last_login_time` int(11) DEFAULT NULL COMMENT '最后登录时间',
  `token` varchar(100) DEFAULT NULL COMMENT '会话令牌',
  `create_time` int(11) DEFAULT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
