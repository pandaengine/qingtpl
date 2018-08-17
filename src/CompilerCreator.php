<?php
namespace qingtpl;
use qing\com\ComCreator;
//use qingtpl\plugins\AppName;
/**
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class CompilerCreator extends ComCreator{
	/**
	 * @see \qing\com\ComCreator::create()
	 */
	public function create(){
		$compiler=new Compiler();
		$compiler->safeScript	='<?php if (!defined(\'APP_QING\')){ exit();}?>'."";
		$compiler->setReplaceString($this->replaceString());
		//$compiler->pushPlugins([new AppName()]);
		//#
		//$compiler->debug	  		=APP_DEBUG;
		$compiler->viewSuffix  		=VIEW_SUFFIX;
		$compiler->cacheSuffix		=VIEW_SUFFIX;
		$compiler->removeHtmlComment=true;
		$compiler->removeEmptyRows	=true;
		$compiler->removeSpace		=false;
		return $compiler;
	}
	/**
	 * 字符串替换|魔术字符串
	 * 
	 * @return array
	 */
	protected function replaceString(){
		return
		[
			// 模版常量
			'__{APP_DEBUG}__' => APP_DEBUG,
			
			// 网站根路径
			'__{HOME}__'      => __HOME__,
			'__{ROOT}__'      => __ROOT__,
				
			// 当前项目url  index.php  admin.php
			'__{APP}__'       => __APP__,
			//static目录，public/static目录下
			'__{STATIC}__'    => defined('__STATIC__')?__STATIC__:__ROOT__.'/static',
			
			// 注意使用范围/不要在不同控制器不同模块的公共部位使用/只有一个操作使用的模版才能使用__{A}__
			// 当前模块
			'__{M}__'		  => __M__,
			// 当前控制器
			'__{C}__'		  => __C__,
			// 当前操作
			'__{A}__'    	  => __A__
		];
	}
}
?>