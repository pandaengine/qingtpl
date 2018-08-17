<?php
namespace qtests;
/**
 * 移除注释
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class CommentTest extends Base{
    /**
     * 
     */
    public function test(){
    	$compiler=$this->newCompiler();
    	//移除html注释
    	$compiler->removeHtmlComment=true;
    	//移除php注释
    	$compiler->removePhpComment=true;
    	//#移除空行
    	$compiler->removeEmptyRows	=true;
    	//#移除模版空格
    	$compiler->removeSpace		=false;
    	//$compiler->debug=true;
    	$viewFile	=__DIR__.'/comment/index.html';
    	$cacheFile	=__DIR__.'/comment/~index.html';
    	$compileFile=__DIR__.'/comment/index.c.html';
    	$compiler->compile($viewFile,$cacheFile);
    	//
    	$this->assertTrue($this->compareFile($cacheFile,$compileFile));
    }
}
?>