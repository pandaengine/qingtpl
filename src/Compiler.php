<?php
namespace qingtpl;
use qing\com\Component;
use qing\view\CompilerInterface;
use qingtpl\exceptions\NotfoundTpl;
/**
 * qingtpl超轻量模板编译引擎
 *
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class Compiler extends Component implements CompilerInterface{
	/**
	 * 系统自带编译方法
	 */
	use traits\IncludeTag;
	use traits\Plugins;
	
	use traits\Conditional;
	use traits\DefineConst;
	
	use traits\CleanComment;
	use traits\Literal;
	use traits\Variable;
	use traits\IncludeText;
	use traits\Libs;
	use traits\ReplaceString;
	use traits\Cleaner;
	use traits\CompileFunction;
	use traits\Tpl;
	use traits\Section;
	/**
	 * 系统默认编译器|trait管理
	 *
	 * @var array
	 */
	protected $compilers=
	[
		//清除模版注释，首先处理
		'CleanComment',
		//{includetext '.js'}
		'IncludeText',
		//text|静态文本/纯文本标签
		'Literal',
		//编译函数
		'CompileFunction',
		//替换字符
		'ReplaceString',
		//常量定义和使用|编译函数后
		'DefineConst',
		//解析库
		'Libs',
		//条件编译|先解析常量
		'Conditional',
		//区块
		'Section',
		//'Tpl',
		//格式化清除
		'Cleaner',
	];
	/**
	 * 编译器实例
	 *
	 * @var Compiler
	 */
	public static $compiler;
	/**
	 *
	 * @param Compiler $compiler
	 */
	public static function setCompiler($compiler){
		self::$compiler=$compiler;
	}
	/**
	 *
	 * @return $this
	 */
	public static function compiler(){
		return self::$compiler;
	}
	/**
	 * 当前编译的模版文件
	 * 
	 * @var string
	 */
	protected $viewFile='';
	/**
	 * 模版缓存文件路径 
	 * 
	 * @var string
	 */
	protected $cacheFile='';
	/**
	 * 是否保存缓存
	 * 
	 * @var string
	 */
	protected $cacheOn=false;
	/**
	 * 模版内容缓存
	 *
	 * @var string
	 */
	protected $content;
    /**
     * 标签别名|文字,文本
     * 
     * @var array
     */
    protected $tagAlias=[
    					'literal'=>['literal','text','textonly']
    				 ];
    /**
     * 模版编译回调绑定事件
     *
     * @var array
     */
    protected $_callbackEvents=array();
    /**
     * 页面缓存
     *
     * @var array
     */
    protected $_caches=array();
    
    //[公开属性]---
    
    /**
     * 标签开始  {include
     * 
     * @var string
     */
    public $tagBegin='{';
    /**
     * 标签结束
     * 
     * @var string
     */
    public $tagEnd='}';
    /**
     * 模版文件后缀 |include定位文件
     * include "common:header"
     * 
     * @var string
     */
    public $viewSuffix='.html';
    /**
     * 编译后缓存文件后缀
     * 
     * @var string
     */
    public $cacheSuffix='.html';
    /**
     * 设置安全代码,在每个模版缓存顶部添加
     * 
     * @var string
     */
    public $safeScript='';
    /**
     * 是否开启模版编译调试
     *
     * @var boolean
     */
    public $debug=false;
    /**
     * 是否已初始化
     *
     * @var boolean
     */
    protected $inited=false;
	/**
     * 构造函数
     */
    public function __construct(){
    	self::setCompiler($this);
    }
    /**
     * 初始化|只执行一次
     */
    protected function init(){
    	$this->inited=true;
    	$this->tagBegin=preg_quote($this->tagBegin,'/');
    	$this->tagEnd  =preg_quote($this->tagEnd,'/');
    }
    /**
     * 编译模板文件|解析模版并存入缓存
     * 
     * @param string $viewFile	模版文件
     */
    public function compile($viewFile,$cacheFile){
    	$this->viewFile=$viewFile;
    	if(!is_file($viewFile)){
    		throw new NotfoundTpl($viewFile);
    	}
    	$this->cacheOn($cacheFile,$cacheFile>'');
    	//读取模板文件内容
    	$content=file_get_contents($viewFile);
    	$content=$this->compileText($content);
    	if($this->cacheOn){
    		//$content=|保存缓存的安全代码注释代码不返回|避免整合模版信息冗余
    		$this->saveCache($this->content,$this->cacheFile);
    	}
    	return $content;
    }
    /**
     * 编译文本
     * - 与文件路径相关的编译无法处理，include等
     * 
     * @param string $content 模版内容
     */
    public function compileText($content){
    	if(!$this->inited){
    		//#只初始化一次
    		$this->init();
    	}
    	//#编译include标签|合并为一个文件
    	$this->content=$this->compileIncludeTag($content,$this->viewFile);
    	//#缓存合并include标签后的模版
    	if($this->debug && $this->cacheFile>''){
    		$this->saveCache($this->content,$this->cacheFile.'~include'.$this->viewSuffix);
    	}
    	$this->content=$this->compileTpl($this->content);
    	//恢复缓存
    	$this->rebackCaches();
    	//执行绑定的回调函数|只能操作$this->content
    	$this->runCallbackEvents();
    	//$this->reset();
    	return $this->content;
    }
    /**
     * 编译模版
     *
     * @param string $content
     * @return string
     */
    protected function compileTpl($content){
    	//#系统编译器|成员方法
    	if($this->compilers){
    		foreach((array)$this->compilers as $c){
    			$method='_compile'.ucfirst($c);
    			if(method_exists($this,$method)){
    				//#成员方法
    				$res=$this->$method($content);
    			}else{
    				throw new \Exception('编译方法不存在:'.$c);
    			}
    			if($res!==null && $res!==false){
    				$content=$res;
    			}
    		}
    	}
    	$content=$this->compilePlugins($content);
    	return $content;
    }
    /**
     * 需要手动重置
     * 每次渲染后重置
     * 
     * @return $this
     */
    public function reset(){
    	$this->resetConsts();
    }
    /**
     * 缓存设置
     * 
     * @param string $cacheFile	模版缓存文件
     * @param string $cacheOn	是否缓存模版内容
     * @return $this
     */
    public function cacheOn($cacheFile,$cacheOn=true){
    	$this->cacheFile=$cacheFile;
    	$this->cacheOn  =$cacheOn;
    	return $this;
    }
    /**
     * 保存模版缓存
     *
     * @param string $content
     * @param string $cacheFile
     * @return string
     */
    public function saveCache($content,$cacheFile=''){
    	//添加安全代码
    	$content=$this->safeScript.$content;
    	if($this->debug){
    		//添加模版文件注释
    		$content='<?php /* '.$this->viewFile.' */ ?>'."\n".$content;
    	}
    	if(!$cacheFile){
    		return '';
    	}
    	//检测模板目录  true 递归创建目录
    	$cacheDir=dirname($cacheFile);
    	if(!is_dir($cacheDir)){
    		$res=@mkdir($cacheDir,MOD_DIR,true);
    		if(!$res){
    			throw new \Exception(L()->filesys_mkdir_err.$cacheFile);
    		}
    	}
    	//写入Cache文件
    	if(false===file_put_contents($cacheFile,$content)){
    		throw new \Exception('模版缓存写入失败:'.$cacheFile);
    	}
    }
    /**
     * [:any]		:任何字符 (.*?)
     * [:blank] 	:空格	   \s*?
     * [:str]   	:普通字符  ([0-9a-zA-Z]*?)
     * [:nonempty]	:非空 ([^\s]*?)
     * /i:大小写不敏感
     * /s:.包括回车
     *
     * @name preparePattern
     * @access public 公共|供功能选项调用 
     * @param  string $tag
     * @param  string $charlist 要转义的字符
     * @return string
     */
    public function prepareTag($tag,$charlist='/()'){
    	$tag=addcslashes($tag,$charlist);
    	$rules=array();
    	//任何字符|捕获
    	$rules['[:any]']='(.*?)';
    	//空白字符|不捕获
    	$rules['[:blank]']='\s*?';
    	//非空|捕获
    	$rules['[:nonempty]']='([^\s]*?)';
    	//普通字符|捕获
    	$rules['[:str]']='([0-9a-zA-Z_]*?)';
    	//标签|变量名|函数名
    	$rules['[:tag]']='([0-9a-zA-Z_]*?)';
    	//$tag=str_replace(array_keys($rules),array_values($rules),$tag);
    	$tag=strtr($tag,$rules);
    	return '/'.$tag.'/is';
    }
    /**
     * 
     * @param string $tag
     * @param string $charlist
     * @return string
     */
    public function preparePattern($tag,$charlist='/()'){
    	return $this->prepareTag($tag,$charlist);
    }
    /**
     * preg_quote — 转义正则表达式字符
     * 正则表达式特殊字符有： . \ + * ? [ ^ ] $ ( ) { } = ! < > | : -
     * 
     * @param string $string
     * @param string $delimiter 指定了可选参数 delimiter|它也会被转义
     * @return string
     */
    public function prepareQuote($string,$delimiter=''){
    	return preg_quote($string,$delimiter);
    }
    /**
     * 绑定一个回调事件
     *
     * @access public 公共|供功能选项调用
     * @param  mixed  $callback
     * @param  string $id 回调标识/控制回调顺序
     */
    public function pushCallbackEvent($callback,$id=null){
    	if($id===null){
    		$this->_callbackEvents[]=$callback;
    	}else{
    		$this->_callbackEvents[$id]=$callback;
    	}
    }
    /**
     * 执行一个回调事件
     * 
     * @throws \Exception
     */
    protected function runCallbackEvents(){
    	$events=$this->_callbackEvents;
    	foreach($events as $k=>$event){
    		if($event instanceof \Closure){
    			//匿名函数/闭包 Closure
    			call_user_func($event);
    			unset($this->_callbackEvents[$k]);
    		}else{
    			throw new \Exception('暂只支持闭包匿名函数');
    		}
    	}
    }
    /**
     * 还原缓存
     * 
     * {!---#cache#---[includetext_1]---#cache#---!}
     * 
     * @param string $tag 指定分组
     * @return string
     */
    protected function rebackCaches($tag=''){
    	if(!$this->_caches){
    		//#没有缓存
    		return;
    	}
    	//还原被缓存的内容，必须在所有解析动作之后才能还原|\d=数字
    	if($tag>''){
    		$pattern=preg_quote($tag,'/').'_\d+';
    	}else{
    		$pattern='[a-z0-9_]+';
    	}
    	$pattern='/\{\!\-\-\-\#cache\#\-\-\-\[('.$pattern.')\]\-\-\-\#cache\#\-\-\-\!\}/is';
    	$this->content=preg_replace_callback($pattern,function($matches)use($tag){
    		$id=$matches[1];
    		if(isset($this->_caches[$id])){
    			$cache=$this->_caches[$id];
    			unset($this->_caches[$id]);
    			return $cache;
    		}else{
    			return '';
    		}
    	},$this->content);
    	
    	//如果文本内容没有全被恢复则抛出异常
    	if(@$this->_caches[$tag]){
    		throw new \Exception('缓存恢复不完全:'.$tag);
    	}
    }
	/**
	 * 添加缓存原始内容
	 * 
     * @param string $cache
     * @param string $tag  	main/literal/标签分组
     * @return string
     */
	public function pushCache($cache,$tag='default'){
		$count=count($this->_caches);
		$count++;
		$id=$tag.'_'.$count;
		$this->_caches[$id]=$cache;
		return "{!---#cache#---[{$id}]---#cache#---!}";
	}
	/**
     * 
     * @param string $tagName
     * @return string
     */
    public function getTagAlias($tagName){
    	if(!isset($this->tagAlias[$tagName])){
    		//#没有别名
    		return [$tagName];
    	}
    	return (array)$this->tagAlias[$tagName];
    }
    /**
     *
     * @param string $tagName
     * @return string
     */
    public function setTagAlias($tagName,$alias){
    	$this->tagAlias[$tagName]=$alias;
    }
    /**
     *
     * @param string $name
     * @param string $isEnd
     * @return string
     */
    public function getTag($name,$isEnd=false){
    	$tagBegin=$this->tagBegin;
    	$tagEnd  =$this->tagEnd;
    	if(!$isEnd){
    		return "{$tagBegin}{$name}{$tagEnd}";
    	}else{
    		return "{$tagBegin}\/{$name}{$tagEnd}";
    	}
    }
	/**
	 *
	 * @param string $includeFile   包含文件字符串
	 * @param string $relativeFile  递归时父模版文件|包含子模版的模版|可以是顶级模版下的多层包
	 * @return string
	 */
	public function getIncludeFile($includeFile,$relativeFile=''){
		if(is_file($includeFile)){
			return $includeFile;
		}
		if(!$relativeFile){
			$relativeDir=dirname($this->viewFile);
		}else{
			$relativeDir=dirname($relativeFile);
		}
		$viewSuffix='';
		if(!$this->hasExt($includeFile)){
			//#无后缀|自动添加视图文件后缀
			$viewSuffix=$this->viewSuffix;
		}
		$includeFile=$relativeDir.DS.$includeFile.$viewSuffix;
		$realFile	=realpath($includeFile);
		if(!$realFile){
			throw new NotfoundTpl($includeFile);
		}
		return $realFile;
	}
	/**
	 * 取得上传文件的后缀
	 *
	 * @param string $viewFile
	 * @return string
	 */
	protected function hasExt($viewFile){
		$pathinfo=pathinfo($viewFile);
		return isset($pathinfo['extension']);
	}
	/**
	 *
	 * @return string
	 */
	public function getViewFile(){
		return $this->viewFile;
	}
}
?>