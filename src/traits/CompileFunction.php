<?php 
namespace qingtpl\traits;
use qingtpl\exceptions\NotfoundFunction;
/**
 * 前置编译有：include标签|编译函数
 * 
 * 解析编译函数
 * ---
 * 前置模版函数|模版解析的时候就执行的函数|只用于配合模版解析
 * public $compileFunction	=array();
 * ---
 * {C:_U()}
 * {C:var_dump()}
 * {#:_U()}
 * {#:C:var_dump()}
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
trait CompileFunction{
// class CompileFunction{
	/**
	 * 前置模版函数|模版解析的时候就执行的函数|只用于配合模版解析
	 *
	 * - e\v\a\l模式|危险！
	 * - 不推荐使用变量|直接使用字符串
	 *
	 * @deprecated
	 * @var boolean
	 */
	public $compileFunctionEvalMode=false;
	/**
	 *
	 * @param string $content
	 * @return string
	 */
	public function _compileCompileFunction($content){
		$tagBegin=$this->tagBegin;
		$tagEnd  =$this->tagEnd;
		
		//模版解析时就执行的函数
		//$pattern=$this->preparePattern("{[C#]:[:str]([:any])}")."";
		$pattern="/{$tagBegin}[C#]\:([0-9a-zA-Z_\\\\]*?)\((.*?)\){$tagEnd}/is";
		$content=preg_replace_callback($pattern,function($matches){
			return $this->compileFunction($matches[1],$matches[2],$matches[0]);
		},$content);
		
		return $content;
	}
	/**
	 * 编译函数|模版解析的时候就执行的
	 * ---
	 * - U() 解析没有动态输入参数的url
	 * - e\v\a\l较危险|注意安全性问题
	 *   $function=$name.'('.$vars.');';
	 *   return e\v\a\l($function);
	 * - 不推荐传入变量|特别是$_GET,$_POST等用户数据变量
	 * ---
	 *  _U(null,null,'edit',array('linkid'=>'{{linkid}}'));
	 * ---
	 * 注意e\v\a\l中的变量|只能获取当前类方法的局部变量和全局变量|不能使用模版变量|只能使用前置编译变量
	 *
	 * @param string $name    	函数名称		 |dump
	 * @param string $var		函数参数  a,b,c  |'1','2','3'
	 * @param string $match		匹配的字符串	 |{C:dump('1','2','3')}
	 */
	protected function compileFunction($name,$vars,$match){
		if(false && $this->compileFunctionEvalMode){
			//#禁用
			//e\v\a\l模式，支持数组/对象等数据类型
			//删除一些可能被用户操作的危险全局变量/e\v\a\l和全局变量一起使用是超级危险的
			//模版变量在该类方法内是不能使用的|只能使用方法局部变量
			if(strpos($vars,'$')!==false){
				throw new \Exception('为安全起见:预编译的函数参数不能包含变量/不能包含$符号{function}'.$match);
			}
			//组成php代码|return dump('1234');
			$function="return {$name}({$vars});";
			//$res=e\v\a\l($function);
			$res='';
			return $res;
		}else{
			//安全模式：只支持字符串输入，不支持数组|只支持文本
			$args=$this->compileFunctionArgs($vars);
			if(!function_exists($name)){
				throw new NotfoundFunction($name);
			}
			return call_user_func_array($name,$args);
		}
	}
	/**
	 * 处理匹配到的多个参数函数
	 * {C:U(null,"add","doAdd")}
	 * {C:F(true,false,"add",array(),['plugin'=>'ppp'])}
	 * 
	 * preg_replace:
	 * \\0和$0代表完整的模式匹配文本
	 * ${1}1
	 * 
	 * @param string $vars
	 */
	protected function compileFunctionArgs($vars){
		//#无参数
		if(!$vars){return [];}
		$_arrs=[];
		$_arrc=0;
		//格式化数组数据，避免被逗号分割,
		$vars=preg_replace_callback('/(\[.*?\]|array\(.*?\))/i',function($matches)use(&$_arrc,&$_arrs){
			$key='arr{{'.$_arrc.'}}';
			$_arrc++;
			$_arrs[$key]=$matches[0];
			return $key;
		},$vars);
		//
		$args=explode(",",$vars);
		//#还原数组数据
		if($_arrs){
			$args=array_map(function($v)use($_arrs){
				if(isset($_arrs[$v])){
					return $_arrs[$v];
				}else{
					return $v;
				}
			},$args);
		}
		$args=array_map(array($this,"compileFunctionArg"),$args);
		return $args;
	}
	/**
	 * 处理每个参数
	 * 
	 * @param string $arg
	 */
	protected function compileFunctionArg($arg){
		if(!$arg){return $arg;}
		$matches=[];
		//#数组，转换为json解析|[]|array()
		if(preg_match('/^\[(.*?)\]$/',$arg,$matches)>0 || preg_match('/^array\((.*?)\)$/',$arg,$matches)>0){
			$json=$matches[1];
			//必须双引号，['a'=>'a'] {'a':'a'}
			$json=str_replace('=>',':',$json);
			$json=str_replace('\'','"',$json);
			//
			$arr=json_decode("{{$json}}",true);
			if(is_array($arr)){
				return $arr;
			}
			$arr=json_decode("[{$json}]",true);
			if(is_array($arr)){
				return $arr;
			}
			//解析失败
			return [];
		}
		//#字符串，有引号
		if(preg_match('/^[\'"].*[\'"]$/i',$arg)){
			//去除两边的单双引号
			$arg=trim($arg,'\"\'');
			return $arg;
		}
		//#整型浮点数
		if(is_numeric($arg)){
			if(strpos($arg,'.')!==false){
				return (float)$arg;
			}else{
				return (int)$arg;
			}
		}
		//#布尔值/null
		$arg=strtolower($arg);
		if($arg=="true"){
			return true;
		}
		if($arg=="false"){
			return false;
		}
		if($arg=="null"){
			return null;
		}
		return null;
	}
}
?>