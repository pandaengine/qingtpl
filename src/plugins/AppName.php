<?php 
namespace qingtpl\plugins;
use qingtpl\Plugin;
/**
 * 应用名称简单替换
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2013 http://qingmvc.com
 */
class AppName extends Plugin{
	/**
	 * 
	 * @param string $content
	 * @return string
	 */
	public function compile($content){
		return preg_replace('/\{appName\}/i',app()->appName,$content);
	}
}
?>