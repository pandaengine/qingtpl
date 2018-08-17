<?php 
namespace qingtpl\traits;
/**
 * 变量标签
 * {{$var}}
 * {$var}
 * 				
 * "{{\\$[:any]}}"			=>"<?php echo htmlentities(\\$$1);?>", 		//{$i} echo htmlentities($i);|输出文本，转义html实体
 * "{\\$[:any]}"			=>"<?php echo \\$$1;?>", 					//{$i} echo $i;|输出变量
 * 
 * @deprecated 复杂化编程！
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
trait Variable{
// class Variable{
	/**
	 *
	 * @param string $content
	 * @return string
	 */
	public function _compileVariable($content){
		$content=$this->defaultValue($content);
		$content=$this->prefixSuffix($content);
		return $content;
	}
	/**
	 * 默认值|拥有默认值的变量输出方式
	 * {$name}
	 * {:default($a,'默认值'))} | default是个函数
	 *
	 * {$a|'默认值'}
	 * {$a|"默认值"}
	 * {$a|默认值}
	 * {$tagInfo['desp']|'默认值'}
	 * {$tagInfo['desp']|"默认值"}
	 * {$tagInfo['desp']|默认值}
	 * 
	 * 只支持字符串变量|不支持数组等
	 *
	 * @param string $content
	 * @return string
	 */
	protected function defaultValue($content){
		$tagBegin=$this->tagBegin;
		$tagEnd  =$this->tagEnd;
		$patterns='/\{(\$.*?)\|[\'\"]?(.*?)[\'\"]?\}/';
		//模版解析时就执行的函数
		$content=preg_replace_callback($patterns,function($matches){
			$varName =$matches[1];
			$defValue=$matches[2];
			/*
			$code="<?php if({$varName}==''){ echo '{$defValue}';}else{ echo {$varName}; } ?>";
			*/
			$code="<?php echo str_default({$varName},'{$defValue}'); ?>";
			return $code;
		},$content);
		return $content;
	}
	/**
	 * 变量前缀后缀|变量不为空的时候输出前缀或者后缀
	 * 只支持字符串数据|不支持数组等|Prefix|Suffix
	 * 
	 * ---------------------------------------
	 * {$name}
	 * {:default($a,'默认值'))} | default是个函数
	 * 
	 * {$a>'后缀'} $a.'后缀'
	 * {$a<'前缀'} '前缀'.$a
	 * {$a>"后缀"}
	 * {$a>后缀}
	 * {$row['stars']<' / '}
	 * {$row['名称']<' / '}
	 * 
	 * $patterns='/\{(\$[0-9a-zA-Z_]+)[\>\<][\'"]?(.*?)[\'"]?\}/';//不能使用数组等
	 * 
	 * \S 非空
	 * \s 空字符
	 * ---------------------------------------
	 * 
	 * @param string $content
	 * @return string
	 */
	protected function prefixSuffix($content){
		$tagBegin=$this->tagBegin;
		$tagEnd  =$this->tagEnd;
		$patterns='/\{(\$[^{}<>]+)([\>\<])[\'"]?(.*?)[\'"]?\}/';
		//模版解析时就执行的函数
		$content=preg_replace_callback($patterns,function($matches){
			$varName =$matches[1];
			$type    =$matches[2];
			$value	 =$matches[3];
			if($type=='>'){
			//变量后缀
				/*
				$code="<?php if((string){$varName}!=''){ echo {$varName}.'{$value}'; } ?>";
				*/
				$code="<?php echo str_suffix({$varName},'{$value}'); ?>";
			}else{
			//变量前缀
				/*
				$code="<?php if((string){$varName}!=''){ echo '{$value}'.{$varName}; } ?>";
				*/
				$code="<?php echo str_prefix({$varName},'{$value}'); ?>";
			}
			return $code;
		},$content);
		
		return $content;
	}
}
?>