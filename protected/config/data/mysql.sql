CREATE TABLE `business` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '英文名',
  `developer` varchar(20) NOT NULL DEFAULT '' COMMENT '开发者',
  `leader` varchar(20) NOT NULL DEFAULT '' COMMENT '负责人',
  `description` varchar(1024) DEFAULT NULL COMMENT '描述',
  `unix` varchar(20) NOT NULL DEFAULT '' COMMENT '创建时间',
  `cname` varchar(30) DEFAULT NULL COMMENT '中文名',
  `creator` varchar(20) DEFAULT NULL COMMENT '创建者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


CREATE TABLE `function` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `funname` varchar(20) NOT NULL DEFAULT '',
  `entrance` varchar(20) NOT NULL DEFAULT '',
  `description` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;


CREATE TABLE `functionItem` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `function` int(11) NOT NULL,
  `subitem` varchar(20) NOT NULL DEFAULT '',
  `description` varchar(1024) DEFAULT NULL,
  `authflag` varchar(20) NOT NULL DEFAULT '',
  `authority` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `menu` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `business` varchar(100) NOT NULL,
  `first` varchar(255) NOT NULL,
  `second` varchar(255) NOT NULL,
  `third` varchar(255) NOT NULL,
  `creator` varchar(10) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sign` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_business` (`business`),
  KEY `idx_first` (`first`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8 COMMENT='菜单管理';


alter table developer_function add column url varchar(255) NOT NULL;
alter table developer_function add column sign varchar(255) NOT NULL;
alter table developer_function add column item int(11) not null comment 'AuthItem id';
alter table developer_functionitem add column item int(11) not null comment 'AuthItem id';