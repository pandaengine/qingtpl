<?php 
namespace qingtpl\traits;
/**
 * RemoveComment
 * RemoveSpace
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
trait Cleaner{
// class Cleaner{
	/**
	 * 移除脚本空格与换行
	 *
	 * @var boolean
	 */
	public $removeSpace=false;
	/**
	 * 移除空行
	 *
	 * @var boolean
	 */
	public $removeEmptyRows=false;
	/**
	 * 移除html注释
	 *
	 * @var boolean
	 */
	public $removeHtmlComment=false;
	/**
	 * 移除php注释
	 *
	 * @var boolean
	 */
	public $removePhpComment=false;
	/**
	 *
	 * @param string $content
	 * @return string
	 */
	public function _compileCleaner($content){
		//移除html注释
		if($this->removeHtmlComment){
			$content=$this->removeHtmlComment($content);
		}
		//移除php注释
		if($this->removePhpComment){
			$content=$this->removePhpComment($content);
		}
		//#移除多行
		if($this->removeEmptyRows){
			$content=$this->removeEmptyRows($content);
		}
		//#移除模版空格
		if($this->removeSpace){
			$content=$this->removeSpace($content);
		}
		//优化生成的php代码
		$content=str_replace('?><?php','',$content);
		$content=trim($content);
		return $content;
	}
	/**
	 * 移除php标签
	 * 多行
	 * 单行
	 * (?:\/\/|\/\*|\*\/)
	 * 
	 * @return string
	 */
	protected function removePhpComment($content){
		$tagBegin=$this->tagBegin;
		$tagEnd  =$this->tagEnd;
		//php标签内部内容 \<\?php \?\>
		$content=preg_replace_callback('/\<\?php(.*?)\?\>/is',function($matches){
			$content=$matches[1];
			
			$quotes=[];
			//先缓存引号内部数据
			$content=preg_replace_callback('/[\'"].*?[\'"]/',function($matches)use(&$quotes){
				$count=count($quotes);
				$quotes[$count]=$matches[0];
				return "--#q#[{$count}]#q#--";
			},$content);
			
			//多行注释 /*...*/
			$content=preg_replace('/\/\*.*?\*\//s','',$content);
			//单行注释 //...换行
			$content=preg_replace('/\/\/.*?[\n\r]/','',$content);
			
			//恢复引号数据
			if($quotes){
				$content=preg_replace_callback('/--#q#\[(\d+)\]#q#--/',function($matches)use($quotes){
					return $quotes[(int)$matches[1]];
				},$content);
			}
			
			return "<?php{$content}?>";
		},$content);
		return $content;
	}
	/**
	 * 移除html标签之间和php标签之间的空格与换行     <div> </div>  ? >  < ?php
	 *
	 * @return string
	 */
	protected function removeSpace($content){
		$find    = array('/>\s+</','/>\s+(\n|\r)/');
		$replace = array('><','>');
		$content = preg_replace($find,$replace,$content);
		return $content;
	}
	/**
	 * $content=nl2br($content);
	 * $content = preg_replace('/((\r\n\s*){2,}|(\n\r\s*){2,}|(\r\s*){2,}|(\n\s*){2,})/',"\r\n",$content);
	 * 
	 * @return string
	 */
	protected function removeEmptyRows($content){
		//#按行分割
		$lines	=explode("\n", $content);
		$content='';
		foreach($lines as $k=>$line){
			if(trim($line)>''){
				//#只取非空行
				$content.=$line."\n";
			}
		}
		return $content;
	}
	/**
	 * 清除HTML注释
	 * html:<!-- -->
	 *
	 * @param  string $content
	 * @return string
	 */
	protected function removeHtmlComment($content){
		// /s .*包括回车
		$content=preg_replace('/\<\!\-\-.*?\-\-\>/s','',$content);
		return $content;
	}
}
?>