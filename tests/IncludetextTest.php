<?php
namespace qtests;
/**
 * 包含纯文本文件
 * 不解析编译被包含文件
 *  
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class IncludetextTest extends Base{
    /**
     * 
     */
    public function test(){
    	$compiler=$this->newCompiler();
    	//$compiler->debug=true;
    	$viewFile	=__DIR__.'/includetext/index.html';
    	$cacheFile	=__DIR__.'/includetext/~index.html';
    	$compileFile=__DIR__.'/includetext/index.c.html';
    	$compiler->compile($viewFile,$cacheFile);
    	//
    	$this->assertTrue($this->compareFile($cacheFile,$compileFile));
    }
}
?>