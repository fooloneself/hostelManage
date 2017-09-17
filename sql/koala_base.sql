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
  `mch_id` int(11) NOT NULL DEFAULT '0' COMMENT '0本系统管理员 !0 商户管理员',
  `expire` int(11) DEFAULT '0' COMMENT '过期时间',
  `last_login_time` int(11) DEFAULT '0' COMMENT '最后登录时间',
  `is_super` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0否 1是',
  `user_name` varchar(100) NOT NULL COMMENT '账号',
  `password` varchar(100) NOT NULL COMMENT '密码',
  `token` varchar(100) DEFAULT NULL COMMENT '会话令牌',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='后台账户表';

/*Table structure for table `t_admin_role` */

DROP TABLE IF EXISTS `t_admin_role`;

CREATE TABLE `t_admin_role` (
  `admin_id` int(11) NOT NULL COMMENT '管理账号ID',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`admin_id`,`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员角色表';

/*Table structure for table `t_building` */

DROP TABLE IF EXISTS `t_building`;

CREATE TABLE `t_building` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '房屋id',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `province` int(11) NOT NULL COMMENT '省份',
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='建筑信息表';

/*Table structure for table `t_mch_module` */

DROP TABLE IF EXISTS `t_mch_module`;

CREATE TABLE `t_mch_module` (
  `mch_id` int(11) NOT NULL COMMENT '商户ID',
  `module_code` varchar(100) NOT NULL COMMENT '模块编码',
  PRIMARY KEY (`mch_id`,`module_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商户模块表';

/*Table structure for table `t_merchant` */

DROP TABLE IF EXISTS `t_merchant`;

CREATE TABLE `t_merchant` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商户ID',
  `number` int(11) NOT NULL COMMENT '商户编号',
  `province` int(11) NOT NULL COMMENT '省份',
  `city` int(11) NOT NULL COMMENT '城市',
  `status` tinyint(1) DEFAULT NULL COMMENT '0注销 1在营',
  `type` tinyint(1) DEFAULT NULL COMMENT '1酒店 2旅行社 3个人',
  `name` varchar(200) DEFAULT NULL COMMENT '商户名称',
  `address` varchar(500) DEFAULT NULL COMMENT '办公地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='商户表';

/*Table structure for table `t_merchant_business_place` */

DROP TABLE IF EXISTS `t_merchant_business_place`;

CREATE TABLE `t_merchant_business_place` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `mch_id` int(11) NOT NULL COMMENT '商家ID',
  `building_id` int(11) NOT NULL COMMENT '建筑ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商家经营场所';

/*Table structure for table `t_merchant_info` */

DROP TABLE IF EXISTS `t_merchant_info`;

CREATE TABLE `t_merchant_info` (
  `mch_id` int(11) NOT NULL COMMENT '商户ID',
  `business_license_no` varchar(50) DEFAULT NULL COMMENT '营业执照编号',
  `business_license` varchar(200) DEFAULT NULL COMMENT '营业执照图片',
  `telephone` varchar(50) DEFAULT NULL COMMENT '座机',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机',
  `introduce` text COMMENT '商户介绍',
  PRIMARY KEY (`mch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商户信息补充表';

/*Table structure for table `t_occupancy_record` */

DROP TABLE IF EXISTS `t_occupancy_record`;

CREATE TABLE `t_occupancy_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `room_id` int(11) NOT NULL COMMENT '房间ID',
  `actual_in_time` int(11) DEFAULT '0' COMMENT '实际入住时间',
  `actual_out_time` int(11) DEFAULT NULL COMMENT '实际退房时间',
  `number_type` tinyint(1) DEFAULT NULL COMMENT '1 身份证 2 学生证 3 军官证 4残疾证',
  `number` varchar(100) COLLATE utf8_estonian_ci DEFAULT NULL COMMENT '证件号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci COMMENT='房间入住信息表';

/*Table structure for table `t_order` */

DROP TABLE IF EXISTS `t_order`;

CREATE TABLE `t_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `mch_id` int(11) NOT NULL COMMENT '商家ID',
  `place_time` int(11) NOT NULL COMMENT '下单时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0待支付 1已结算',
  `amount_payable` decimal(10,2) DEFAULT '0.00' COMMENT '应付金额',
  `amount_paid` decimal(10,2) DEFAULT '0.00' COMMENT '实付金额',
  `amount_deffer` decimal(10,2) DEFAULT '0.00' COMMENT '差额=应付-实付',
  `number` varchar(20) NOT NULL COMMENT '订单编号',
  `channel` varchar(3) DEFAULT NULL COMMENT '订单来源渠道',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单表';

/*Table structure for table `t_order_cost_detail` */

DROP TABLE IF EXISTS `t_order_cost_detail`;

CREATE TABLE `t_order_cost_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '明细ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `room_id` int(11) NOT NULL COMMENT '房间ID',
  `charging_item` varchar(100) NOT NULL COMMENT '收费项目名称',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '金额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='房间费用收取明细表';

/*Table structure for table `t_order_pay_detail` */

DROP TABLE IF EXISTS `t_order_pay_detail`;

CREATE TABLE `t_order_pay_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '支付明细ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `channel` varchar(2) DEFAULT NULL COMMENT '支付渠道',
  `resource_no` varchar(50) DEFAULT NULL COMMENT '资源编号（如折扣券编号、支付宝订单号）',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单费用支付明细';

/*Table structure for table `t_order_room` */

DROP TABLE IF EXISTS `t_order_room`;

CREATE TABLE `t_order_room` (
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `room_id` int(11) NOT NULL COMMENT '房间ID',
  `plan_in_time` int(11) NOT NULL COMMENT '预定入住时间',
  `plan_out_time` int(11) NOT NULL COMMENT '预定退房时间',
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='预定房间信息';

/*Table structure for table `t_region` */

DROP TABLE IF EXISTS `t_region`;

CREATE TABLE `t_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '区域ID',
  `type` tinyint(1) NOT NULL COMMENT '0 省份 1城市',
  `pid` int(11) NOT NULL COMMENT '从属区域ID',
  `name` varchar(100) NOT NULL COMMENT '地区名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='地区管理';

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
  `building_id` int(11) NOT NULL COMMENT '建筑ID',
  `number` int(11) NOT NULL COMMENT '房间编号',
  `create_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `mch_id` int(11) NOT NULL COMMENT '商家ID',
  `bed_num` tinyint(1) DEFAULT '1' COMMENT '床位',
  `floor` tinyint(2) DEFAULT NULL COMMENT '楼层数',
  `status` int(1) DEFAULT '1' COMMENT '0不对外 1对外（如在改装）',
  `blair_said` varchar(100) DEFAULT NULL COMMENT '房间雅称',
  `pic` varchar(400) DEFAULT NULL COMMENT '房间图片（多图以，分割）',
  `introduce` text COMMENT '房间介绍',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='房间信息表';

/*Table structure for table `t_room_charging` */

DROP TABLE IF EXISTS `t_room_charging`;

CREATE TABLE `t_room_charging` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `room_id` int(11) NOT NULL COMMENT '房间ID',
  `type` tinyint(2) NOT NULL COMMENT '计费方式',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='房间计费方式';

/*Table structure for table `t_room_service` */

DROP TABLE IF EXISTS `t_room_service`;

CREATE TABLE `t_room_service` (
  `room_id` int(11) NOT NULL COMMENT '房间ID',
  `service_id` int(11) NOT NULL COMMENT '服务ID',
  PRIMARY KEY (`room_id`,`service_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='房间服务关联表';

/*Table structure for table `t_service` */

DROP TABLE IF EXISTS `t_service`;

CREATE TABLE `t_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '服务ID',
  `name` varchar(100) DEFAULT NULL COMMENT '服务名称',
  `introduce` text COMMENT '服务介绍',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='服务表';

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
