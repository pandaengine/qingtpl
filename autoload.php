<?php
/**
 *
* @author xiaowang <736523132@qq.com>
* @copyright Copyright (c) 2013 http://qingmvc.com
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
*/
if(!class_exists('AutoloadLite')){
	class AutoloadLite{
		/**
		 * @var array
		 */
		public static $namespaces=[];
		/**
		 * @param string $fullClassName 完整类名，包括命名空间
		 * @return [$first,$other/子目录+类名]
		 */
		public static function getNSFirstPart($fullClassName){
			if(($nsIndex=strpos($fullClassName,'\\'))!==false){
				//#命名空间不为空
				$first=substr($fullClassName,0,$nsIndex);
				$other=substr($fullClassName,$nsIndex+1);
			}else{
				//#命名空间为空
				$first='';
				$other=$fullClassName;
			}
			return [$first,$other];
		}
		/**
		 * @param string $fullClassName
		 * @return boolean
		 */
		public static function autoload($fullClassName){
			list($first,$other)=self::getNSFirstPart($fullClassName);
			if(isset(self::$namespaces[$first])){
				$file=self::$namespaces[$first].DIRECTORY_SEPARATOR.$other.'.php';
				if(is_file($file)){
					require_once $file;
					return true;
				}
			}
			return false;
		}
	}
}
//
AutoloadLite::$namespaces['qingtpl']=__DIR__.'/src';
//自动加载
spl_autoload_extensions('.php');
spl_autoload_register([AutoloadLite::class,'autoload']);

?>