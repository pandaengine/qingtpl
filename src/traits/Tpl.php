<?php 
namespace qingtpl\traits;
/**
 * 局部模版
 *
 * {tpl(var_plugin)}{$plugin}{/tpl}
 * {tpl@var_plugin}
 * 
 * @deprecated 2017.12.15 使用常量即可/DefineConst
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
trait Tpl{
// class Tpl{
	/**
	 *
	 * @param string $content
	 * @return string
	 */
	public function _compileTpl($content){
		return $content;
	}
}
?>