-- --------------------------------------------------------
-- 数据库缓存信息表
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `test_cache` (
  `id` CHAR(32) NOT NULL COMMENT '缓存ID',
  `namespace` varchar(30) NOT NULL COMMENT '缓存区名',
  `data` text COMMENT '缓存内容',
  `expire_at` datetime NOT NULL COMMENT '有效时间',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY pk_id(`id`),
  KEY `idx_namespace`(`namespace`),
  KEY `idx_expire_at` (`expire_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='数据库缓存信息表';