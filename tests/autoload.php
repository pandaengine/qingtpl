<?php
/**
 * 加载qingmvc类自动加载器
 * 如果没有配置composer则可以使用该加载器
 *
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */

function dump($var=''){
	echo "\n[1]------------------------\n";
	var_dump($var);
	echo "\n[2]------------------------\n";
}
function qexport($var,$filename){
	file_put_contents($filename,var_export($var,true));
}

// 路径分隔符
defined('DS')  or define("DS",DIRECTORY_SEPARATOR);

require_once __DIR__.'/Base.php';

//加载框架基础包
require_once __DIR__.'/../qingbase/autoload.php';
//加载框架
// require_once __DIR__.'/../../qingmvc07/autoload.php';
require_once __DIR__.'/../autoload.php';
?>