<?php
namespace qtests;
/**
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class Demo01Test extends Base{
    /**
     * 
     */
    public function test(){
    	$compiler=$this->newCompiler();
    	$viewFile	=__DIR__.'/demo01/index.html';
    	$cacheFile	=__DIR__.'/demo01/~index.html';
    	$compileFile=__DIR__.'/demo01/index.c.html';
    	$compiler->compile($viewFile,$cacheFile);
    	//
    	$this->assertTrue($this->compareFile($cacheFile,$compileFile));
    }
}
?>