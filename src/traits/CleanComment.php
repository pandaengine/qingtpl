<?php 
namespace qingtpl\traits;
/**
 * - 清除模版注释
 * - 处理包含成一个文件之后即清除模版注释
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
trait CleanComment{
// class CleanComment{
	/**
	 * 清除模版注释|模版编译后会被清除
	 *
	 * html:<!-- -->
	 * 单行 : // #
	 * 多行 : /* \*\/
	 * thinkphp:格式：{/* 注释内容 \*\/ } 或 {// 注释内容 }
	 * smarty: {*  *} 支持多行和单行
	 *
	 * 模版注释:{*  *}
	 *
	 * @see    clearComment
	 * @param  string $content
	 * @return string
	 */
	protected function _compileCleanComment($content){
		// /s .*包括回车
		$content=preg_replace('/\{\*.*?\*\}/s','',$content);
		return $content;
	}
}
?>