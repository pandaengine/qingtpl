<?php 
namespace qingtpl\traits;
/**
 * 替换字符串|替换用户定义的字符|魔术字符串
---
__{ROOT}__
__{APP}__   
__{M}__		 
__{C}__		 
__{A}__ 	 
__{URL}__ 	 
__{STATIC}__

__{TPL}__   : 模版路径
__{CACHE}__ : 模版缓存文件

#宏定义是C语言提供的三种预处理功能的其中一种，这三种预处理包括：宏定义、文件包含、条件编译。

##字符串替换|魔术字符串
##宏定义
{#define __{APP__} '123' /}

 *
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
// class ReplaceString{
trait ReplaceString{
	/**
	 * 要替换的用户定义的字符串
	 *
	 * @var array
	 */
	protected $replaceString=array();
	/**
	 * @param array $list
	 */
	public function setReplaceString(array $list){
		$this->replaceString=array_merge((array)$this->replaceString,$list);
	}
	/**
	 * $content=str_replace(array_keys($replace),array_values($replace),$content);
	 * ireplace($str);
	 * 
	 * @param string $content
	 * @return string
	 */
	protected function _compileReplaceString($content){
		//替换用户定义的字符
		$replace=(array)$this->replaceString;
		$replace['__{TPL}__']		=$this->viewFile;
		$replace['__{TPLDIR}__']	=dirname($this->viewFile);
		$replace['__{TPLCACHE}__']	=$this->cacheFile;
		if($replace){
			$content=strtr($content,$replace);
		}
		return $content;
	}
}
?>