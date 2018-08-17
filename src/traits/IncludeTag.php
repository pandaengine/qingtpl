<?php 
namespace qingtpl\traits;
/**
 * 预处理器：文件包含
 * include标签|解析包含模版|把包含文件整合成一整个文件后再解析其他标签
 * 
 * //2015.05.10
 * //TODO:{include ''/}标签采用预处理；预处理整合成一整个文件后再解析标签；
 * 预处理include标签|把模版整合到一个文件|public function pretreatment(){}
 * 
 * @example 
 * {include 'head'}
 * {include '/home/wwwroot/a.html'}
 * {include 'common:login'}
 * {#include 'common/head'/}
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
trait IncludeTag{
	/**
	 *
	 * @var boolean
	 */
// 	public $includeFile=false;
	/**
	 *  - {include ''/} | {include ""/}
	 *  - 包含文件
	 *  - 任意层次的包含，即包含文件中又有包含标签
	 *
	 *  \s:空白字符
	 *  \S:非空白字符
	 *  /s:点号包括回车
	 *
	 *  TODO: 被包含文件更改但包含文件并未及时更新的问题，
	 *  包含文件的模版文件和缓存文件一直都没有更改，被包含文件更改后并不能反应到包含文件上去。
	 * 
	 * @param string $content
	 * @return string
	 */
	protected function compileIncludeTag($content,$viewFile){
		$content=$this->rCompileIncludeTag($content,$viewFile);
		return $content;
	}
	/**
	 * 根据模版内容|递归编译包含文件
	 * 
	 * @name recursive 
	 * @param string $content
	 * @param string $relativeFile
	 * @return mixed
	 */
	protected function rCompileIncludeTag($content,$relativeFile){
		$tagBegin=$this->tagBegin;
		$tagEnd  =$this->tagEnd;
		
		//#includetext的包含路径问题
		$content=$this->preCompileIncludeTextPath($content,$relativeFile);
		//#解析include标签
		$pattern ="/{$tagBegin}#?include\s*[\'\"](.+?)[\'\"]\s*?\/?\s*{$tagEnd}/is";
		$content=preg_replace_callback($pattern,function($matches)use($relativeFile){
			$matchText=$matches[0]; //全匹配|{include "common:header"/}
			$matchFile=$matches[1]; //子匹配|"common:header"
			return $this->compileIncludeFile($matchFile,$relativeFile);
		},$content);
		
		return $content;
	}
	/**
	 * 编译包含文件
	 * 
	 * @param string $includeFile
	 * @param string $relativeFile
	 * @return string
	 */
	protected function compileIncludeFile($includeFile,$relativeFile){
		//#包含文件真实路径
		$includeFile	=$this->getIncludeFile($includeFile, $relativeFile);
		//#包含文件的内容
		$includeContent	=file_get_contents($includeFile);
		if($this->debug){
			//添加模版文件注释
			$content='';
			$content.="\n".'<?php /* [ include-begin: '.$includeFile.'] */ ?>'."\n";
			$content.=$includeContent;
			$content.="\n".'<?php /* [ include-end: '.$includeFile.'] */ ?>'."\n";
			$includeContent=$content;
		}
		//#递归解析该包含文件内容的include标签
		return $this->rCompileIncludeTag($includeContent,$includeFile);
	}
}
?>