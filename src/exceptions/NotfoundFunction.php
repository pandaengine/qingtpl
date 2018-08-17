<?php
namespace qingtpl\exceptions;
/**
 * 找不到函数
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class NotfoundFunction extends \Exception{
	/**
	 * @param string $func
	 */
	public function __construct($func){
		parent::__construct('函数不存在：'.$func);
	}
}
?>