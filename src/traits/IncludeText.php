<?php 
namespace qingtpl\traits;
/**
 * - 只包含text内容，不解析模版标签
 * - 一般用户包含css/js静态文件
 * 
 * @example 
 * {includetext 'base64.min.js'}
 * {includetext 'style.css'}
 * {includetext '/home/wwwroot/a.html'}
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
// class IncludeText{
trait IncludeText{
	/**
	 * 
	 * @param string $content
	 * @return string
	 */
	public function _compileIncludeText($content){
		$tagName ='includetext';
		$tagBegin=$this->tagBegin;
		$tagEnd  =$this->tagEnd;
		$pattern ="/{$tagBegin}{$tagName}\s*[\'\"](.+?)[\'\"]\s*?\/?\s*{$tagEnd}/is";
		$content=preg_replace_callback($pattern,function($matches)use($tagName){
			$matchText=$matches[0]; //全匹配|{include "common:header"/}
			$matchFile=$matches[1]; //子匹配|"common:header"
			return $this->pushCache($this->getIncludeText($matchFile),$tagName);
		},$content);
		
		/*
		//绑定编译后回调函数
		$this->pushCallbackEvent(function()use($tagName){
			$this->rebackCache($tagName);
		});
		*/
		
		return $content;
	}
	/**
	 * 递归的属性覆盖问题？
	 * 
	 * @param string $includeFile
	 * @return string
	 */
	protected function getIncludeText($includeFile){
		$realFile	=$this->getIncludeFile($includeFile,'');
		$includeText=file_get_contents($realFile);
		//使用静态文本包括|text
		return $includeText;
	}
	/**
	 * - 预处理IncludeText路径
	 * - 在合并include标签之前
	 * - 解决多层级包含路径找不到的问题
	 *
	 * @param string $content
	 * @return string
	 */
	public function preCompileIncludeTextPath($content,$relativeFile){
		$tagBegin=$this->tagBegin;
		$tagEnd  =$this->tagEnd;
		//#解析includetext的路径|替换为绝对路径
		$tagName ='includetext';
		$pattern ="/{$tagBegin}{$tagName}\s*[\'\"](.+?)[\'\"]\s*?\/?\s*{$tagEnd}/is";
		$content=preg_replace_callback($pattern,function($matches)use($relativeFile,$tagName,$tagBegin,$tagEnd){
			$matchText=$matches[0]; //全匹配|{includetext "common:header"/}
			$matchFile=$matches[1]; //子匹配|"common:header"
			$includeFile=$this->getIncludeFile($matchFile,$relativeFile);
			//inc/替换多次，无法编译解析了
			//return str_replace($matchFile,$includeFile,$matchText);
			//#要去除转义符号\{\}
			$tagBegin=str_replace('\\','',$tagBegin);
			$tagEnd=str_replace('\\','',$tagEnd);
			return "{$tagBegin}{$tagName} '{$includeFile}' /{$tagEnd}";
		},$content);
		return $content;
	}
}
?>