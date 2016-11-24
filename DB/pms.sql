/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50624
Source Host           : localhost:3306
Source Database       : pms

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2016-10-06 08:49:15
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for answer
-- ----------------------------
DROP TABLE IF EXISTS `answer`;
CREATE TABLE `answer` (
  `answer_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '答案',
  `question_id` varchar(10) NOT NULL COMMENT '问题id',
  `answer` text NOT NULL COMMENT '答案',
  `play_time` int(6) DEFAULT '0' COMMENT '播放时间',
  `inclination_id` varchar(10) DEFAULT NULL COMMENT '倾向性',
  `score` tinyint(3) NOT NULL COMMENT '答案分值',
  `comment` varchar(50) NOT NULL COMMENT '评语',
  PRIMARY KEY (`answer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of answer
-- ----------------------------
INSERT INTO `answer` VALUES ('1', '1', '父母或助学金', '0', '1', '1', '缺乏独立性');
INSERT INTO `answer` VALUES ('2', '1', '兼职打工', '0', '2', '3', '具备一定独立生活能力，但挣钱之能还不够');
INSERT INTO `answer` VALUES ('3', '1', '奖学金', '0', '3', '3', '具备一定学习能力');
INSERT INTO `answer` VALUES ('4', '1', '创业', '0', '3', '5', '具备挣钱之能');
INSERT INTO `answer` VALUES ('5', '2', '随便听听、看看，无所谓\r\n随便听听、看看，无所谓', '0', '2', '0', '没想法');
INSERT INTO `answer` VALUES ('6', '2', '只关注重大事件', '0', '1', '1', '有所选择');
INSERT INTO `answer` VALUES ('7', '2', '只关注自己感兴趣的领域', '0', '3', '3', '有自己的兴趣');
INSERT INTO `answer` VALUES ('8', '2', '经常主动、有目的地关注', '0', '4', '5', '有明确的目标并在努力');
INSERT INTO `answer` VALUES ('9', '3', '33333', '0', '3', '3', '33333');
INSERT INTO `answer` VALUES ('10', '3', '44444', '0', '4', '4', '4444');
INSERT INTO `answer` VALUES ('11', '3', '5555', '0', '5', '5', '5555');
INSERT INTO `answer` VALUES ('12', '3', '666', '0', '1', '3', '213214');
INSERT INTO `answer` VALUES ('13', '4', '444444', '0', '4', '4', '4');
INSERT INTO `answer` VALUES ('14', '4', '43', '0', '3', '3', '4444444');
INSERT INTO `answer` VALUES ('15', '4', '42', '0', '2', '3', '24233');
INSERT INTO `answer` VALUES ('16', '4', '41', '0', '1', '1', '1232');
INSERT INTO `answer` VALUES ('17', '5', '54', '0', '3', '3', '5');
INSERT INTO `answer` VALUES ('18', '5', '53', '0', '3', '2', '4');
INSERT INTO `answer` VALUES ('19', '5', '52', '0', '2', '1', '3');
INSERT INTO `answer` VALUES ('20', '5', '51', '0', '1', '1', '2');
INSERT INTO `answer` VALUES ('21', '6', '好听', '0', '2', '3', '好啊');
INSERT INTO `answer` VALUES ('22', '6', '不好听', '0', '3', '3', '可以');
INSERT INTO `answer` VALUES ('23', '', '还可以', '0', '2', '4', '鹅');

-- ----------------------------
-- Table structure for answer_sheet
-- ----------------------------
DROP TABLE IF EXISTS `answer_sheet`;
CREATE TABLE `answer_sheet` (
  `answer_sheet_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '答卷',
  `user_id` varchar(10) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=个人，2=团队',
  `level_id` varchar(10) NOT NULL COMMENT '基础难度',
  `start_time` datetime NOT NULL COMMENT '答题开始时间',
  `answer_time` int(10) DEFAULT NULL COMMENT '答题用时',
  `last_time` datetime NOT NULL COMMENT '上一次答题时间',
  `answers` tinyint(3) NOT NULL COMMENT '答题数量',
  `score` int(5) DEFAULT '0' COMMENT '实际总得分',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '答题状态1=答题中，2=完成，3=用户取消',
  `flag` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否放弃',
  `order_id` varchar(10) DEFAULT NULL COMMENT '订单id',
  PRIMARY KEY (`answer_sheet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of answer_sheet
-- ----------------------------
INSERT INTO `answer_sheet` VALUES ('113', '1', '1', '1', '2016-10-03 12:00:21', null, '0000-00-00 00:00:00', '2', '2', '2', '1', null);
INSERT INTO `answer_sheet` VALUES ('114', '1', '1', '1', '2016-10-03 12:04:16', null, '0000-00-00 00:00:00', '2', '2', '2', '1', null);
INSERT INTO `answer_sheet` VALUES ('115', '1', '1', '1', '2016-10-03 12:06:51', null, '0000-00-00 00:00:00', '2', '0', '1', '1', null);
INSERT INTO `answer_sheet` VALUES ('116', '1', '1', '1', '2016-10-03 12:08:33', null, '0000-00-00 00:00:00', '2', '0', '1', '1', null);
INSERT INTO `answer_sheet` VALUES ('117', '1', '1', '1', '2016-10-03 12:08:44', null, '0000-00-00 00:00:00', '2', '0', '1', '1', null);
INSERT INTO `answer_sheet` VALUES ('118', '1', '1', '1', '2016-10-03 12:09:02', null, '0000-00-00 00:00:00', '2', '0', '1', '1', null);
INSERT INTO `answer_sheet` VALUES ('119', '1', '1', '1', '2016-10-03 12:12:19', null, '0000-00-00 00:00:00', '2', '0', '1', '1', null);
INSERT INTO `answer_sheet` VALUES ('120', '1', '1', '1', '2016-10-03 12:14:57', null, '0000-00-00 00:00:00', '2', '0', '1', '1', null);
INSERT INTO `answer_sheet` VALUES ('121', '1', '1', '1', '2016-10-03 12:15:34', '96', '2016-10-03 12:18:25', '2', '2', '2', '1', null);

-- ----------------------------
-- Table structure for bespeak
-- ----------------------------
DROP TABLE IF EXISTS `bespeak`;
CREATE TABLE `bespeak` (
  `bespeak_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '预约表',
  `user_id` varchar(10) NOT NULL,
  `test_id` varchar(10) NOT NULL COMMENT '测试人员id',
  `bespeak_time` datetime NOT NULL COMMENT '预约时间',
  `bespeak_cost` decimal(10,2) NOT NULL COMMENT '预约费用',
  PRIMARY KEY (`bespeak_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of bespeak
-- ----------------------------

-- ----------------------------
-- Table structure for charge
-- ----------------------------
DROP TABLE IF EXISTS `charge`;
CREATE TABLE `charge` (
  `charge_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '收费标准',
  `charge_type` tinyint(3) NOT NULL COMMENT '收费类型',
  `charge_cost` decimal(10,2) NOT NULL COMMENT '费用',
  `created` datetime NOT NULL,
  `member_id` varchar(10) NOT NULL COMMENT '添加人',
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`charge_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of charge
-- ----------------------------

-- ----------------------------
-- Table structure for classify
-- ----------------------------
DROP TABLE IF EXISTS `classify`;
CREATE TABLE `classify` (
  `classify_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '题目分类',
  `classify_name` varchar(40) NOT NULL COMMENT '分类名',
  `father_id` varchar(10) DEFAULT '0' COMMENT '大类id',
  `level` tinyint(3) NOT NULL DEFAULT '1' COMMENT '分类等级1=大类，2=小类',
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`classify_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of classify
-- ----------------------------
INSERT INTO `classify` VALUES ('4', '火', '0', '1', '1');
INSERT INTO `classify` VALUES ('5', '土', '0', '1', '1');
INSERT INTO `classify` VALUES ('8', '土1', '5', '2', '1');
INSERT INTO `classify` VALUES ('10', '火1', '4', '2', '1');

-- ----------------------------
-- Table structure for classify_sheet
-- ----------------------------
DROP TABLE IF EXISTS `classify_sheet`;
CREATE TABLE `classify_sheet` (
  `classify_sheet_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '分类答卷',
  `classify_id` varchar(10) NOT NULL COMMENT '分类id',
  `answer_sheet_id` varchar(10) NOT NULL COMMENT '答卷id',
  `answers` tinyint(3) NOT NULL COMMENT '已回答数',
  `sheet_id` varchar(10) DEFAULT NULL COMMENT '正在回答的答题id',
  `correct` tinyint(3) DEFAULT '0' COMMENT '连续正确数',
  `level_id` varchar(10) DEFAULT NULL COMMENT '难度等级',
  `difficulty` tinyint(1) NOT NULL DEFAULT '0' COMMENT '当前难度',
  `score` int(5) NOT NULL COMMENT '得分',
  `is_answer` tinyint(1) DEFAULT '0' COMMENT '是否正在答题',
  PRIMARY KEY (`classify_sheet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=443 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of classify_sheet
-- ----------------------------
INSERT INTO `classify_sheet` VALUES ('425', '8', '113', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('426', '10', '113', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('427', '8', '114', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('428', '10', '114', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('429', '8', '115', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('430', '10', '115', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('431', '8', '116', '0', null, '0', '1', '3', '0', '0');
INSERT INTO `classify_sheet` VALUES ('432', '10', '116', '0', null, '0', '1', '3', '0', '0');
INSERT INTO `classify_sheet` VALUES ('433', '8', '117', '0', null, '0', '1', '3', '0', '0');
INSERT INTO `classify_sheet` VALUES ('434', '10', '117', '0', null, '0', '1', '3', '0', '0');
INSERT INTO `classify_sheet` VALUES ('435', '8', '118', '0', null, '0', '1', '3', '0', '0');
INSERT INTO `classify_sheet` VALUES ('436', '10', '118', '0', null, '0', '1', '3', '0', '0');
INSERT INTO `classify_sheet` VALUES ('437', '8', '119', '0', null, '0', '1', '3', '0', '0');
INSERT INTO `classify_sheet` VALUES ('438', '10', '119', '0', null, '0', '1', '3', '0', '0');
INSERT INTO `classify_sheet` VALUES ('439', '8', '120', '0', null, '0', '1', '3', '0', '0');
INSERT INTO `classify_sheet` VALUES ('440', '10', '120', '0', null, '0', '1', '3', '0', '0');
INSERT INTO `classify_sheet` VALUES ('441', '8', '121', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('442', '10', '121', '2', null, '-2', '1', '2', '0', '1');

-- ----------------------------
-- Table structure for cost
-- ----------------------------
DROP TABLE IF EXISTS `cost`;
CREATE TABLE `cost` (
  `cost_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '缴费清单',
  `order_id` varchar(10) NOT NULL COMMENT '订单id',
  `user_id` varchar(10) NOT NULL,
  `cost_money` decimal(10,2) NOT NULL COMMENT '缴费费用',
  `cost_type` tinyint(3) NOT NULL COMMENT '缴费类型',
  `cost_way` tinyint(3) DEFAULT '0' COMMENT '支付方式',
  PRIMARY KEY (`cost_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cost
-- ----------------------------

-- ----------------------------
-- Table structure for file
-- ----------------------------
DROP TABLE IF EXISTS `file`;
CREATE TABLE `file` (
  `file_id` int(10) NOT NULL AUTO_INCREMENT,
  `file_path` varchar(255) NOT NULL,
  `file_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-图片，2-音频，3-视频',
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of file
-- ----------------------------

-- ----------------------------
-- Table structure for inclination
-- ----------------------------
DROP TABLE IF EXISTS `inclination`;
CREATE TABLE `inclination` (
  `inclination_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '偏向性',
  `inclination` varchar(100) NOT NULL COMMENT '偏向性',
  `remark` varchar(100) DEFAULT NULL COMMENT '备注',
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`inclination_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of inclination
-- ----------------------------
INSERT INTO `inclination` VALUES ('1', '偏向1', null, '1');
INSERT INTO `inclination` VALUES ('2', '2', null, '1');
INSERT INTO `inclination` VALUES ('3', '3', null, '1');
INSERT INTO `inclination` VALUES ('4', '4', null, '1');
INSERT INTO `inclination` VALUES ('5', '5', null, '1');

-- ----------------------------
-- Table structure for level
-- ----------------------------
DROP TABLE IF EXISTS `level`;
CREATE TABLE `level` (
  `level_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '等级',
  `level_name` varchar(20) DEFAULT NULL COMMENT '等级名',
  `max_age` tinyint(3) DEFAULT NULL COMMENT '最大年龄',
  `min_age` tinyint(3) DEFAULT NULL COMMENT '最小年龄',
  `sort` tinyint(3) DEFAULT NULL COMMENT '排序',
  `answer_num` tinyint(3) DEFAULT NULL COMMENT '答题数量',
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of level
-- ----------------------------
INSERT INTO `level` VALUES ('1', '全部', '100', '1', '1', '2', '1');

-- ----------------------------
-- Table structure for member
-- ----------------------------
DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `member_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '管理员',
  `member_name` varchar(40) NOT NULL COMMENT '用户名',
  `password` varchar(40) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否超级管理员',
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of member
-- ----------------------------

-- ----------------------------
-- Table structure for msg
-- ----------------------------
DROP TABLE IF EXISTS `msg`;
CREATE TABLE `msg` (
  `msg_key` varchar(60) NOT NULL DEFAULT '',
  `code` int(6) DEFAULT NULL,
  `msg_time` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`msg_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of msg
-- ----------------------------
INSERT INTO `msg` VALUES ('18628803303', '850750', '1474287983');
INSERT INTO `msg` VALUES ('13541319025', '182177', '1474376466');

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `order_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '订单表',
  `order_no` varchar(60) NOT NULL COMMENT '订单编号',
  `order_type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '订单类型',
  `order_time` datetime NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `order_cost` decimal(10,2) NOT NULL COMMENT '订单费用',
  `is_pay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支付',
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of order
-- ----------------------------
INSERT INTO `order` VALUES ('1', '', '1', '2016-09-25 17:46:24', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('2', 'WCGR18CT-20160925-0001', '1', '2016-09-25 19:31:36', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('3', 'WCGR18CT-20161002-0000', '1', '2016-10-02 14:05:38', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('4', 'WCGR18CT-20161002-0001', '1', '2016-10-02 23:54:32', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('5', 'WCGR18CT-20161002-0002', '1', '2016-10-02 23:55:19', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('6', 'WCGR18CT-20161003-0000', '1', '2016-10-03 00:03:49', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('7', 'WCGR18CT-20161003-0001', '1', '2016-10-03 00:11:31', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('8', 'WCGR18CT-20161003-0002', '1', '2016-10-03 00:13:46', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('9', 'WCGR18CT-20161003-0003', '1', '2016-10-03 00:16:20', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('10', 'WCGR18CT-20161003-0004', '1', '2016-10-03 00:22:32', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('11', 'WCGR18CT-20161003-0005', '1', '2016-10-03 00:31:38', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('12', 'WCGR18CT-20161003-0006', '1', '2016-10-03 00:41:38', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('13', 'WCGR18CT-20161003-0007', '1', '2016-10-03 00:45:17', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('14', 'WCGR18CT-20161003-0008', '1', '2016-10-03 01:03:55', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('15', 'WCGR18CT-20161003-0009', '1', '2016-10-03 01:08:28', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('16', 'WCGR18CT-20161003-0010', '1', '2016-10-03 01:31:41', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('17', 'WCGR18CT-20161003-0011', '1', '2016-10-03 01:37:30', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('18', 'WCGR18CT-20161003-0012', '1', '2016-10-03 01:38:09', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('19', 'WCGR18CT-20161003-0013', '1', '2016-10-03 01:41:25', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('20', 'WCGR18CT-20161003-0014', '1', '2016-10-03 01:42:36', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('21', 'WCGR18CT-20161003-0015', '1', '2016-10-03 01:42:55', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('22', 'WCGR18CT-20161003-0016', '1', '2016-10-03 01:44:36', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('23', 'WCGR18CT-20161003-0017', '1', '2016-10-03 01:44:54', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('24', 'WCGR18CT-20161003-0018', '1', '2016-10-03 01:48:28', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('25', 'WCGR18CT-20161003-0019', '1', '2016-10-03 01:51:18', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('26', 'WCGR18CT-20161003-0020', '1', '2016-10-03 01:53:05', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('27', 'WCGR18CT-20161003-0021', '1', '2016-10-03 01:53:48', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('28', 'WCGR18CT-20161003-0022', '1', '2016-10-03 10:47:45', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('29', 'WCGR18CT-20161003-0023', '1', '2016-10-03 11:53:15', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('30', 'WCGR18CT-20161003-0024', '1', '2016-10-03 11:56:00', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('31', 'WCGR18CT-20161003-0025', '1', '2016-10-03 11:56:13', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('32', 'WCGR18CT-20161003-0026', '1', '2016-10-03 11:58:43', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('33', 'WCGR18CT-20161003-0027', '1', '2016-10-03 12:00:21', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('34', 'WCGR18CT-20161003-0028', '1', '2016-10-03 12:04:16', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('35', 'WCGR18CT-20161003-0029', '1', '2016-10-03 12:06:51', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('36', 'WCGR18CT-20161003-0030', '1', '2016-10-03 12:08:33', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('37', 'WCGR18CT-20161003-0031', '1', '2016-10-03 12:08:44', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('38', 'WCGR18CT-20161003-0032', '1', '2016-10-03 12:09:02', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('39', 'WCGR18CT-20161003-0033', '1', '2016-10-03 12:12:19', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('40', 'WCGR18CT-20161003-0034', '1', '2016-10-03 12:14:57', '1', '0.00', '1', '1');
INSERT INTO `order` VALUES ('41', 'WCGR18CT-20161003-0035', '1', '2016-10-03 12:15:34', '1', '0.00', '1', '1');

-- ----------------------------
-- Table structure for question
-- ----------------------------
DROP TABLE IF EXISTS `question`;
CREATE TABLE `question` (
  `question_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '问题表',
  `classify_id` varchar(10) NOT NULL COMMENT '问题类型',
  `question` text NOT NULL COMMENT '问题',
  `level_id` varchar(10) NOT NULL COMMENT '问题等级',
  `file` varchar(255) DEFAULT NULL COMMENT '文件路径',
  `play_time` int(6) DEFAULT NULL COMMENT '播放时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '答题类型，0=文字，1=图片，2=语言，3=视频',
  `difficulty` tinyint(3) NOT NULL COMMENT '难度',
  `created` datetime DEFAULT NULL,
  `create_member` varchar(10) NOT NULL COMMENT '创建人',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  `update_member` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '修改人',
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of question
-- ----------------------------
INSERT INTO `question` VALUES ('1', '8', '你的生活费来自', '1', null, null, '0', '3', null, '', null, null, '1');
INSERT INTO `question` VALUES ('2', '10', '你关注新闻吗', '1', null, null, '0', '3', null, '', null, null, '1');
INSERT INTO `question` VALUES ('3', '8', '测试问题1', '1', null, null, '0', '3', null, '', null, null, '1');
INSERT INTO `question` VALUES ('4', '8', '测试问题2', '1', 'http://pms.dev.com/Upload/voide/mp4.mp4', null, '3', '3', null, '', null, null, '1');
INSERT INTO `question` VALUES ('5', '10', '测试问题3', '1', 'http://pms.dev.com/Upload/voide/mp4.mp4', null, '3', '3', null, '', null, null, '1');
INSERT INTO `question` VALUES ('6', '10', '听音乐', '1', '/Upload/music/music.mp3', '0', '2', '3', null, '', null, null, '1');

-- ----------------------------
-- Table structure for sheet
-- ----------------------------
DROP TABLE IF EXISTS `sheet`;
CREATE TABLE `sheet` (
  `sheet_id` int(10) NOT NULL AUTO_INCREMENT,
  `answer_sheet_id` varchar(10) NOT NULL COMMENT '答卷id',
  `classify_sheet_id` varchar(10) NOT NULL COMMENT '分类答卷',
  `question_id` varchar(10) NOT NULL COMMENT '答题问题',
  `answer_id` varchar(10) DEFAULT NULL COMMENT '答题答案',
  `score` tinyint(3) DEFAULT '0' COMMENT '本题得分',
  `inclination_id` varchar(10) DEFAULT NULL COMMENT '偏向性',
  `updatetime` datetime DEFAULT NULL,
  `is_answer` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已答',
  PRIMARY KEY (`sheet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=268 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sheet
-- ----------------------------
INSERT INTO `sheet` VALUES ('252', '113', '426', '3', '12', '0', '12', '2016-10-03 12:02:57', '1');
INSERT INTO `sheet` VALUES ('253', '113', '426', '4', '12', '0', '12', '2016-10-03 12:03:08', '1');
INSERT INTO `sheet` VALUES ('254', '113', '425', '4', '12', '0', '12', '2016-10-03 12:03:17', '1');
INSERT INTO `sheet` VALUES ('255', '113', '425', '5', '12', '0', '12', '2016-10-03 12:03:20', '1');
INSERT INTO `sheet` VALUES ('256', '114', '427', '1', '3', '0', '3', '2016-10-03 12:04:21', '1');
INSERT INTO `sheet` VALUES ('257', '114', '428', '5', '3', '0', '3', '2016-10-03 12:04:36', '1');
INSERT INTO `sheet` VALUES ('258', '114', '428', '6', '3', '0', '3', '2016-10-03 12:04:58', '1');
INSERT INTO `sheet` VALUES ('259', '114', '427', '5', '3', '0', '3', '2016-10-03 12:06:42', '1');
INSERT INTO `sheet` VALUES ('260', '115', '430', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('261', '115', '429', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('262', '115', '430', '6', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('263', '115', '429', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('264', '121', '442', '1', '1', '0', '1', '2016-10-03 12:18:19', '1');
INSERT INTO `sheet` VALUES ('265', '121', '442', '5', '1', '0', '1', '2016-10-03 12:18:22', '1');
INSERT INTO `sheet` VALUES ('266', '121', '441', '3', '10', '0', '10', '2016-10-03 12:18:23', '1');
INSERT INTO `sheet` VALUES ('267', '121', '441', '6', '22', '0', '22', '2016-10-03 12:18:25', '1');

-- ----------------------------
-- Table structure for team
-- ----------------------------
DROP TABLE IF EXISTS `team`;
CREATE TABLE `team` (
  `team_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '团体编号',
  `team_name` varchar(100) NOT NULL COMMENT '公司名',
  `nature` tinyint(3) NOT NULL COMMENT '性质',
  `attribute` tinyint(3) NOT NULL COMMENT '行业属性',
  `code` varchar(6) NOT NULL COMMENT '邀请码',
  `team_user` varchar(10) NOT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of team
-- ----------------------------
INSERT INTO `team` VALUES ('1', '第一个', '2', '1', '123456', '1');

-- ----------------------------
-- Table structure for team_user
-- ----------------------------
DROP TABLE IF EXISTS `team_user`;
CREATE TABLE `team_user` (
  `team_id` varchar(10) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `created` datetime NOT NULL COMMENT '加入时间',
  PRIMARY KEY (`team_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of team_user
-- ----------------------------
INSERT INTO `team_user` VALUES ('1', '1', '0000-00-00 00:00:00');
INSERT INTO `team_user` VALUES ('1', '3', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for test
-- ----------------------------
DROP TABLE IF EXISTS `test`;
CREATE TABLE `test` (
  `test_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '测试人员-待定',
  `pwd` varchar(40) NOT NULL,
  `name` varchar(20) NOT NULL COMMENT '测试人员名',
  `create` datetime NOT NULL,
  `member_id` varchar(10) NOT NULL,
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`test_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of test
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户表',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `name` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `sex` tinyint(1) DEFAULT NULL COMMENT '性别',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机',
  `work_id` varchar(11) DEFAULT NULL COMMENT '职业',
  `ompany_id` varchar(11) DEFAULT NULL COMMENT '单位',
  `email` varchar(40) DEFAULT NULL COMMENT '邮箱',
  `weixin` varchar(40) DEFAULT NULL COMMENT '微信',
  `qq` varchar(15) DEFAULT NULL COMMENT 'qq',
  `birth` date DEFAULT NULL COMMENT '出生年月',
  `from_add` varchar(50) DEFAULT NULL COMMENT '来源地0=城镇，1=乡村',
  `reg_time` datetime NOT NULL COMMENT '注册时间',
  `update_time` datetime DEFAULT NULL,
  `login_err` tinyint(1) NOT NULL DEFAULT '0' COMMENT '密码错误次数',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户状态0=注册未完成，1=正常，2=锁定，9=封禁',
  `answer` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有答题',
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  `team_id` varchar(255) DEFAULT NULL COMMENT '团队id',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'cb876a1e38befad13143e2be953b644a', 'slowly', '1', '13541319025', '1', '1', '396231662@qq.com', null, null, '1989-06-06', '1', '0000-00-00 00:00:00', '2016-10-03 12:18:25', '0', '1', '0', '1', '1');
INSERT INTO `user` VALUES ('2', '6d5da75f1a593e472e65e985579460ae', 'slowly', '0', '13541319033', '2', null, null, '', '', '2010-03-07', '', '2016-07-07 22:38:47', '2016-07-07 22:50:13', '0', '1', '1', '1', '1');
INSERT INTO `user` VALUES ('3', '', null, null, '13541319066', '3', null, null, null, null, null, null, '2016-07-07 22:59:33', null, '0', '0', '0', '1', '1');
INSERT INTO `user` VALUES ('4', 'e07c99301f39735b510430c6e53843b9', 'slowly', '1', '13541319027', '4', null, null, '', '', '0000-00-00', '', '2016-09-06 21:54:58', '2016-09-06 22:08:29', '0', '1', '0', '1', '1');
INSERT INTO `user` VALUES ('5', '43e38d21f04c0a62df067c3227ef245e', 'aaa', '0', '18628803303', '', null, null, '', '', '0000-00-00', '', '2016-09-21 00:10:37', '2016-09-21 00:26:47', '0', '1', '0', '1', null);

-- ----------------------------
-- Table structure for work
-- ----------------------------
DROP TABLE IF EXISTS `work`;
CREATE TABLE `work` (
  `work_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '职业',
  `name` varchar(20) DEFAULT NULL COMMENT '职业名',
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`work_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of work
-- ----------------------------
INSERT INTO `work` VALUES ('1', '军人', '1');
INSERT INTO `work` VALUES ('2', '教师', '1');
INSERT INTO `work` VALUES ('3', '工人', '1');
INSERT INTO `work` VALUES ('4', '农民', '1');
INSERT INTO `work` VALUES ('5', '其他', '1');
