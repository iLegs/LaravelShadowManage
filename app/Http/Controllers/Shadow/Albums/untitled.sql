DROP TABLE IF EXISTS `common_albums`;
CREATE TABLE `common_albums`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '名称',
  `desc` varchar(256) NOT NULL DEFAULT '' COMMENT '描述',
  `cover` varchar(256) NOT NULL DEFAULT '' COMMENT '封面',
  `year` varchar(12) NOT NULL DEFAULT '' COMMENT '发行年份',
  `date` varchar(12) NOT NULL DEFAULT '' COMMENT '发行日期',
  `lib_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '关联图库编号',
  `browse_times` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '浏览次数',
  `comment_times` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '评论次数',
  `collect_times` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收藏次数',
  `status` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态:0-禁用;1-启用',
  `add_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '新增时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT '专辑信息表';

DROP TABLE IF EXISTS `common_album_photoes`;
CREATE TABLE `common_album_photoes`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `qn_key` varchar(256) NOT NULL DEFAULT '' COMMENT '七牛云存储key',
  `album_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '关联专辑编号',
  `browse_times` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '浏览次数',
  `comment_times` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '评论次数',
  `collect_times` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收藏次数',
  `type` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '类型：0-横；1-竖',
  `status` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态:0-已删除;1-启用;2-已删除（七牛待删除）',
  `add_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '新增时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT '专辑图片信息表';

DROP TABLE IF EXISTS `relation_album_tags`;
CREATE TABLE `relation_album_tags`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `album_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '关联专辑编号',
  `tag_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '关联标签编号',
  `add_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '新增时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT '专辑关联标签信息表';

DROP TABLE IF EXISTS `relation_album_models`;
CREATE TABLE `relation_album_models`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '编号',
  `album_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '关联专辑编号',
  `model_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '关联模特编号',
  `add_time` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '新增时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT '专辑关联标签信息表';



