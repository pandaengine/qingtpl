<?php
namespace qingtpl\exceptions;
/**
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
class NotfoundTpl extends \Exception{
	/**
	 * @param string $tpl
	 */
	public function __construct($tpl){
		parent::__construct('模版不存在：'.$tpl);
	}
}
?>