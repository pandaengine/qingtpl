<?php 
namespace qingtpl\traits;
/**
 * 预处理之：条件编译
 * 

{#if()}
 
{#else/}
{#else}

{/#if}
{#endif}

 *
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
// class Conditional{
trait Conditional{
	/**
	 * @var boolean
	 */
	public $conditionalMode=false;
	/**
	 * $else 	="(?:{$tagBegin}#else\/?{$tagEnd})?";
	 * 
	 * @param string $content
	 * @return string
	 */
	protected function _compileConditional($content){
		$tagBegin=$this->tagBegin;
		$tagEnd  =$this->tagEnd;
		
		$beginif="{$tagBegin}#if\((.+?)\){$tagEnd}";
		$endif	="(?:{$tagBegin}\/#if{$tagEnd}|{$tagBegin}\/?#endif{$tagEnd})";
		$pattern="/{$beginif}(.*?){$endif}/is";
		$else 	 ="{$tagBegin}#else\/?{$tagEnd}";
		
		//#{#if}{/#if}
		$content=preg_replace_callback($pattern,function($matches)use($else){
			//dump($matches);
			//#不包含else|{#if}{/#if}
			$source   =$matches[0];
			$condition=$matches[1];
			$matche   =$matches[2];
			if(preg_match("/{$else}/is",$matche)){
				//#含有else/返回原数据
				return $source;
			}
			//return '{#if}{/#if}';
			if($this->getConditional($condition)){
				//#true
				return $matche;
			}else{
				return '';
			}
		},$content);
		
		//{#if}{#else/}{/#if}
		$pattern="/{$beginif}(.*?){$else}(.*?){$endif}/is";
		$content=preg_replace_callback($pattern,function($matches){
			//#不包含else|{#if}{/#if}
			$source   =$matches[0];
			$condition=$matches[1];
			$conTrue  =$matches[2];
			$conFalse =$matches[3];
			//return '{#if}{#else/}{/#if}';
			if($this->getConditional($condition)){
				//#true
				return $conTrue;
			}else{
				return $conFalse;
			}
		},$content);
		return $content;
	}
	/**
	 * $condition='3 && 1 && 2';
	 * 
	 * @param string $condition
	 */
	protected function getConditional($condition){
		$condition=(string)$condition;
		if($condition[0]=='!'){
			//取反
			return !$this->getConditional(substr($condition,1));
		}
		if(false && $this->conditionalMode){
			/*
			 * eval模式
			 * 删除一些可能被用户操作的危险全局变量/eval和全局变量一起使用是超级危险的
			 */
			/*
			if(strpos($condition,'$')!==false){
				throw new \Exception('为安全起见:条件编译不能包含变量'.$condition);
			}
			//组成php代码|return (1 && 22 || 33);
			$condition="return ({$condition});";
			//$res=@eval($condition);
			//$res=eval($condition);
			*/
			$res=false;
			return $res;
		}else{
			/*@var $self \qingtpl\Compiler */
			$self=$this;
			/*
			 * #安全模式：
			 * - 只支持常量：APP_DEBUG
			 * - 函数？
			 * - 模版变量/模版宏定义
			 */
			if(defined($condition)){
				//#常量
				return (bool)constant($condition);
			}else if($self->hasConst($condition)){
				//#模版宏定义常量
				return (bool)$self->getConst($condition);
			}else{
				return false;
			}
		}
	}
}
?>