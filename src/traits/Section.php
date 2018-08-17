<?php 
namespace qingtpl\traits;
/**
 * 区块
 *
 * # 定义区块
 * - {section('name')}...{/section}
 * 
 * # 使用区块
 * {@section('name')}
 * 
 * {section('sidebar')}...{/section}
 * {section('main')}...{/section}
 * {@section('sidebar')}
 * {@section('main')}
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
trait Section{
// class Section{
	/**
	 * 取款信息
	 * 
	 * @var array
	 */
	protected $sections=[];
	/**
	 * /i:大小写
	 * /s:.*包括回车
	 * 
	 * @param string $content
	 * @return string
	 */
	public function _compileSection($content){
		$tBegin=$this->tagBegin;//\{
		$tEnd  =$this->tagEnd;//\}
		
		//#解析区块定义
		$tagName='section';
		$tagEnd	=$this->getTag($tagName,true);
		$pattern="/{$tBegin}{$tagName}\([\'\"]?(.+?)[\'\"]?\){$tEnd}(.*?){$tagEnd}/is";
		$content=preg_replace_callback($pattern,function($matches)use($tagName){
			$sectionName=$matches[1];
			$sectionContent=$matches[2];
			$this->sections[$sectionName]=$sectionContent;
			return '';
		},$content);
		
		//#解析区块使用
		$pattern="/{$tBegin}\@{$tagName}\([\'\"]?(.+?)[\'\"]?\){$tEnd}/is";
		$content=preg_replace_callback($pattern,function($matches)use($tagName){
			$sectionName=$matches[1];
			if(isset($this->sections[$sectionName])){
				$content=$this->sections[$sectionName];
				unset($this->sections[$sectionName]);
				return $content;
			}else{
				return '';
			}
		},$content);
		$this->sections=[];
		
		return $content;
	}
}
?>