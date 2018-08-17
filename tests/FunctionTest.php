<?php
namespace qtests;
/**
 * 函数替换
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class FunctionTest extends Base{
    /**
     * 
     */
    public function test(){
    	$compiler=$this->newCompiler();
    	$tpl='{:U()} {~U()}';
    	$tpl2='<?php echo U();?> <?php U();?>';
    	$this->assertTrue($compiler->compileText($tpl)==$tpl2);
    	//dump($compiler->compileText($tpl));
    	
    	$tpl='{:func()} {~func()}';
    	$tpl2='<?php echo func();?> <?php func();?>';
    	$this->assertTrue($compiler->compileText($tpl)==$tpl2);
    	//dump($compiler->compileText($tpl));
    }
}
?>