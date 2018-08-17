<?php
namespace qing\com;
/**
 * - 组件创建器
 * - 代码级别配置组件
 * 
 * @TODO 不要在组件注册方法内部尝试调用获取组件/死循环
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
abstract class ComCreator extends Component implements ComCreatorInterface{
	/**
	 * 注册组件
	 *
	 * @return Component
	 */
	abstract public function create();
	/**
	 * 取得组件配置
	 *
	 * @return array
	 */
	public function getConf(){
		return coms()->getService($this->comName);
	}
}
?>