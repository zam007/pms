/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : pms

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-09-15 01:32:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `answer`
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

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

-- ----------------------------
-- Table structure for `answer_sheet`
-- ----------------------------
DROP TABLE IF EXISTS `answer_sheet`;
CREATE TABLE `answer_sheet` (
  `answer_sheet_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '答卷',
  `user_id` varchar(10) NOT NULL,
  `level_id` varchar(10) NOT NULL COMMENT '基础难度',
  `start_time` datetime NOT NULL COMMENT '答题开始时间',
  `answer_time` int(10) DEFAULT NULL COMMENT '答题用时',
  `last_time` int(10) NOT NULL COMMENT '上一次答题时间',
  `answers` tinyint(3) NOT NULL COMMENT '答题数量',
  `score` int(5) DEFAULT '0' COMMENT '实际总得分',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '答题状态1=答题中，2=完成，3=用户取消',
  `flag` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否放弃',
  PRIMARY KEY (`answer_sheet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of answer_sheet
-- ----------------------------
INSERT INTO `answer_sheet` VALUES ('69', '1', '1', '2016-09-11 15:22:02', '90', '2016', '2', '2', '2', '1');
INSERT INTO `answer_sheet` VALUES ('70', '1', '1', '2016-09-11 15:25:14', '90', '2016', '2', '2', '2', '1');
INSERT INTO `answer_sheet` VALUES ('71', '1', '1', '2016-09-11 15:25:44', '90', '2016', '2', '2', '2', '1');
INSERT INTO `answer_sheet` VALUES ('72', '1', '1', '2016-09-11 15:28:33', '90', '2016', '2', '2', '2', '1');
INSERT INTO `answer_sheet` VALUES ('73', '1', '1', '2016-09-11 15:29:57', '90', '2016', '2', '2', '2', '1');
INSERT INTO `answer_sheet` VALUES ('74', '1', '1', '2016-09-11 15:30:09', '90', '2016', '2', '2', '2', '1');
INSERT INTO `answer_sheet` VALUES ('75', '1', '1', '2016-09-11 15:34:07', '90', '2016', '2', '2', '2', '1');
INSERT INTO `answer_sheet` VALUES ('76', '1', '1', '2016-09-11 15:35:02', '90', '2016', '2', '2', '2', '1');
INSERT INTO `answer_sheet` VALUES ('77', '1', '1', '2016-09-11 15:36:39', '90', '2016', '2', '2', '2', '1');
INSERT INTO `answer_sheet` VALUES ('78', '1', '1', '2016-09-11 15:42:01', '90', '2016', '2', '2', '2', '1');
INSERT INTO `answer_sheet` VALUES ('79', '1', '1', '2016-09-11 15:42:48', '90', '2016', '2', '2', '2', '1');
INSERT INTO `answer_sheet` VALUES ('80', '1', '1', '2016-09-11 15:44:46', '90', '2016', '2', '2', '2', '1');

-- ----------------------------
-- Table structure for `bespeak`
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
-- Table structure for `charge`
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
-- Table structure for `classify`
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
-- Table structure for `classify_sheet`
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
) ENGINE=InnoDB AUTO_INCREMENT=361 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of classify_sheet
-- ----------------------------
INSERT INTO `classify_sheet` VALUES ('289', '', '45', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('290', '', '45', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('291', '', '46', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('292', '', '46', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('293', '', '47', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('294', '', '47', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('295', '8', '48', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('296', '10', '48', '2', null, '-1', '1', '0', '0', '1');
INSERT INTO `classify_sheet` VALUES ('297', '8', '49', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('298', '10', '49', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('299', '8', '50', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('300', '10', '50', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('301', '8', '51', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('302', '10', '51', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('303', '8', '52', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('304', '10', '52', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('305', '8', '53', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('306', '10', '53', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('307', '8', '54', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('308', '10', '54', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('309', '8', '55', '2', null, '-1', '1', '0', '0', '1');
INSERT INTO `classify_sheet` VALUES ('310', '10', '55', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('311', '8', '56', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('312', '10', '56', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('313', '8', '57', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('314', '10', '57', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('315', '8', '58', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('316', '10', '58', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('317', '8', '59', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('318', '10', '59', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('319', '8', '60', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('320', '10', '60', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('321', '8', '61', '2', null, '-1', '1', '0', '0', '1');
INSERT INTO `classify_sheet` VALUES ('322', '10', '61', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('323', '8', '62', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('324', '10', '62', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('325', '8', '63', '2', null, '0', '1', '3', '0', '1');
INSERT INTO `classify_sheet` VALUES ('326', '10', '63', '2', null, '-1', '1', '0', '0', '1');
INSERT INTO `classify_sheet` VALUES ('327', '8', '64', '2', null, '-1', '1', '0', '0', '1');
INSERT INTO `classify_sheet` VALUES ('328', '10', '64', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('329', '8', '65', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('330', '10', '65', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('331', '8', '66', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('332', '10', '66', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('333', '8', '67', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('334', '10', '67', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('335', '8', '68', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('336', '10', '68', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('337', '8', '69', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('338', '10', '69', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('339', '8', '70', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('340', '10', '70', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('341', '8', '71', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('342', '10', '71', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('343', '8', '72', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('344', '10', '72', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('345', '8', '73', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('346', '10', '73', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('347', '8', '74', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('348', '10', '74', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('349', '8', '75', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('350', '10', '75', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('351', '8', '76', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('352', '10', '76', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('353', '8', '77', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('354', '10', '77', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('355', '8', '78', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('356', '10', '78', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('357', '8', '79', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('358', '10', '79', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('359', '8', '80', '2', null, '-2', null, '5', '0', '1');
INSERT INTO `classify_sheet` VALUES ('360', '10', '80', '2', null, '-2', null, '5', '0', '1');

-- ----------------------------
-- Table structure for `cost`
-- ----------------------------
DROP TABLE IF EXISTS `cost`;
CREATE TABLE `cost` (
  `cost_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '缴费清单',
  `order_id` varchar(10) NOT NULL COMMENT '订单id',
  `user_id` varchar(10) NOT NULL,
  `cost_money` decimal(10,2) NOT NULL COMMENT '缴费费用',
  `cost_type` tinyint(3) NOT NULL COMMENT '缴费类型',
  PRIMARY KEY (`cost_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cost
-- ----------------------------

-- ----------------------------
-- Table structure for `file`
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
-- Table structure for `inclination`
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
-- Table structure for `level`
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
-- Table structure for `member`
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
-- Table structure for `order`
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `order_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '订单表',
  `order_type` tinyint(3) NOT NULL COMMENT '订单类型',
  `order_time` datetime NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `order_cost` decimal(10,2) NOT NULL COMMENT '订单费用',
  `is_pay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支付',
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of order
-- ----------------------------

-- ----------------------------
-- Table structure for `question`
-- ----------------------------
DROP TABLE IF EXISTS `question`;
CREATE TABLE `question` (
  `question_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '问题表',
  `classify_id` varchar(10) NOT NULL COMMENT '问题类型',
  `question` text NOT NULL COMMENT '问题',
  `level_id` varchar(10) NOT NULL COMMENT '问题等级',
  `file` varchar(255) DEFAULT NULL COMMENT '文件路径',
  `play_time` datetime DEFAULT NULL COMMENT '播放时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '答题类型，0=文字，1=图片，2=语言，3=视频',
  `difficulty` tinyint(3) NOT NULL COMMENT '难度',
  `created` datetime DEFAULT NULL,
  `create_member` varchar(10) NOT NULL COMMENT '创建人',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  `update_member` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '修改人',
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of question
-- ----------------------------
INSERT INTO `question` VALUES ('1', '8', '你的生活费来自', '1', null, null, '0', '3', null, '', null, null, '1');
INSERT INTO `question` VALUES ('2', '10', '你关注新闻吗', '1', null, null, '0', '3', null, '', null, null, '1');
INSERT INTO `question` VALUES ('3', '8', '测试问题1', '1', null, null, '0', '3', null, '', null, null, '1');
INSERT INTO `question` VALUES ('4', '8', '测试问题2', '1', null, null, '0', '3', null, '', null, null, '1');
INSERT INTO `question` VALUES ('5', '10', '测试问题3', '1', null, null, '0', '3', null, '', null, null, '1');

-- ----------------------------
-- Table structure for `sheet`
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
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sheet
-- ----------------------------
INSERT INTO `sheet` VALUES ('1', '', '289', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('2', '', '289', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('3', '', '289', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('4', '', '294', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('5', '47', '294', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('6', '47', '293', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('7', '47', '293', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('8', '48', '295', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('9', '48', '296', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('10', '48', '295', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('11', '48', '296', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('12', '49', '298', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('13', '49', '298', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('14', '49', '297', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('15', '49', '297', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('16', '50', '299', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('17', '50', '299', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('18', '50', '300', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('19', '50', '300', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('20', '51', '302', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('21', '51', '301', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('22', '51', '302', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('23', '51', '301', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('24', '52', '303', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('25', '52', '304', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('26', '52', '303', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('27', '52', '304', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('28', '53', '305', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('29', '53', '306', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('30', '53', '305', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('31', '53', '306', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('32', '54', '307', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('33', '54', '307', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('34', '54', '308', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('35', '54', '308', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('36', '55', '309', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('37', '55', '309', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('38', '55', '310', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('39', '55', '310', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('40', '56', '311', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('41', '56', '311', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('42', '56', '312', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('43', '56', '312', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('44', '57', '314', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('45', '57', '314', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('46', '57', '313', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('47', '57', '313', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('48', '58', '316', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('49', '58', '316', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('50', '58', '315', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('51', '58', '315', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('52', '59', '317', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('53', '59', '318', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('54', '59', '317', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('55', '59', '318', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('56', '60', '320', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('57', '60', '319', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('58', '60', '320', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('59', '60', '319', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('60', '61', '321', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('61', '61', '321', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('62', '61', '322', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('63', '61', '322', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('64', '62', '323', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('65', '62', '323', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('66', '62', '324', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('67', '62', '324', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('68', '63', '325', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('69', '63', '325', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('70', '63', '326', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('71', '63', '326', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('72', '64', '327', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('73', '64', '328', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('74', '64', '327', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('75', '64', '328', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('76', '65', '329', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('77', '65', '329', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('78', '65', '330', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('79', '65', '330', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('80', '66', '332', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('81', '66', '331', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('82', '66', '331', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('83', '66', '332', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('84', '67', '333', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('85', '67', '333', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('86', '67', '334', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('87', '67', '334', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('88', '68', '336', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('89', '68', '335', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('90', '68', '335', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('91', '68', '336', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('92', '69', '337', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('93', '69', '337', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('94', '69', '338', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('95', '69', '338', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('96', '70', '340', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('97', '70', '339', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('98', '70', '340', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('99', '70', '339', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('100', '71', '342', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('101', '71', '341', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('102', '71', '341', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('103', '71', '342', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('104', '72', '343', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('105', '72', '343', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('106', '72', '344', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('107', '72', '344', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('108', '73', '346', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('109', '73', '345', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('110', '73', '345', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('111', '73', '346', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('112', '74', '347', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('113', '74', '348', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('114', '74', '348', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('115', '74', '347', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('116', '75', '349', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('117', '75', '350', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('118', '75', '349', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('119', '75', '350', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('120', '76', '351', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('121', '76', '352', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('122', '76', '352', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('123', '76', '351', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('124', '77', '354', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('125', '77', '354', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('126', '77', '353', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('127', '77', '353', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('128', '78', '356', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('129', '78', '355', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('130', '78', '355', '5', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('131', '78', '356', '2', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('132', '79', '357', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('133', '79', '357', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('134', '79', '358', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('135', '79', '358', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('136', '80', '360', '3', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('137', '80', '359', '1', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('138', '80', '360', '4', null, '0', null, null, '0');
INSERT INTO `sheet` VALUES ('139', '80', '359', '5', null, '0', null, null, '0');

-- ----------------------------
-- Table structure for `team`
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
-- Table structure for `team_user`
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
-- Table structure for `test`
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
-- Table structure for `user`
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'cb876a1e38befad13143e2be953b644a', 'slowly', '1', '13541319025', '1', '1', '396231662@qq.com', null, null, '1989-06-06', '1', '0000-00-00 00:00:00', '2016-09-11 15:44:51', '0', '1', '0', '1', '1');
INSERT INTO `user` VALUES ('2', '6d5da75f1a593e472e65e985579460ae', 'slowly', '0', '13541319033', '2', null, null, '', '', '2010-03-07', '', '2016-07-07 22:38:47', '2016-07-07 22:50:13', '0', '1', '1', '1', '1');
INSERT INTO `user` VALUES ('3', '', null, null, '13541319066', '3', null, null, null, null, null, null, '2016-07-07 22:59:33', null, '0', '0', '0', '1', '1');
INSERT INTO `user` VALUES ('4', 'e07c99301f39735b510430c6e53843b9', 'slowly', '1', '13541319027', '4', null, null, '', '', '0000-00-00', '', '2016-09-06 21:54:58', '2016-09-06 22:08:29', '0', '1', '0', '1', '1');

-- ----------------------------
-- Table structure for `work`
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
