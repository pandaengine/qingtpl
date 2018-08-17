<?php 
namespace qingtpl;
/**
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2013 http://qingmvc.com
 */
abstract class Plugin implements PluginInterface{
	/**
	 * 编译抽象方法
	 * 
	 * @param string $content
     */
	abstract function compile($content);
}
?>