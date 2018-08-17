<?php
namespace qingtpl;
/**
 * 编译器插件扩展
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2013 http://qingmvc.com
 */
interface PluginInterface{
	/**
	 * @param string $content
	 * @return string  
	 */
	public function compile($content);
}
?>