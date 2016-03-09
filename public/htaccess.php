<?php
/**
 * htaccess测试
 * 
 * http://qing2016.xyz/qingcms_demo/public/.git
 * http://qing2016.xyz/qingcms_demo/public/.svn
 * 
 * http://qing2016.xyz/qingcms_demo/public/aaa/.git
 * http://qing2016.xyz/qingcms_demo/public/aaa/.svn
 * 
 * http://qing2016.xyz/qingcms_demo/public/aaa/.git/bbb
 * http://qing2016.xyz/qingcms_demo/public/aaa/.svn/bbb
 * 
 * http://qing2016.xyz/qingcms_demo/public/aaa/.g/bbb
 * http://qing2016.xyz/qingcms_demo/public/aaa/.s/bbb
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
header('HTTP/1.1 403 Forbidden');
echo '<h1 style="color:red;">禁止访问</h1>';
/*
echo '<pre>';
var_dump($_REQUEST);
echo '</pre>';
*/
?>