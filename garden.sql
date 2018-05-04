/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : garden

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-05-03 15:55:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for gd_activate
-- ----------------------------
DROP TABLE IF EXISTS `gd_activate`;
CREATE TABLE `gd_activate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activateNo` char(12) NOT NULL COMMENT '激活码',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '激活码状态 0 未使用 1 已使用',
  `datetime` datetime NOT NULL COMMENT '激时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_activateNo` (`activateNo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='激活码信息表';

-- ----------------------------
-- Records of gd_activate
-- ----------------------------
INSERT INTO `gd_activate` VALUES ('1', '111111111111', '1', '2018-04-09 17:57:05');

-- ----------------------------
-- Table structure for gd_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `gd_admin_user`;
CREATE TABLE `gd_admin_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL,
  `datetime` datetime NOT NULL COMMENT '最新时间（激活时间）',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='管理员信息表';

-- ----------------------------
-- Records of gd_admin_user
-- ----------------------------
INSERT INTO `gd_admin_user` VALUES ('7', 'admin6', '1bbd886460827015e5d605ed44252251', '0000-00-00 00:00:00');
INSERT INTO `gd_admin_user` VALUES ('8', 'admin7', '1bbd886460827015e5d605ed44252251', '0000-00-00 00:00:00');
INSERT INTO `gd_admin_user` VALUES ('9', 'admin8', '1bbd886460827015e5d605ed44252251', '0000-00-00 00:00:00');
INSERT INTO `gd_admin_user` VALUES ('10', 'admin9', '1bbd886460827015e5d605ed44252251', '0000-00-00 00:00:00');
INSERT INTO `gd_admin_user` VALUES ('11', 'admin10', '1bbd886460827015e5d605ed44252251', '0000-00-00 00:00:00');
INSERT INTO `gd_admin_user` VALUES ('12', 'admin11', '1bbd886460827015e5d605ed44252251', '0000-00-00 00:00:00');
INSERT INTO `gd_admin_user` VALUES ('13', 'admin12', '1bbd886460827015e5d605ed44252251', '0000-00-00 00:00:00');
INSERT INTO `gd_admin_user` VALUES ('14', 'admin13', '1bbd886460827015e5d605ed44252251', '0000-00-00 00:00:00');
INSERT INTO `gd_admin_user` VALUES ('15', 'admin14', '1bbd886460827015e5d605ed44252251', '0000-00-00 00:00:00');
INSERT INTO `gd_admin_user` VALUES ('16', 'admin15', '1bbd886460827015e5d605ed44252251', '0000-00-00 00:00:00');
INSERT INTO `gd_admin_user` VALUES ('17', 'admin16', '1bbd886460827015e5d605ed44252251', '0000-00-00 00:00:00');
INSERT INTO `gd_admin_user` VALUES ('18', 'admin17', '1bbd886460827015e5d605ed44252251', '0000-00-00 00:00:00');
INSERT INTO `gd_admin_user` VALUES ('19', 'admint', '1bbd886460827015e5d605ed44252251', '2018-04-23 07:46:00');

-- ----------------------------
-- Table structure for gd_config
-- ----------------------------
DROP TABLE IF EXISTS `gd_config`;
CREATE TABLE `gd_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(20) NOT NULL COMMENT '配置主键',
  `tips` varchar(255) NOT NULL COMMENT '提示',
  `content` varchar(255) NOT NULL COMMENT '值',
  `datetime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='系统配置信息表';

-- ----------------------------
-- Records of gd_config
-- ----------------------------
INSERT INTO `gd_config` VALUES ('1', 'Fert2Fruit', '化肥产出果子数量 单位(个)', '20', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('2', 'FertGrowTime', '化肥产出果子所需时间 单位(秒)', '86400', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('3', 'FertGrowWormyChance', '化肥长虫概率', '0.07', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('4', 'DryFruitCDChance', '干旱果子减少概率', '0.2', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('5', 'WormyFruitCDChance', '长虫果子减少概率', '0.3', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('6', 'PreventStealChance', '防偷神奇防偷概率', '0.8', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('7', 'ActivateDayStealNum', '激活用户每天可偷取次数(每天)', '1', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('8', 'PackageDayStealNum', '套餐用户每天可偷取次数(每天)', '10', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('9', 'ActivateStealMinNum', '激活用户偷取最小量', '1', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('10', 'ActivateStealMaxNum', '激活用户偷取最大量', '3', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('11', 'PackageStealMinNum', '套餐用户偷取最小量', '2', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('12', 'PackageStealMaxNum', '激活用户偷取最大量', '6', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('13', 'FruitMaxStealRate', '果子可偷取最大比例', '0.1', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('14', 'NoWater2Dry', '多长时间不浇水会导致干旱 单位(秒)', '172800', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('15', 'ActiveWaterMinTime', '被浇水最小间隔时间 单位(秒)', '14400', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('16', 'NightStart', '夜晚开始时间', '18', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('17', 'NightEnd', '夜晚结束时间', '6', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('18', 'RipeningRate', '催熟时间减少比例', '0.5', '2018-04-23 17:29:36');
INSERT INTO `gd_config` VALUES ('19', 'AntiTheftTime', '防偷神奇防护时间', '259200', '2018-04-23 17:29:36');

-- ----------------------------
-- Table structure for gd_package
-- ----------------------------
DROP TABLE IF EXISTS `gd_package`;
CREATE TABLE `gd_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `packageNo` char(12) NOT NULL COMMENT '礼包编码',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '礼包等级',
  `batch` varchar(50) NOT NULL COMMENT '批次',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '礼包状态 0 未使用 1 已使用',
  `datetime` datetime NOT NULL COMMENT '激活时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_packageNo` (`packageNo`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='礼包信息表';

