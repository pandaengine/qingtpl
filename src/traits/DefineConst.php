<?php 
namespace qingtpl\traits;
/**
 * 预处理之：宏定义

- 宏定义常量
- 和replaceString配合使用

##字符串替换|魔术字符串
##宏定义
##可以是php代码|代码模版片段

{#define __{APP}__ '123' /}
{#const __{APP}__ /}

定义：{#define('__{APP}__','123')}
使用：{#const('__{APP}__')}
{#const@var_plugin}
不会转义:
__const__pluginVar__

靠后执行：在replaceString之后
 *
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
trait DefineConst{
	/**
	 * 常量栈
	 * 
	 * @var array
	 */
	protected $consts=[];
	/**
	 * @param array $consts
	 */
	public function resetConsts(){
		$this->consts=[];
	}
	/**
	 *
	 * @param string $key
	 * @return string
	 */
	public function hasConst($key){
		return isset($this->consts[$key]);
	}
	/**
	 * @return array
	 */
	public function getConsts(){
		return $this->consts;
	}
	/**
	 * @param array $consts
	 */
	public function setConsts(array $consts){
		$this->consts=$consts;
	}
	/**
	 *
	 * @param string $key
	 * @return string
	 */
	public function getConst($key){
		return (string)$this->consts[$key];
	}
	/**
	 * 可初始化设置常量
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function setConst($key,$value){
		$this->consts[$key]=$value;
	}
	/**
	 * 
	 * @param string $content
	 * @return string
	 */
	protected function _compileDefineConst($content){
		$tagBegin=$this->tagBegin;
		$tagEnd  =$this->tagEnd;
		
		//宏定义|可以为空|可以为代码模版片段|{#define('__{APP}__','123')}
		$pattern ="/{$tagBegin}#define\(\s*[\'\"](.+?)[\'\"]\s*,\s*[\'\"]?(.*?)[\'\"]?\s*\){$tagEnd}/is";
		$content=preg_replace_callback($pattern,function($matches){
			$key  =$matches[1];
			$value=$matches[2];
			$this->setConst($key,$value);
			return '';
		},$content);
		
		//宏使用：{#const('__{APP}__')}
		$pattern ="/{$tagBegin}#const\([\'\"](.+?)[\'\"]\){$tagEnd}/is";
		$content=preg_replace_callback($pattern,function($matches){
			$key  =$matches[1];
			return $this->getConst($key);
		},$content);
		
		//使用：{#const@var_plugin}
		/*
		$pattern ="/{$tagBegin}#const@([a-z0-9_-]+?){$tagEnd}/i";
		$content=preg_replace_callback($pattern,function($matches){
			$key  =$matches[1];
			return $this->getConst($key);
		},$content);
		*/
		
		//使用，不转义占位符，适用url转义：__const__pluginVar__
		$pattern ="/__const__([a-z0-9_-]+?)__/i";
		$content=preg_replace_callback($pattern,function($matches){
			$key  =$matches[1];
			return $this->getConst($key);
		},$content);
		
		return $content;
	}
}
?>