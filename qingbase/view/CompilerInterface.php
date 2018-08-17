<?php
namespace qing\view;
/**
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright Copyright (c) 2013 http://qingmvc.com
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 */
interface CompilerInterface{
	/**
     * 编译模板|解析模版并存入缓存
     * 
	 * @param string $viewFile
	 * @param string $cacheFile
	 */
    public function compile($viewFile,$cacheFile);
}
?>