-- ----------------------------
-- Records of gd_package
-- ----------------------------
INSERT INTO `gd_package` VALUES ('1', '111111111111', '1', '', '1', '2018-04-09 17:11:26');
INSERT INTO `gd_package` VALUES ('2', '222222222222', '1', '', '1', '2018-04-09 17:11:33');
INSERT INTO `gd_package` VALUES ('3', '333333333333', '2', '', '1', '2018-04-09 17:11:41');
INSERT INTO `gd_package` VALUES ('4', 'P1M31y8RCcn7', '1', '111', '0', '2018-04-23 10:07:10');
INSERT INTO `gd_package` VALUES ('5', 'P1MpOuxpQ2Qc', '1', '111', '0', '2018-04-23 10:07:10');
INSERT INTO `gd_package` VALUES ('6', 'P15Z4EQUlHHT', '1', '111', '0', '2018-04-23 10:07:10');
INSERT INTO `gd_package` VALUES ('7', 'P1XUFeeOeNbp', '1', '111', '0', '2018-04-23 10:07:10');
INSERT INTO `gd_package` VALUES ('8', 'P1nRlYFu6DvD', '1', '', '0', '2018-04-23 10:07:10');
INSERT INTO `gd_package` VALUES ('9', 'P1Qzi6cbYaie', '1', '111', '0', '2018-04-23 10:07:10');
INSERT INTO `gd_package` VALUES ('10', 'P1TCltc29JJ8', '1', '', '0', '2018-04-23 10:07:10');
INSERT INTO `gd_package` VALUES ('11', 'P13x79rIsW8f', '1', '', '0', '2018-04-23 10:07:10');
INSERT INTO `gd_package` VALUES ('12', 'P1EC3Xacg7ct', '1', '', '0', '2018-04-23 10:07:10');
INSERT INTO `gd_package` VALUES ('13', 'P1Beljl857ye', '1', '', '0', '2018-04-23 10:07:11');
INSERT INTO `gd_package` VALUES ('14', 'P1sFXUsanMgs', '1', '', '0', '2018-04-23 10:07:11');
INSERT INTO `gd_package` VALUES ('15', 'P1ppfYE6EmM7', '1', '', '0', '2018-04-23 10:07:11');
INSERT INTO `gd_package` VALUES ('16', 'P1rem4uW14BL', '1', '', '0', '2018-04-23 10:07:11');
INSERT INTO `gd_package` VALUES ('17', 'P17rxReboQPE', '1', '', '0', '2018-04-23 10:07:11');
INSERT INTO `gd_package` VALUES ('18', 'P1PODedLuDqX', '1', '', '0', '2018-04-23 10:07:11');
INSERT INTO `gd_package` VALUES ('19', 'P1LYOPDtF1Na', '1', '', '0', '2018-04-23 10:07:11');
INSERT INTO `gd_package` VALUES ('20', 'P1DGZrPC25hr', '1', '', '0', '2018-04-23 10:07:11');
INSERT INTO `gd_package` VALUES ('21', 'P1LJd8V5iwpL', '1', '', '0', '2018-04-23 10:07:11');
INSERT INTO `gd_package` VALUES ('22', 'P1NScMNftvYt', '1', '', '0', '2018-04-23 10:07:11');
INSERT INTO `gd_package` VALUES ('23', 'P1vIhRQgflLc', '1', '', '0', '2018-04-23 10:07:11');
INSERT INTO `gd_package` VALUES ('24', 'P2EwQ6kwCCtN', '2', '', '0', '2018-04-24 03:51:14');
INSERT INTO `gd_package` VALUES ('25', 'P2SRI1neOlZr', '2', '2222', '0', '2018-04-25 03:41:57');
INSERT INTO `gd_package` VALUES ('26', 'P2lhHy7JyU0s', '2', '2222', '0', '2018-04-25 03:41:57');
INSERT INTO `gd_package` VALUES ('27', 'R1lhHy7JyU0s', '98', '注册', '0', '2018-05-03 15:19:08');
INSERT INTO `gd_package` VALUES ('28', 'R15MNNpSQ2FF', '98', '注册', '1', '2018-05-03 15:42:11');
INSERT INTO `gd_package` VALUES ('29', 'R25MNNpSQ2FF', '99', '分享', '1', '2018-05-03 15:42:11');

