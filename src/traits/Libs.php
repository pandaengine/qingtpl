<?php 
namespace qingtpl\traits;
/**
 * 标签库 
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
trait Libs{
// class Libs{
	/**
	 * 只要初始化一次即可
	 * 
	 * @var boolean
	 */
	protected $tagLibs=[];
	/**
	 * 替换开始结束标签
	 * // {  }
	 * //\{ \}
	 * 
	 * @param string $content
	 * @return string
	 */
	protected function replaceTag($tag){
		$tagBegin=$this->tagBegin;
		$tagEnd  =$this->tagEnd;
		//替换默认的开始和结束标签|\{|\}
		if($tagBegin!='{' || $tagEnd!='}'){
			$search =array('{'  	,'}'	);
			$replace=array($tagBegin,$tagEnd);
			return str_replace($search,$replace,$tag);
		}
		return $tag;
	}
	/**
	 * 预先准备标签库
	 *
	 * @param string $content
	 * @return string
	 */
	protected function formatTag($tag){
		$tag=$this->replaceTag($tag);
		$tag=$this->prepareTag($tag);
		return $tag;
	}
	/**
	 * 预先准备标签库
	 * 
	 * @param string $content
	 * @return string
	 */
	protected function getTagLibs(){
		if($this->tagLibs){
			return $this->tagLibs;
		}
		$tagBegin=$this->tagBegin;
		$tagEnd  =$this->tagEnd;
		//#条件判断
		$tags_condition=array(
				"{if([:any])}"				=>"<?php if($1){ ?>",
				"{/if}"						=>"<?php } ?>",
				"{else/?}" 					=>"<?php }else{ ?>",
				"{elseif([:any])/?}"		=>"<?php }elseif($1){ ?>",
				"{else[:blank]if([:any])/?}" =>"<?php }elseif($1){ ?>",
				//"{switch([:any])}"   		=>"<?php switch($1){ \?\>",  				    //switch和case间很容易就产生空格
				"{switch([:any])}[:blank]{case[:any]:}" =>"<?php switch($1){ case $2: ?>",  //为了避免switch和case间产生空格
				"{/switch}"   				=>"<?php } ?>",
				"{case[:any]:}"   			=>"<?php case $1: ?>",
				"{break;}"   				=>"<?php break; ?>",
				"{default:}"   				=>"<?php default: ?>",
		);
		//#循环
		$tags_loop=array(
				"{for([:any])}"				=>"<?php for($1){ ?>",
				"{/for}"					=>"<?php } ?>",
				"{foreach([:any])}"			=>"<?php foreach($1){ ?>",
				"{/foreach}"				=>"<?php } ?>",
				"{while([:any])}"			=>"<?php while($1){ ?>",
				"{/while}"					=>"<?php } ?>",
				"{do}"						=>"<?php do{ ?>",
				"{dowhile([:any])}"			=>"<?php }while($1); ?>",   //while和以上的while冲突，需要使用dowhile
		);
		/*
		 * - {$name}   变量  {$varName}  <?php echo $varName; /?/>
		* - {:hook()} 函数返回结果并输出          <?php echo hook(''); /?/>
		* - {~run()}  执行某个函数，没有输入  <?php run(); /?/>
		* - {php} {/php} <?php \?\>
		*/
		$tags_other=array(
				"{echo [:nonempty]}"	=>"<?php echo $1;?>",	 //{echo "123".$abc}
				"{php}"					=>"<?php ",
				"{/php}"				=>"?>",
				"{{\\$[:any]}}"			=>"<?php echo htmlentities(\\$$1);?>", 		//{$i} echo htmlentities($i);|输出文本，转义html实体
				"{\\$[:any]}"			=>"<?php echo \\$$1;?>", 					//{$i} echo $i;|输出变量
				"{@\\$[:any]}"			=>"<?php echo @\\$$1;?>", 					//{@$i} echo @$i;|输出变量，屏蔽notice错误
				"{:[:str]([:any])}"		=>"<?php echo $1($2);?>",					//{:fun(1,2,3)}|执行并输出函数结果
				"{~[:str]([:any])}"		=>"<?php $1($2);?>",	 					//{~fun(1,2,3)}|仅执行函数
		);
		
		$tags=(array)array_merge($tags_condition,$tags_loop,$tags_other);
		$new=array();
		foreach($tags as $tag=>$code){
			$tag=$this->formatTag($tag);
			$new[$tag]=$code;
		}
		return $this->tagLibs=$new;
	}
	/**
	 * 对以下的标签做简单替换
	 * if else elseif else if
	 * foreach for switch
	 * 
	 * @param string $content
	 * @return string
	 */
	public function _compileLibs($content){
		$libs	=(array)$this->getTagLibs();
		$content=preg_replace(array_keys($libs),array_values($libs),$content);
		return $content;
	}
}
?>