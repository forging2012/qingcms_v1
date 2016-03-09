-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2014-12-11 10:30:21
-- 服务器版本： 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `demo.qingcms.com`
--

--
-- 转存表中的数据 `pre_ad`
--

INSERT INTO `pre_ad` (`id`, `title`, `place`, `content`, `is_active`, `is_closable`, `ctime`, `stime`, `etime`, `display_order`) VALUES
(13, '右侧第二个广告', 'right_2', '<img src="/data/adTest/300-250.gif" />', 1, 0, NULL, NULL, NULL, 0),
(14, '底部广告', 'bottom', '<img src="/data/adTest/950-90.gif" />', 1, 0, NULL, NULL, NULL, 0);


--
-- 转存表中的数据 `pre_content`
--

INSERT INTO `pre_content` (`id`, `uid`, `content`, `cateid`, `type`, `type_data`, `url`, `pic_path`, `pic_name`, `video`, `up`, `down`, `comment`, `ctime`, `is_check`, `is_del`) VALUES
(1, 1, '爱情开始的时候总是甜蜜的，后来就有了厌倦、习惯、背弃、寂寞、绝望和冷笑，曾经渴望与一个人长相厮守，后来，多么庆幸自己离开了，曾几何时，在一段短暂的时光里，我们以为自己深深的爱着的一个人。后来，我们才知道，那不是爱，那只是对自己说谎。爱情都是谁骗谁一辈子，一旦不愿意骗了，爱情结束了', 2, 1, 'a:2:{s:4:"path";s:6:"201209";s:4:"name";s:14:"1347464233.jpg";}', '', '', '', '', 0, 0, 0, 1347464233, NULL, 0),
(2, 1, '这么多年，我一直在学习一件事情，就是不回头，只为自己没有做过的事情后悔，不为自己做过的事情后悔。人生每一步行来，都是需要付出代价的。我得到了我想要的一些，失去了我不想失去的一些。可这世上的芸芸众生，谁又不是这样呢？——桐华《那些回不去的年少时光》', 6, 1, 'a:2:{s:4:"path";s:6:"201209";s:4:"name";s:14:"1347464328.jpg";}', '', '', '', '', 0, 0, 0, 1347464328, NULL, 0),
(3, 1, '【躺着也中枪】每次听到闺密指着杂志上的短文对她说：“你看，文章虽短小，却精悍。好喜欢！”马伊俐的心里总觉得怪怪的。每次听到朋友指着牛奶货架对他说：“你看，伊利的奶虽然贵，口感却很好。好喜欢！”文章的心里总觉得怪怪的！', 4, 1, 'a:2:{s:4:"path";s:6:"201209";s:4:"name";s:14:"1347464405.jpg";}', '', '', '', '', 0, 0, 0, 1347464405, NULL, 0),
(4, 1, '某同事把一猫和一狗拿到办公室玩，另一同事想挑起事端让猫和狗打架看热闹，就打了猫一下，然后说了很经典的一句话：狗打的！', 5, 1, 'a:2:{s:4:"path";s:6:"201209";s:4:"name";s:14:"1347464890.jpg";}', '', '', '', '', 0, 0, 0, 1347464890, NULL, 0);

--
-- 转存表中的数据 `pre_content_cate`
--

INSERT INTO `pre_content_cate` (`id`, `parentid`, `nid`, `name`, `displayorder`, `type_id`, `def`, `isnav`) VALUES
(1, 0, 'weixiaoshuo', '微小说', 2, 0, 0, 1),
(2, 1, 'love', '爱情', 1, 0, 0, 0),
(3, 0, 'fun', '搞笑', 1, 0, 0, 1),
(4, 3, 'leng', '冷笑话', 1, 0, 0, 0),
(5, 3, 'qiu', '糗事', 2, 0, 0, 0),
(6, 1, 'lizhi', '励志', 2, 0, 0, 0),
(10, 0, 'gif', 'gif图', 3, 2, 0, 1),
(11, 0, 'video', '视频', 4, 3, 0, 1);

--
-- 转存表中的数据 `pre_content_type`
--

INSERT INTO `pre_content_type` (`id`, `type_id`, `name`) VALUES
(1, 0, '图文'),
(2, 2, 'gif图'),
(3, 3, '视频');

--
-- 转存表中的数据 `pre_credit_rule`
--

INSERT INTO `pre_credit_rule` (`id`, `rulename`, `action`, `cycletype`, `cyclenum`, `gold`) VALUES
(4, '删除文章', 'delpost', 0, 50, -2),
(5, '关注人数', '', 0, 0, 0),
(3, '删除评论', 'delcomment', 2, 20, -1),
(2, '添加评论', 'comment', 2, 20, 1),
(1, '发布文章', 'post', 0, 50, 2);

--
-- 转存表中的数据 `pre_friendlink`
--

