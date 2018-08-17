<?php
namespace qtests;
/**
 * 变量
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class VarTest extends Base{
    /**
     * 
     */
    public function test(){
    	$compiler=$this->newCompiler();    	
    	//
    	$tpl='<b>{$var}</b>';
    	$tpl2='<b><?php echo $var;?></b>';
    	$this->assertTrue($compiler->compileText($tpl)==$tpl2);
    	//
    	$tpl='<b>{{$var}}</b>';
    	$tpl2='<b><?php echo htmlentities($var);?></b>';
    	$this->assertTrue($compiler->compileText($tpl)==$tpl2);
    	//
    	$tpl='<b>{@$var}</b>';
    	$tpl2='<b><?php echo @$var;?></b>';
    	$this->assertTrue($compiler->compileText($tpl)==$tpl2);
    	//
    	//dump($compiler->compileText($tpl));
    }
}
?>