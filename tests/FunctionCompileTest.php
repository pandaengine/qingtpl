<?php
namespace qtests;
//
function func(){
	//dump(func_get_args());
	//dump(var_export(func_get_args(),true));
	return json_encode(func_get_args());
}
/**
 * 前置编译函数
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class FunctionCompileTest extends Base{
    /**
     * 参数测试
     */
    public function test(){
    	$compiler=$this->newCompiler();
    	$tpl="<b>{C:qtests\\func('aaa',\"ABC\",123,3.14,true,FALSE,null,null2,array('k'=>'v','kk'=>'vv'),['k2'=>'v2','kk2'=>'vv2'])}</b>";
    	$tpl2='<b>["aaa","ABC",123,3.14,true,false,null,null,{"k":"v","kk":"vv"},{"k2":"v2","kk2":"vv2"}]</b>';
    	$this->assertTrue($compiler->compileText($tpl)==$tpl2);
    	//dump($compiler->compileText($tpl));
    }
    /**
     * 
     */
    public function test02(){
    	$compiler=$this->newCompiler();
    	$tpl="<b>{C:var_export('qingmvc',true)}</b>";
    	$tpl2='<b>\'qingmvc\'</b>';
    	$this->assertTrue($compiler->compileText($tpl)==$tpl2);
    	//dump($compiler->compileText($tpl));
    }
}
?>