CREATE TABLE `ck_actions` (
  `action_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '活動編號',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '活動名稱',
  `content` text NOT NULL COMMENT '活動說明',
  `action_date` date NOT NULL COMMENT '活動日期',
  `end_date` datetime NOT NULL COMMENT '報名截止日',
  `uid` smallint(5) unsigned NOT NULL COMMENT '發布者編號',
  `enable` enum('1','0') NOT NULL COMMENT '是否啟用',
  PRIMARY KEY (`action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活動資料表';

CREATE TABLE `ck_signups` (
  `uid` smallint(5) unsigned NOT NULL COMMENT '使用者編號',
  `action_id` smallint(5) unsigned NOT NULL COMMENT '活動編號',
  `signup_date` datetime NOT NULL COMMENT '報名日期',
  PRIMARY KEY (`uid`,`action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='報名表';

