<?php 
namespace qingtpl\traits;
/**
 * literal静态文本/纯文本标签
 *
 * 1. {literal}...{/literal}
 * 2. 内部内容不解析
 * 3. regexp中  { }  /均需要进行转义
 * ---
 * $content=preg_replace('/\{literal\}(.*?)\{/literal\}/eis',"\$this->cacheLiteral('\\1')",$content);
 * preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead
 * /e:参数类似于eval不安全不推荐使用
 * ---
 *
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
trait Literal{
// class Literal{
	/**
	 *
	 * @param string $content
	 * @return string
	 */
	public function _compileLiteral($content){
		$tagName ='literal';
		$alias	 =(array)$this->getTagAlias('literal');
		/*
		 * /i:大小写
		 * /s:.*包括回车
		 * 
		 * $content=preg_replace('/\{literal\}(.*?)\{/literal\}/eis',"\$this->cacheLiteral('\\1')",$content);
		 */
		foreach($alias as $literal){
			$literalBegin=$this->getTag($literal);
			$literalEnd	 =$this->getTag($literal,true);
			$pattern	 ="/{$literalBegin}(.*?){$literalEnd}/is";
			$content=preg_replace_callback($pattern,function($matches)use($tagName){
				return $this->pushCache($matches[1],$tagName);
			},$content);
		}
		/*
		//绑定编译后回调函数
		$this->attachCallbackEvent(function()use($tagName){
			$this->rebackCache($tagName);
		});
		*/
		return $content;
	}
}
?>