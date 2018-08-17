<?php
namespace qtests;
/**
 * 模版常量
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class ConstTest extends Base{
    /**
     * 替换字符串
     */
    public function testReplaceString(){
    	$compiler=$this->newCompiler();
    	$compiler->setReplaceString(['__{STATIC}__'=>'/static','CONST01'=>'{CONST_01}']);
    	$tpl='<b>__{STATIC}__</b> __{A}__ CONST01 ';
    	$this->assertTrue($compiler->compileText($tpl)=='<b>/static</b> __{A}__ {CONST_01}');
    	
    	//dump($compiler->compileText($tpl));
    }
    /**
     * 宏定义
     */
    public function testDefineConst(){
    	$compiler=$this->newCompiler();
    	$compiler->setReplaceString(['__{STATIC}__'=>'/static']);
    	//
    	$tpl="<b>__{STATIC}__</b>";
    	$tpl.="<em>定义:{#define('STATIC_PUBLIC','http://static.com')}</em>";
		$tpl.="<span>使用:{#const('STATIC_PUBLIC')}</span>";
		//
		$tpl2="<b>/static</b>";
		$tpl2.="<em>定义:</em>";
		$tpl2.="<span>使用:http://static.com</span>";
		
    	$this->assertTrue($compiler->compileText($tpl)==$tpl2);
    	
    	//## 在一些特殊情况不能包含特殊符号会被转义
    	//不转义占位符，适用url转义
    	//使用: __const__VAR_NAME__ 等效于 {#const('VAR_NAME')}
    	$tpl="<em>定义:{#define('VAR_NAME','http://static.com')}</em>";
    	$tpl.="<span>使用:__const__VAR_NAME__</span>";
    	//
    	$tpl2="<em>定义:</em>";
    	$tpl2.="<span>使用:http://static.com</span>";

    	$this->assertTrue($compiler->compileText($tpl)==$tpl2);
    	//dump($compiler->compileText($tpl));
    }
}
?>