INSERT INTO `pre_friendlink` (`id`, `name`, `url`, `description`, `logo`, `type`, `displayorder`, `ctime`) VALUES
(2, 'Logo234', 'http://www.logo234.com/', '', '', 0, 2, 1417948983),
(1, 'QingCms', 'http://www.QingCms.com/', '', '', 0, 1, 1417948983);

--
-- 转存表中的数据 `pre_message`
--

INSERT INTO `pre_message` (`uid`, `notify`, `comment`) VALUES
(1, 0, 0);

--
-- 转存表中的数据 `pre_plugin`
--

INSERT INTO `pre_plugin` (`id`, `name`, `zhName`, `author`, `info`, `version`, `status`, `admin`, `site`, `qcVersion`) VALUES
(6, 'BackTop', '返回顶部', 'xiaowang', '返回顶部', '1.0', 1, 0, 'http://www.qingcms.com/', NULL),
(7, 'FollowBlock', 'Follow关注模块', 'xiaowang', '方便用户关于网站相关的微博或空间', '1', 1, 1, 'http://www.qingcms.com/', NULL),
(8, 'ScrollStop', '右侧滚动停止', 'xiaowang', '右侧滚动在指定的地方停止', '1', 1, 0, 'http://www.qingcms.com/', NULL),
(9, 'TagCloud', '标签云', 'xiaowang', '标签云', '1.0', 1, 0, 'http://www.qingcms.com/', NULL);

--
-- 转存表中的数据 `pre_plugin_data`
--

INSERT INTO `pre_plugin_data` (`id`, `plugin`, `mtime`, `data`) VALUES
(5, 'FollowBlock', '2014-12-11 17:22:23', 'a:2:{i:0;a:2:{s:4:"name";s:7:"qingcms";s:3:"url";s:22:"http://www.qingcms.com";}i:1;a:2:{s:4:"name";s:7:"logo234";s:3:"url";s:22:"http://www.logo234.com";}}');

--
-- 转存表中的数据 `pre_smiley`
--

INSERT INTO `pre_smiley` (`id`, `title`, `type`, `code`, `filename`, `displayorder`) VALUES
(1, '拥抱', 'smiley', '[拥抱]', 'hug.gif', 0),
(2, '示爱', 'smiley', '[示爱]', 'kiss.gif', 0),
(3, '呲牙', 'smiley', '[呲牙]', 'lol.gif', 0),
(4, '可爱', 'smiley', '[可爱]', 'loveliness.gif', 0),
(5, '折磨', 'smiley', '[折磨]', 'mad.gif', 0),
(6, '难过', 'smiley', '[难过]', 'sad.gif', 0),
(7, '流汗', 'smiley', '[流汗]', 'sweat.gif', 0),
(8, '憨笑', 'smiley', '[憨笑]', 'biggrin.gif', 0),
(9, '大哭', 'smiley', '[大哭]', 'cry.gif', 0),
(10, '惊恐', 'smiley', '[惊恐]', 'funk.gif', 0),
(11, '握手', 'smiley', '[握手]', 'handshake.gif', 0),
(12, '发怒', 'smiley', '[发怒]', 'huffy.gif', 0),
(13, '惊讶', 'smiley', '[惊讶]', 'shocked.gif', 0),
(14, '害羞', 'smiley', '[害羞]', 'shy.gif', 0),
(15, '微笑', 'smiley', '[微笑]', 'smile.gif', 0),
(16, '偷笑', 'smiley', '[偷笑]', 'titter.gif', 0),
(17, '调皮', 'smiley', '[调皮]', 'tongue.gif', 0),
(18, '胜利', 'smiley', '[胜利]', 'victory.gif', 0);

--
-- 转存表中的数据 `pre_system`
--

