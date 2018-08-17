<?php
namespace qtests;
/**
 * literal静态内容
 * 不编译保持原样
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class LiteralTest extends Base{
    /**
     * 
     */
    public function test(){
    	$compiler=$this->newCompiler();    	
    	//
    	$tpl='{text}<b>{$var}</b>{/text}';
    	$tpl2='<b>{$var}</b>';
    	$this->assertTrue($compiler->compileText($tpl)==$tpl2);
    	//
    	$tpl='{literal}<b>{$var}</b>{/literal}';
    	$tpl2='<b>{$var}</b>';
    	$this->assertTrue($compiler->compileText($tpl)==$tpl2);
    	//
    	$tpl='{textonly}<b>{$var}</b>{/textonly}';
    	$tpl2='<b>{$var}</b>';
    	$this->assertTrue($compiler->compileText($tpl)==$tpl2);
    	//
    	//dump($compiler->compileText($tpl));
    }
}
?>