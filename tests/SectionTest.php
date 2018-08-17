<?php
namespace qtests;
/**
 * 一般用于视图布局，侧边栏等需要复用或者自定义的块状
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class SectionTest extends Base{
    /**
     * 
     */
    public function test(){
    	$compiler=$this->newCompiler();
    	//$compiler->debug=true;
    	$viewFile	=__DIR__.'/section/index.html';
    	$cacheFile	=__DIR__.'/section/~index.html';
    	$compileFile=__DIR__.'/section/index.c.html';
    	$compiler->compile($viewFile,$cacheFile);
    	//
    	$this->assertTrue($this->compareFile($cacheFile,$compileFile));
    }
}
?>