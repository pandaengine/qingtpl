<?php
namespace qtests;
/**
 * 包含标签
 *  
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class IncludeTest extends Base{
    /**
     * 
     */
    public function test(){
    	$compiler=$this->newCompiler();
    	//$compiler->debug=true;
    	$viewFile	=__DIR__.'/include/index/index.html';
    	$cacheFile	=__DIR__.'/include/index/~index.html';
    	$compileFile=__DIR__.'/include/index/index.c.html';
    	$compiler->compile($viewFile,$cacheFile);
    	//
    	$this->assertTrue($this->compareFile($cacheFile,$compileFile));
    }
}
?>