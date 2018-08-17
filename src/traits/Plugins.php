<?php 
namespace qingtpl\traits;
use qingtpl\PluginInterface;
/**
 * 插件支持
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
// class Plugins{
trait Plugins{
	/**
	 * 插件扩展库|功能选项类库
	 * #可以添加自定义的插件扩展
	 * 
	 * @var array
	 */
	protected $plugins=array();
	/**
	 * @param array $list
	 */
	public function setPlugins(array $list){
		$this->plugins=$list;
	}
	/**
	 * @param array $list
	 */
	public function pushPlugins(array $list){
		foreach($list as $p){
			$this->plugins[]=$p;
		}
	}
	/**
	 * 编译插件扩展
	 *
	 * @param string $content
	 * @return string
	 */
	protected function compilePlugins($content){
		foreach((array)$this->plugins as $plugin){
			/*@var $plugin \qingtpl\PluginInterface */
			if(!is_object($plugin)){
				//#字符串
				$plugin=new $plugin();
			}
			if(!$plugin instanceof PluginInterface){
				throw new \Exception('not match interface');
			}
			$res=$plugin->compile($content);
			if($res!==null && $res!==false){
				$content=$res;
			}
		}
		return $content;
	}
}
?>