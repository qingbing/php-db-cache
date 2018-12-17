-- --------------------------------------------------------
-- 数据库缓存信息表
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `pf_cache` (
  `id` varchar(32) NOT NULL COMMENT '缓存ID',
  `namespace` varchar(20) NOT NULL COMMENT '缓存区名',
  `data` text COMMENT '缓存内容',
  `expire_time` datetime NOT NULL COMMENT '有效时间',
  `update_time` datetime NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`id`),
  KEY `namespace` (`namespace`),
  KEY `expire_time` (`expire_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='数据库缓存信息表';