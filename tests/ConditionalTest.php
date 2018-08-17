<?php
namespace qtests;
/**
 * 预处理之：条件编译
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class ConditionalTest extends Base{
    /**
     * # 可使用的参数
     * 
     * - 已定义PHP常量：define('IS_TRUE',true);
     * - 模版宏定义常量: `{#define('IS_TRUE','1')}`
     * 
     */
    public function test(){
    	define('Conditional_DEBUG',true);
    	$compiler=$this->newCompiler();
    	$tpl=<<<'NOWDOC'
{#if(Conditional_DEBUG)}
	is debug
{#else/}
	not debug
{/#if}
NOWDOC;
    	$this->assertTrue($compiler->compileText($tpl)=='is debug');
    	
    	$tpl=<<<'NOWDOC'
{#if(!Conditional_DEBUG)}
	is debug
{#else/}
	not debug
{/#if}
NOWDOC;
    	$this->assertTrue($compiler->compileText($tpl)=='not debug');
    	//dump($compiler->compileText($tpl));
    }
    /**
     * 
     */
    public function testDefine(){
    	$compiler=$this->newCompiler();
    	$tpl=<<<'NOWDOC'
<define>{#define('IS_TRUE',1)}</define>
<const>{#const('IS_TRUE')}</const>
<true>{#if(IS_TRUE)}is true{#else/}not true{/#if}</true>
NOWDOC;
    	$tpl2=<<<'NOWDOC'
<define></define>
<const>1</const>
<true>is true</true>
NOWDOC;
    	$this->assertTrue($compiler->compileText($tpl)==$tpl2);
    	//dump($compiler->compileText($tpl));
    }
}
?>