INSERT INTO `pre_system` (`id`, `uid`, `list`, `keyword`, `value`, `mtime`) VALUES
(28, 0, 'siteinfo', 'site_name', 's:7:"QingCms";', '2014-12-07 16:11:17'),
(29, 0, 'siteinfo', 'url', 's:24:"http://demo.qingcms.com/";', '2014-12-07 16:11:17'),
(41, 0, 'view', 'isRewrite', 's:1:"1";', '2014-12-07 16:15:21'),
(30, 0, 'siteinfo', 'icp', 's:20:"湘ICP备12004225号";', '2014-12-07 16:11:17'),
(31, 0, 'siteinfo', 'keywords', 's:49:"qingcms,cms,轻内容管理系统,php开源程序";', '2014-12-07 16:11:17'),
(32, 0, 'siteinfo', 'description', 's:45:"QingCms-轻内容管理系统-php开源程序";', '2014-12-07 16:11:17'),
(70, 0, 'tools', 'countCode', '<script type="text/javascript">\r\nvar _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");\r\ndocument.write(unescape("%3Cscript src=''" + _bdhmProtocol + "hm.baidu.com/h.js%3F953efb1d99099c151ef4b148580d475a'' type=''text/javascript''%3E%3C/script%3E"));\r\n</script>\r\n', '2014-12-07 17:26:28'),
(71, 0, 'tools', 'shareCode', '<!-- JiaThis Button BEGIN -->\r\n<div class="jiathis_style">\r\n	<a class="jiathis_button_qzone"></a>\r\n	<a class="jiathis_button_tsina"></a>\r\n	<a class="jiathis_button_tqq"></a>\r\n	<a class="jiathis_button_renren"></a>\r\n	<a class="jiathis_button_kaixin001"></a>\r\n	<a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>\r\n	<a class="jiathis_counter_style"></a>\r\n</div>\r\n<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js?uid=1352358789596506" charset="utf-8"></script>\r\n<!-- JiaThis Button END -->', '2014-12-07 17:26:28'),
(38, 0, 'view', 'textPage', 's:2:"12";', '2014-12-07 16:15:21'),
(35, 0, 'view', 'length', 's:3:"200";', '2014-12-07 16:15:21'),
(37, 0, 'view', 'comLen', 's:3:"120";', '2014-12-07 16:15:21'),
(12, 0, 'view', 'comAjaxLen', 's:1:"6";', '2012-09-04 22:57:40'),
(34, 0, 'view', 'minlength', 's:2:"12";', '2014-12-07 16:15:21'),
(36, 0, 'view', 'minComLen', 's:1:"2";', '2014-12-07 16:15:21'),
(39, 0, 'view', 'cateMust', 's:1:"1";', '2014-12-07 16:15:21'),
(16, 0, 'view', 'comPage', 's:2:"10";', '2012-09-04 22:57:40'),
(33, 0, 'siteinfo', 'themes', 's:7:"default";', '2014-12-07 16:11:17'),
(27, 0, 'siteinfo', 'cms_name', 's:29:"QingCms-轻内容管理系统";', '2014-12-07 16:11:17'),
(40, 0, 'view', 'top10', 's:1:"3";', '2014-12-07 16:15:21'),
(72, 0, 'attachment', 'maxSize', 's:3:"1.2";', '2014-12-11 17:21:16'),
(73, 0, 'attachment', 'thumb_sx', 's:3:"200";', '2014-12-11 17:21:16'),
(74, 0, 'attachment', 'thumb_sy', 's:4:"1000";', '2014-12-11 17:21:16'),
(75, 0, 'attachment', 'thumb_mx', 's:3:"500";', '2014-12-11 17:21:16'),
(76, 0, 'attachment', 'thumb_my', 's:3:"600";', '2014-12-11 17:21:16'),
(77, 0, 'attachment', 'removeOrigin', 's:1:"0";', '2014-12-11 17:21:16'),
(78, 0, 'attachment', 'water', 's:1:"1";', '2014-12-11 17:21:16'),
(79, 0, 'attachment', 'waterPosition', 's:1:"7";', '2014-12-11 17:21:16'),
(80, 0, 'attachment', 'water_minWidth', 's:3:"200";', '2014-12-11 17:21:16'),
(81, 0, 'attachment', 'water_minHeight', 's:3:"200";', '2014-12-11 17:21:16'),
(82, 0, 'attachment', 'padding_x', 's:1:"6";', '2014-12-11 17:21:16'),
(83, 0, 'attachment', 'padding_y', 's:2:"10";', '2014-12-11 17:21:16'),
(84, 0, 'attachment', 'waterFile', 's:24:"./static/image/water.png";', '2014-12-11 17:21:16');


--
-- 转存表中的数据 `pre_user_field`
--

INSERT INTO `pre_user_field` (`id`, `key`, `name`, `status`, `module`, `displayorder`) VALUES
(3, 'name', '名字', 1, 'intro', 0),
(4, 'summary', '我的简介', 1, 'intro', 0),
(5, 'nearestwish', '最近心愿', 1, 'intro', 0),
(6, 'motto', '座右铭', 1, 'intro', 0),
(8, 'favbook', '喜欢的书', 1, 'intro', 0),
(9, 'interest', '兴趣爱好', 1, 'intro', 0),
(10, 'address', '地址', 1, 'contact', 0),
(11, 'postcode', '邮编', 1, 'contact', 0),
(12, 'telphone', '电话', 1, 'contact', 0),
(13, 'mobile', '手机', 1, 'contact', 0),
(14, 'qq', 'QQ', 1, 'contact', 0),
(15, 'msn', 'MSN', 1, 'contact', 0);

--
-- 转存表中的数据 `pre_user_profile`
--

INSERT INTO `pre_user_profile` (`id`, `uid`, `contact`, `intro`) VALUES
(1, 1, NULL, NULL);

--
-- 转存表中的数据 `pre_area`
--

INSERT INTO `pre_area` (`area_id`, `title`, `pid`) VALUES
(1, '北京', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
