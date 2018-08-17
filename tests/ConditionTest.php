<?php
namespace qtests;
/**
 * 条件判断
 * if
 * switch
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class ConditionTest extends Base{
    /**
     * 
     */
    public function test(){
    	$compiler=$this->newCompiler();
    	//$compiler->debug=true;
    	$viewFile	=__DIR__.'/condition/index.html';
    	$cacheFile	=__DIR__.'/condition/~index.html';
    	$compileFile=__DIR__.'/condition/index.c.html';
    	$compiler->compile($viewFile,$cacheFile);
    	//
    	$this->assertTrue($this->compareFile($cacheFile,$compileFile));
    }
}
?>