-- ----------------------------
-- Table structure for gd_package_tool
-- ----------------------------
DROP TABLE IF EXISTS `gd_package_tool`;
CREATE TABLE `gd_package_tool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '礼包等级',
  `toolId` int(10) unsigned NOT NULL COMMENT '关联 tool 表中的id',
  `everyDays` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '每多少天赠送一次',
  `sendNum` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '赠送个数',
  `sendTimes` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '赠送次数',
  `datetime` datetime NOT NULL COMMENT '最新时间（激活时间）',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_level_toolId` (`level`,`toolId`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='礼包对应道具信息表';

-- ----------------------------
-- Records of gd_package_tool
-- ----------------------------
INSERT INTO `gd_package_tool` VALUES ('1', '1', '1', '2', '1', '1', '2018-04-18 16:35:57');
INSERT INTO `gd_package_tool` VALUES ('2', '1', '2', '1', '1', '1', '2018-04-18 18:09:48');
INSERT INTO `gd_package_tool` VALUES ('4', '2', '1', '1', '1', '2', '2018-04-18 10:20:11');
INSERT INTO `gd_package_tool` VALUES ('5', '2', '2', '1', '1', '2', '2018-04-18 10:20:38');
INSERT INTO `gd_package_tool` VALUES ('6', '98', '1', '1', '1', '50', '2018-05-03 15:38:15');
INSERT INTO `gd_package_tool` VALUES ('7', '99', '1', '1', '1', '5', '2018-05-03 15:38:40');
INSERT INTO `gd_package_tool` VALUES ('8', '99', '2', '1', '1', '1', '2018-05-03 15:39:10');
INSERT INTO `gd_package_tool` VALUES ('9', '99', '4', '1', '1', '1', '2018-05-03 15:39:18');

-- ----------------------------
-- Table structure for gd_sms
-- ----------------------------
DROP TABLE IF EXISTS `gd_sms`;
CREATE TABLE `gd_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(12) CHARACTER SET utf8mb4 NOT NULL COMMENT '手机号码',
  `code` char(4) NOT NULL COMMENT '验证码',
  `type` tinyint(3) unsigned NOT NULL COMMENT '验证码类型 1 注册',
  `sendTime` datetime NOT NULL COMMENT '发送时间',
  PRIMARY KEY (`id`,`mobile`),
  UNIQUE KEY `idx_mobile` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='短信验证码信息表';

-- ----------------------------
-- Records of gd_sms
-- ----------------------------
INSERT INTO `gd_sms` VALUES ('1', '18520228703', '6481', '1', '2018-05-03 14:25:28');

-- ----------------------------
-- Table structure for gd_tool_cn
-- ----------------------------
DROP TABLE IF EXISTS `gd_tool_cn`;
CREATE TABLE `gd_tool_cn` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `toolname` varchar(50) NOT NULL COMMENT '道具名称',
  `isSell` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否可售卖 1 可以 0 不可以',
  `describe` varchar(255) NOT NULL COMMENT '功能描述',
  `banner` varchar(255) NOT NULL COMMENT '图标路径',
  `datetime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='道具信息(中文)表';

-- ----------------------------
-- Records of gd_tool_cn
-- ----------------------------
INSERT INTO `gd_tool_cn` VALUES ('1', '化肥', '1', '在施用化肥后的24小时可以结出果实（20枚）', 'tool/props_icon01.png', '2018-03-27 10:27:12');
INSERT INTO `gd_tool_cn` VALUES ('2', '驱虫器', '1', '用驱虫器杀虫，发生虫害产量减少30%', 'tool/props_icon02.png', '2018-03-27 10:27:39');
INSERT INTO `gd_tool_cn` VALUES ('3', '催熟剂', '1', '每使用一次可以减少一半的时间成熟', 'tool/props_icon04.png', '2018-03-27 10:28:45');
INSERT INTO `gd_tool_cn` VALUES ('4', '防偷神器', '1', '使用后被偷概率减少80%', 'tool/props_icon05.png', '2018-03-27 10:29:02');
INSERT INTO `gd_tool_cn` VALUES ('5', '药剂', '1', '除病虫害，生病害产量减少40%', 'tool/props_icon03.png', '2018-03-27 10:30:09');
INSERT INTO `gd_tool_cn` VALUES ('6', '浇水', '0', '防止干旱，发生干旱产量减少20%', '', '2018-03-27 10:33:43');
INSERT INTO `gd_tool_cn` VALUES ('7', '干旱', '0', '连续48小时没浇过水', '', '2018-03-27 10:43:08');
INSERT INTO `gd_tool_cn` VALUES ('8', '虫害', '0', '施肥7%的概率', '', '2018-03-27 10:43:21');

-- ----------------------------
-- Table structure for gd_tool_en
-- ----------------------------
DROP TABLE IF EXISTS `gd_tool_en`;
CREATE TABLE `gd_tool_en` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `toolname` varchar(50) NOT NULL COMMENT '道具名称',
  `isSell` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否可售卖 1 可以 0 不可以',
  `describe` varchar(255) NOT NULL COMMENT '功能描述',
  `banner` varchar(255) NOT NULL COMMENT '图标路径',
  `datetime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='道具信息(英文)表';

-- ----------------------------
-- Records of gd_tool_en
-- ----------------------------
INSERT INTO `gd_tool_en` VALUES ('1', 'fertilizer', '1', 'Fruit (20) can be yielded 24 hours after the application of chemical fertilizer.', 'tool/props_icon01.png', '2018-03-27 10:27:12');
INSERT INTO `gd_tool_en` VALUES ('2', 'repellent', '1', 'Insect pests were killed by anthelmintic, and the yield of insect pests was reduced by 30%', 'tool/props_icon02.png', '2018-03-27 10:27:39');
INSERT INTO `gd_tool_en` VALUES ('3', 'ripener', '1', 'Each time can be reduced by half a time', 'tool/props_icon04.png', '2018-03-27 10:28:45');
INSERT INTO `gd_tool_en` VALUES ('4', 'anti-theft', '1', 'After use, the probability of stealing is reduced by 80%', 'tool/props_icon05.png', '2018-03-27 10:29:02');
INSERT INTO `gd_tool_en` VALUES ('5', 'drug', '1', 'In addition to diseases and pests, the yield of raw diseases was reduced by 40%', 'tool/props_icon03.png', '2018-03-27 10:30:09');
INSERT INTO `gd_tool_en` VALUES ('6', 'water', '0', 'To prevent drought, the yield of drought is reduced by 20%', '', '2018-03-27 10:33:43');
INSERT INTO `gd_tool_en` VALUES ('7', 'drought', '0', 'No water was poured for 48 hours in a row', '', '2018-03-27 10:43:08');
INSERT INTO `gd_tool_en` VALUES ('8', 'pest', '0', 'The probability of 7% fertilization', '', '2018-03-27 10:43:21');

-- ----------------------------
-- Table structure for gd_user
-- ----------------------------
DROP TABLE IF EXISTS `gd_user`;
CREATE TABLE `gd_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `nickname` varchar(30) NOT NULL COMMENT '昵称',
  `password` varchar(32) NOT NULL COMMENT '加密密码',
  `salt` char(6) NOT NULL COMMENT '密码加密串',
  `datetime` datetime NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_username` (`username`) USING BTREE COMMENT '唯一用户名'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='用户基本信息表';

-- ----------------------------
-- Records of gd_user
-- ----------------------------
INSERT INTO `gd_user` VALUES ('1', 'h280338871', '', '09c6537cedf5b85d6b2c5d84d120d6a5', 'RbeQDF', '2018-03-26 17:29:32');
INSERT INTO `gd_user` VALUES ('2', 'h28033887', '', '09c6537cedf5b85d6b2c5d84d120d6a5', 'RbeQDF', '2018-03-26 17:29:32');
INSERT INTO `gd_user` VALUES ('3', 'h2803388', '', '09c6537cedf5b85d6b2c5d84d120d6a5', 'RbeQDF', '2018-03-26 17:29:32');
INSERT INTO `gd_user` VALUES ('4', 'h280338', '', '09c6537cedf5b85d6b2c5d84d120d6a5', 'RbeQDF', '2018-03-26 17:29:32');
INSERT INTO `gd_user` VALUES ('5', '1111111111', '', '879dc76a2c87eb025c3917d395abd211', '4j16Uu', '2018-03-30 10:09:48');
INSERT INTO `gd_user` VALUES ('6', 'lsdlsd111', '', '4820ff8638fc57327175a4175dacdfda', 'I04vip', '2018-03-30 10:16:05');
INSERT INTO `gd_user` VALUES ('7', 'qqqqqqqq', '', 'bcc0b333f84f86c27a2744497b6da7ee', 'JGQ1qR', '2018-03-30 11:44:12');
INSERT INTO `gd_user` VALUES ('8', '18520228703', '', '5b8c91c33f5a1d61493d9e00a725d136', 'wVkt7W', '2018-05-03 15:42:11');

-- ----------------------------
-- Table structure for gd_user_attach
-- ----------------------------
DROP TABLE IF EXISTS `gd_user_attach`;
CREATE TABLE `gd_user_attach` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL COMMENT '关联 uesr 表中的id',
  `curType` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '附件类型 1 图片',
  `useType` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '使用类型 1 头像',
  `url` varchar(255) NOT NULL COMMENT '相对地址',
  `datetime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_userId_curType_useType` (`userId`,`curType`,`useType`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户附件信息表';

-- ----------------------------
-- Records of gd_user_attach
-- ----------------------------
INSERT INTO `gd_user_attach` VALUES ('1', '1', '1', '1', '', '2018-04-17 17:48:48');

-- ----------------------------
-- Table structure for gd_user_count
-- ----------------------------
DROP TABLE IF EXISTS `gd_user_count`;
CREATE TABLE `gd_user_count` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL COMMENT '关联 uesr 表中的id',
  `score` int(10) unsigned NOT NULL COMMENT '积分信息',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户数据信息表';

-- ----------------------------
-- Records of gd_user_count
-- ----------------------------
INSERT INTO `gd_user_count` VALUES ('1', '1', '45');

-- ----------------------------
-- Table structure for gd_user_day_count
-- ----------------------------
DROP TABLE IF EXISTS `gd_user_day_count`;
CREATE TABLE `gd_user_day_count` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL COMMENT '关联 uesr 表中的id',
  `dayDate` int(8) unsigned NOT NULL COMMENT '每日',
  `stealNum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日偷取次数',
  `stealFruit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日偷取果子',
  `pickNum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日摘取次数',
  `pickFruit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日摘取果子',
  `waterNum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日浇水次数',
  `fertNum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日施肥次数',
  `wormNum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日除虫次数',
  `ripenerNum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日催熟次数',
  `antiTheftNum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日防盗次数',
  `drugNum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日药剂使用次数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_userId_dayDate` (`userId`,`dayDate`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户每日统计数据信息表';

-- ----------------------------
-- Records of gd_user_day_count
-- ----------------------------
INSERT INTO `gd_user_day_count` VALUES ('1', '1', '20180408', '2', '2', '2', '36', '0', '0', '0', '0', '0', '0');
INSERT INTO `gd_user_day_count` VALUES ('2', '1', '20180418', '10', '7', '0', '0', '0', '0', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for gd_user_log
-- ----------------------------
DROP TABLE IF EXISTS `gd_user_log`;
CREATE TABLE `gd_user_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL COMMENT '关联 uesr 表中的id',
  `curType` varchar(10) NOT NULL COMMENT '操作类型',
  `joinUserId` int(10) unsigned NOT NULL COMMENT '关联 uesr 表中的id',
  `joinFruit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联果子数量',
  `content` varchar(50) NOT NULL COMMENT '描述',
  `datetime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='用户日志信息表';

-- ----------------------------
-- Records of gd_user_log
-- ----------------------------
INSERT INTO `gd_user_log` VALUES ('1', '4', 'water', '1', '0', 'user.user_water_tree', '2018-03-30 15:39:11');
INSERT INTO `gd_user_log` VALUES ('2', '4', 'water', '1', '0', 'user.user_water_tree', '2018-03-30 16:04:26');
INSERT INTO `gd_user_log` VALUES ('4', '2', 'steal', '1', '1', 'user.user_steal_fruit', '2018-04-08 13:39:35');
INSERT INTO `gd_user_log` VALUES ('7', '2', 'steal', '1', '1', 'user.user_steal_fruit', '2018-04-08 13:49:15');
INSERT INTO `gd_user_log` VALUES ('8', '1', 'pick', '1', '19', 'user.user_pick_fruit', '2018-04-08 14:12:05');
INSERT INTO `gd_user_log` VALUES ('9', '1', 'pick', '1', '17', 'user.user_pick_fruit', '2018-04-08 14:14:45');
INSERT INTO `gd_user_log` VALUES ('10', '1', 'anti-theft', '1', '0', 'tool.tool_anti_theft_used', '2018-04-08 16:44:00');
INSERT INTO `gd_user_log` VALUES ('11', '1', 'anti-theft', '1', '0', 'tool.tool_repellent_used', '2018-04-08 17:01:51');
INSERT INTO `gd_user_log` VALUES ('12', '1', 'ripener', '1', '0', 'tool.tool_ripener_used', '2018-04-09 11:32:04');
INSERT INTO `gd_user_log` VALUES ('13', '1', 'ripener', '1', '0', 'tool.tool_ripener_used', '2018-04-09 11:32:54');
INSERT INTO `gd_user_log` VALUES ('14', '1', 'fert', '1', '0', 'tool.tool_fertilizer_used', '2018-04-09 12:03:03');
INSERT INTO `gd_user_log` VALUES ('15', '1', 'water', '1', '0', 'user.user_water_tree', '2018-04-18 10:46:04');
INSERT INTO `gd_user_log` VALUES ('16', '1', 'water', '1', '0', 'user.user_water_tree', '2018-04-18 11:11:08');
INSERT INTO `gd_user_log` VALUES ('17', '2', 'steal', '1', '2', 'user.user_steal_fruit', '2018-04-18 11:14:23');
INSERT INTO `gd_user_log` VALUES ('18', '3', 'steal', '1', '1', 'user.user_steal_fruit', '2018-04-18 11:14:41');
INSERT INTO `gd_user_log` VALUES ('19', '4', 'steal', '1', '1', 'user.user_steal_fruit', '2018-04-18 11:57:23');
INSERT INTO `gd_user_log` VALUES ('20', '4', 'steal', '1', '1', 'user.user_steal_fruit', '2018-04-18 11:57:25');
INSERT INTO `gd_user_log` VALUES ('21', '4', 'steal', '1', '0', 'user.user_steal_fruit', '2018-04-18 14:16:28');
INSERT INTO `gd_user_log` VALUES ('22', '4', 'steal', '1', '2', 'user.user_steal_fruit', '2018-04-18 14:16:33');
INSERT INTO `gd_user_log` VALUES ('23', '4', 'steal', '1', '0', 'user.user_steal_fruit', '2018-04-18 14:16:36');
INSERT INTO `gd_user_log` VALUES ('24', '4', 'steal', '1', '0', 'user.user_steal_fruit', '2018-04-18 14:16:51');
INSERT INTO `gd_user_log` VALUES ('25', '4', 'steal', '1', '0', 'user.user_steal_fruit', '2018-04-18 14:16:53');
INSERT INTO `gd_user_log` VALUES ('26', '4', 'steal', '1', '0', 'user.user_steal_fruit', '2018-04-18 14:17:10');

-- ----------------------------
-- Table structure for gd_user_package
-- ----------------------------
DROP TABLE IF EXISTS `gd_user_package`;
CREATE TABLE `gd_user_package` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL COMMENT '关联 uesr 表中的id',
  `packageNo` char(12) NOT NULL COMMENT '礼包编码',
  `level` tinyint(255) unsigned NOT NULL DEFAULT '1' COMMENT '礼包等级',
  `sendDay` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '赠送日',
  `sendEnd` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否赠送完成 0 未完成 1 已完成',
  `datetime` datetime NOT NULL COMMENT '最新时间（激活时间）',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_packageNo` (`packageNo`) USING BTREE,
  KEY `idx_sendEnd_sendDay` (`sendEnd`,`sendDay`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='用户对应礼包信息表';

-- ----------------------------
-- Records of gd_user_package
-- ----------------------------
INSERT INTO `gd_user_package` VALUES ('1', '1', '111111111111', '1', '20180409', '1', '2018-04-09 17:42:10');
INSERT INTO `gd_user_package` VALUES ('11', '1', '222222222222', '1', '20180418', '1', '2018-04-18 18:32:01');
INSERT INTO `gd_user_package` VALUES ('12', '1', '333333333333', '2', '20180419', '1', '2018-04-18 18:38:00');
INSERT INTO `gd_user_package` VALUES ('13', '8', 'R15MNNpSQ2FF', '98', '20180503', '0', '2018-05-03 15:42:11');
INSERT INTO `gd_user_package` VALUES ('14', '1', 'R25MNNpSQ2FF', '99', '20180503', '0', '2018-05-03 15:42:11');

-- ----------------------------
-- Table structure for gd_user_profile
-- ----------------------------
DROP TABLE IF EXISTS `gd_user_profile`;
CREATE TABLE `gd_user_profile` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL COMMENT '关联 uesr 表中的id',
  `isActivate` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否激活账号 1 激活 0 未激活',
  `activateTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '账号激活时间',
  `isPackage` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否激活套餐',
  `packageTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '套餐激活时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='用户附加信息表';

-- ----------------------------
-- Records of gd_user_profile
-- ----------------------------
INSERT INTO `gd_user_profile` VALUES ('1', '1', '1', '2018-04-09 18:00:13', '1', '2018-04-18 18:38:00');
INSERT INTO `gd_user_profile` VALUES ('2', '2', '0', '0000-00-00 00:00:00', '0', '0000-00-00 00:00:00');
INSERT INTO `gd_user_profile` VALUES ('3', '3', '0', '0000-00-00 00:00:00', '0', '0000-00-00 00:00:00');
INSERT INTO `gd_user_profile` VALUES ('4', '4', '0', '0000-00-00 00:00:00', '0', '0000-00-00 00:00:00');
INSERT INTO `gd_user_profile` VALUES ('5', '5', '0', '0000-00-00 00:00:00', '0', '0000-00-00 00:00:00');
INSERT INTO `gd_user_profile` VALUES ('6', '6', '0', '0000-00-00 00:00:00', '0', '0000-00-00 00:00:00');
INSERT INTO `gd_user_profile` VALUES ('7', '7', '0', '0000-00-00 00:00:00', '0', '0000-00-00 00:00:00');
INSERT INTO `gd_user_profile` VALUES ('8', '8', '1', '2018-05-03 15:42:11', '0', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for gd_user_score_log
-- ----------------------------
DROP TABLE IF EXISTS `gd_user_score_log`;
CREATE TABLE `gd_user_score_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL COMMENT '关联 uesr 表中的id',
  `changeType` tinyint(1) unsigned NOT NULL COMMENT '变更方式 1 增加 0 减少',
  `changeScore` int(10) unsigned NOT NULL COMMENT '变更积分',
  `oldScore` int(10) unsigned NOT NULL COMMENT '变更之前积分',
  `newScore` int(10) unsigned NOT NULL COMMENT '变更之后积分',
  `content` varchar(50) NOT NULL COMMENT '描述',
  `datetime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_userId_changeType` (`userId`,`changeType`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='用户积分日志信息表';

-- ----------------------------
-- Records of gd_user_score_log
-- ----------------------------
INSERT INTO `gd_user_score_log` VALUES ('1', '1', '0', '100', '1000', '900', 'user.user_score_take', '2018-03-28 18:15:31');
INSERT INTO `gd_user_score_log` VALUES ('2', '1', '0', '100', '900', '800', 'user.user_score_take', '2018-03-28 18:16:21');
INSERT INTO `gd_user_score_log` VALUES ('3', '1', '0', '100', '800', '700', 'user.user_score_take', '2018-03-28 18:16:22');
INSERT INTO `gd_user_score_log` VALUES ('4', '1', '0', '100', '700', '600', 'user.user_score_take', '2018-03-28 18:16:23');
INSERT INTO `gd_user_score_log` VALUES ('5', '1', '0', '100', '600', '500', 'user.user_score_take', '2018-03-28 18:16:24');
INSERT INTO `gd_user_score_log` VALUES ('6', '1', '0', '100', '500', '400', 'user.user_score_take', '2018-03-28 18:16:24');
INSERT INTO `gd_user_score_log` VALUES ('7', '1', '0', '100', '400', '300', 'user.user_score_take', '2018-03-28 18:16:25');
INSERT INTO `gd_user_score_log` VALUES ('8', '1', '0', '100', '300', '200', 'user.user_score_take', '2018-03-28 18:16:26');
INSERT INTO `gd_user_score_log` VALUES ('9', '1', '0', '100', '200', '100', 'user.user_score_take', '2018-03-28 18:16:26');
INSERT INTO `gd_user_score_log` VALUES ('10', '1', '0', '100', '100', '0', 'user.user_score_take', '2018-03-28 18:16:27');
INSERT INTO `gd_user_score_log` VALUES ('12', '1', '1', '1', '0', '1', 'user.user_steal_fruit', '2018-04-08 13:39:35');
INSERT INTO `gd_user_score_log` VALUES ('13', '1', '1', '1', '1', '2', 'user.user_steal_fruit', '2018-04-08 13:49:15');
INSERT INTO `gd_user_score_log` VALUES ('14', '1', '1', '19', '2', '21', 'user.user_pick_fruit', '2018-04-08 14:12:05');
INSERT INTO `gd_user_score_log` VALUES ('15', '1', '1', '17', '21', '38', 'user.user_pick_fruit', '2018-04-08 14:14:45');
INSERT INTO `gd_user_score_log` VALUES ('16', '1', '1', '2', '38', '40', 'user.user_steal_fruit', '2018-04-18 11:14:23');
INSERT INTO `gd_user_score_log` VALUES ('17', '1', '1', '1', '40', '41', 'user.user_steal_fruit', '2018-04-18 11:14:41');
INSERT INTO `gd_user_score_log` VALUES ('18', '1', '1', '1', '41', '42', 'user.user_steal_fruit', '2018-04-18 11:57:23');
INSERT INTO `gd_user_score_log` VALUES ('19', '1', '1', '1', '42', '43', 'user.user_steal_fruit', '2018-04-18 11:57:25');
INSERT INTO `gd_user_score_log` VALUES ('20', '1', '1', '2', '43', '45', 'user.user_steal_fruit', '2018-04-18 14:16:33');

-- ----------------------------
-- Table structure for gd_user_score_take
-- ----------------------------
DROP TABLE IF EXISTS `gd_user_score_take`;
CREATE TABLE `gd_user_score_take` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL COMMENT '关联 uesr 表中的id',
  `address` varchar(100) NOT NULL COMMENT '提现地址',
  `score` int(10) unsigned NOT NULL COMMENT '提现积分',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '提取状态',
  `datetime` datetime NOT NULL COMMENT '提取时间',
  PRIMARY KEY (`id`),
  KEY `idx_userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='用户积分提取信息表';

-- ----------------------------
-- Records of gd_user_score_take
-- ----------------------------
INSERT INTO `gd_user_score_take` VALUES ('1', '1', '', '100', '0', '2018-03-28 18:15:31');
INSERT INTO `gd_user_score_take` VALUES ('2', '1', '', '100', '0', '2018-03-28 18:16:21');
INSERT INTO `gd_user_score_take` VALUES ('3', '1', '', '100', '0', '2018-03-28 18:16:22');
INSERT INTO `gd_user_score_take` VALUES ('4', '1', '', '100', '0', '2018-03-28 18:16:23');
INSERT INTO `gd_user_score_take` VALUES ('5', '1', '', '100', '0', '2018-03-28 18:16:24');
INSERT INTO `gd_user_score_take` VALUES ('6', '1', '', '100', '0', '2018-03-28 18:16:24');
INSERT INTO `gd_user_score_take` VALUES ('7', '1', '', '100', '0', '2018-03-28 18:16:25');
INSERT INTO `gd_user_score_take` VALUES ('8', '1', '', '100', '0', '2018-03-28 18:16:26');
INSERT INTO `gd_user_score_take` VALUES ('9', '1', '', '100', '0', '2018-03-28 18:16:26');
INSERT INTO `gd_user_score_take` VALUES ('10', '1', '', '100', '0', '2018-03-28 18:16:27');

-- ----------------------------
-- Table structure for gd_user_token
-- ----------------------------
DROP TABLE IF EXISTS `gd_user_token`;
CREATE TABLE `gd_user_token` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL COMMENT '关联 uesr 表中的id',
  `version` varchar(12) NOT NULL COMMENT '版本号',
  `clientType` tinyint(1) unsigned NOT NULL COMMENT '客户端类型 0 Android 1 IOS 2Wap（H5） 3Web（PC）',
  `network` tinyint(1) unsigned NOT NULL COMMENT '网络类型 0 其他 1 Wifi 2 2G 3 3G 4 4G 5 5G',
  `lang` tinyint(1) unsigned NOT NULL COMMENT '语言 0 中文 1 英文',
  `token` varchar(32) NOT NULL COMMENT 'token',
  `datetime` datetime NOT NULL COMMENT '最新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_userId` (`userId`) USING BTREE,
  UNIQUE KEY `idx_token` (`token`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户 token 信息表';

-- ----------------------------
-- Records of gd_user_token
-- ----------------------------
INSERT INTO `gd_user_token` VALUES ('1', '1', '', '0', '0', '0', 'e45c9f10fcd5bf2f1627fc858803932b', '2018-04-13 11:55:58');
INSERT INTO `gd_user_token` VALUES ('2', '6', '', '0', '0', '0', '59ae1f3af484cdb017cdb75150dcbe88', '2018-03-30 16:32:35');

-- ----------------------------
-- Table structure for gd_user_tool_count
-- ----------------------------
DROP TABLE IF EXISTS `gd_user_tool_count`;
CREATE TABLE `gd_user_tool_count` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL COMMENT '关联 uesr 表中的id',
  `toolId` int(10) unsigned NOT NULL COMMENT '关联 tool 表中的id',
  `num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_userId_toolId` (`userId`,`toolId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='用户道具数量表';

-- ----------------------------
-- Records of gd_user_tool_count
-- ----------------------------
INSERT INTO `gd_user_tool_count` VALUES ('1', '1', '1', '13');
INSERT INTO `gd_user_tool_count` VALUES ('2', '1', '3', '1');
INSERT INTO `gd_user_tool_count` VALUES ('3', '1', '4', '1');
INSERT INTO `gd_user_tool_count` VALUES ('4', '1', '2', '7');
INSERT INTO `gd_user_tool_count` VALUES ('5', '1', '5', '3');
INSERT INTO `gd_user_tool_count` VALUES ('6', '8', '1', '1');

-- ----------------------------
-- Table structure for gd_user_tool_log
-- ----------------------------
DROP TABLE IF EXISTS `gd_user_tool_log`;
CREATE TABLE `gd_user_tool_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL COMMENT '关联 uesr 表中的id',
  `toolId` int(11) unsigned NOT NULL COMMENT '关联 tool 表中的id',
  `changeType` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '变更方式 1 增加 0 减少',
  `changeNum` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '变更数量',
  `oldNum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '原有数量',
  `newNum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '现有数量',
  `content` varchar(50) NOT NULL COMMENT '描述',
  `datetime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_userId_toolId` (`userId`,`toolId`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='用户道具日志信息表';

-- ----------------------------
-- Records of gd_user_tool_log
-- ----------------------------
INSERT INTO `gd_user_tool_log` VALUES ('1', '1', '4', '0', '1', '1', '0', 'tool.tool_anti_theft_used', '2018-04-08 16:44:00');
INSERT INTO `gd_user_tool_log` VALUES ('2', '1', '2', '0', '1', '3', '2', 'tool.tool_repellent_used', '2018-04-08 17:01:51');
INSERT INTO `gd_user_tool_log` VALUES ('3', '1', '3', '0', '1', '2', '1', 'tool.tool_ripener_used', '2018-04-09 11:32:03');
INSERT INTO `gd_user_tool_log` VALUES ('4', '1', '3', '0', '2', '3', '1', 'tool.tool_ripener_used', '2018-04-09 11:32:54');
INSERT INTO `gd_user_tool_log` VALUES ('5', '1', '1', '0', '2', '10', '8', 'tool.tool_fertilizer_used', '2018-04-09 12:03:03');
INSERT INTO `gd_user_tool_log` VALUES ('6', '1', '1', '1', '1', '8', '9', 'tool.package_2_tool', '2018-04-18 18:32:01');
INSERT INTO `gd_user_tool_log` VALUES ('7', '1', '2', '1', '1', '2', '3', 'tool.package_2_tool', '2018-04-18 18:32:01');
INSERT INTO `gd_user_tool_log` VALUES ('8', '1', '1', '1', '1', '9', '10', 'tool.package_2_tool', '2018-04-18 18:38:00');
INSERT INTO `gd_user_tool_log` VALUES ('9', '1', '2', '1', '1', '3', '4', 'tool.package_2_tool', '2018-04-18 18:38:00');
INSERT INTO `gd_user_tool_log` VALUES ('10', '1', '1', '1', '1', '10', '11', 'tool.package_2_tool', '2018-04-19 10:39:06');
INSERT INTO `gd_user_tool_log` VALUES ('11', '1', '2', '1', '1', '4', '5', 'tool.package_2_tool', '2018-04-19 10:39:06');
INSERT INTO `gd_user_tool_log` VALUES ('12', '1', '1', '1', '1', '11', '12', 'tool.package_2_tool', '2018-04-19 10:52:26');
INSERT INTO `gd_user_tool_log` VALUES ('13', '1', '2', '1', '1', '5', '6', 'tool.package_2_tool', '2018-04-19 10:52:26');
INSERT INTO `gd_user_tool_log` VALUES ('14', '8', '1', '1', '1', '0', '1', 'tool.package_2_tool', '2018-05-03 15:42:11');
INSERT INTO `gd_user_tool_log` VALUES ('15', '1', '1', '1', '1', '12', '13', 'tool.package_2_tool', '2018-05-03 15:42:11');
INSERT INTO `gd_user_tool_log` VALUES ('16', '1', '2', '1', '1', '6', '7', 'tool.package_2_tool', '2018-05-03 15:42:11');
INSERT INTO `gd_user_tool_log` VALUES ('17', '1', '4', '1', '1', '0', '1', 'tool.package_2_tool', '2018-05-03 15:42:11');

-- ----------------------------
-- Table structure for gd_user_tree
-- ----------------------------
DROP TABLE IF EXISTS `gd_user_tree`;
CREATE TABLE `gd_user_tree` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL COMMENT '关联 uesr 表中的id',
  `matureTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '果树成熟时间',
  `waterTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '果树浇水时间',
  `dryTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '干旱时间',
  `wormyTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '生虫时间',
  `antiTheftTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '防盗结束时间',
  `matureFruit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '成熟果子',
  `stealFruit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '偷窃的果子',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_userId` (`userId`),
  KEY `idx_matureTime` (`matureTime`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='用户果树信息表';

-- ----------------------------
-- Records of gd_user_tree
-- ----------------------------
INSERT INTO `gd_user_tree` VALUES ('1', '1', '0000-00-00 00:00:00', '2018-04-18 11:11:08', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2018-04-11 16:44:00', '220', '0');
INSERT INTO `gd_user_tree` VALUES ('2', '2', '2018-03-29 18:24:51', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '20', '2');
INSERT INTO `gd_user_tree` VALUES ('3', '3', '2018-03-29 18:24:51', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '20', '1');
INSERT INTO `gd_user_tree` VALUES ('4', '4', '2018-04-18 11:12:31', '2018-03-30 16:04:26', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2018-04-18 16:16:17', '50', '4');
INSERT INTO `gd_user_tree` VALUES ('5', '5', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '0');
INSERT INTO `gd_user_tree` VALUES ('6', '6', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '0');
INSERT INTO `gd_user_tree` VALUES ('7', '7', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '0');
INSERT INTO `gd_user_tree` VALUES ('8', '8', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '0');

-- ----------------------------
-- Table structure for gd_user_tree_fruit
-- ----------------------------
DROP TABLE IF EXISTS `gd_user_tree_fruit`;
CREATE TABLE `gd_user_tree_fruit` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL COMMENT '关联 uesr 表中的id',
  `fertTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '果树施肥时间',
  `isMature` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已成熟 1 已成熟 0 未成熟',
  `matureTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '果树成熟时间',
  `matureFruit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '果树成熟果子',
  PRIMARY KEY (`id`),
  KEY `idx_userId_isMature` (`userId`,`isMature`) USING BTREE,
  KEY `idx_isMature_matureTime` (`isMature`,`matureTime`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='果树果子信息表';

-- ----------------------------
-- Records of gd_user_tree_fruit
-- ----------------------------
INSERT INTO `gd_user_tree_fruit` VALUES ('1', '1', '2018-04-09 10:32:32', '1', '2018-04-09 14:25:14', '20');
INSERT INTO `gd_user_tree_fruit` VALUES ('2', '1', '2018-04-09 01:32:32', '1', '2018-04-09 13:17:44', '20');
INSERT INTO `gd_user_tree_fruit` VALUES ('3', '1', '2018-04-09 12:03:03', '1', '2018-04-11 12:03:03', '40');

-- ----------------------------
-- Table structure for gd_user_tree_log
-- ----------------------------
DROP TABLE IF EXISTS `gd_user_tree_log`;
CREATE TABLE `gd_user_tree_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(10) unsigned NOT NULL COMMENT '关联 uesr 表中的id',
  `matureFruit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '成熟果子',
  `dryFruit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '干旱果子(减少)',
  `wormyFruit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虫害果子(减少)',
  `oldTreeFruit` int(10) unsigned NOT NULL COMMENT '树上果子(旧的)',
  `newTreeFruit` int(10) unsigned NOT NULL COMMENT '树上果子(新的)',
  `datetime` datetime NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `idx_userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='果树日志信息表';

-- ----------------------------
-- Records of gd_user_tree_log
-- ----------------------------
INSERT INTO `gd_user_tree_log` VALUES ('1', '1', '20', '0', '0', '0', '20', '2018-04-24 16:33:02');
INSERT INTO `gd_user_tree_log` VALUES ('2', '1', '20', '0', '0', '20', '40', '2018-04-24 16:33:02');
INSERT INTO `gd_user_tree_log` VALUES ('3', '1', '40', '0', '0', '40', '80', '2018-04-24 16:33:02');
INSERT INTO `gd_user_tree_log` VALUES ('4', '1', '20', '0', '0', '80', '100', '2018-04-24 16:34:23');
INSERT INTO `gd_user_tree_log` VALUES ('5', '1', '40', '0', '0', '100', '140', '2018-04-24 16:34:23');
INSERT INTO `gd_user_tree_log` VALUES ('6', '1', '20', '0', '0', '140', '160', '2018-04-24 16:54:38');
INSERT INTO `gd_user_tree_log` VALUES ('7', '1', '20', '0', '0', '160', '180', '2018-04-24 16:54:38');
INSERT INTO `gd_user_tree_log` VALUES ('8', '1', '40', '0', '0', '180', '220', '2018-04-24 16:54:38');
SET FOREIGN_KEY_CHECKS=1;
