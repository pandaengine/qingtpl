
# QingTpl超轻量模版编译引擎

## 简单的模版编译器

- 只是简单的替换模版标签成原生php代码`<?php ?>`
- 没有其他多余的依赖
- 只编译，并不渲染
- 被包含文件，修改后不能及时的更新编译缓存，不能解决


# composer载入qingtpl

```
"require": {
	"php": ">=5.3.0",
	"qingmvc/qingtpl":"dev-master",
}
```

# qingmvc载入qingtpl

```
//命名空间映射
'namespaces' =>
[
	'qingtpl'=>'/qingtpl/src'
],
```

# QingMVC配置

```
//组件列表
'components'=>
[
	//视图组件
	'view'=>
	[
		'class'=>'\qing\view\CachedView'
	],
	//视图编译组件
	'view.compiler'=>
	[
		'creator'=>'\qingtpl\CompilerCreator',
	]
]
```

# QingMVC使用

```
//$viewFile 原始视图文件
//$cacheFile 视图缓存文件
$compiler=com('view.compiler');
$compiler->compile($viewFile,$cacheFile);
```

# Summary

* [0.简介](0.简介.md)
* [1.0.配置和使用](1.0.配置和使用.md)
* [1.1.配置和使用.QingMVC](1.1.配置和使用.QingMVC.md)
* [2.0.模版常量](2.0.模版常量.md)
* [2.1.模版常量.宏定义define](2.1.模版常量.宏定义define.md)
* [3.条件编译](3.条件编译.md)
* [4.0.函数](4.0.函数.md)
* [4.1.前置编译函数](4.1.前置编译函数.md)
* [5.0.包含文件](5.0.包含文件.md)
* [5.1.包含视图文件](5.1.包含视图文件.md)
* [5.2.包含纯文本文件](5.2.包含纯文本文件.md)
* [6.静态内容-不解析内容](6.静态内容-不解析内容.md)
* [7.变量](7.变量.md)
* [8.区块](8.区块.md)
* [9.条件判断](9.条件判断.md)
* [10.循环遍历](10.循环遍历.md)
* [11.其他](11.其他.md)
* [12.插件](12.插件.md)
* [13.模版注释](13.模版注释.md)
* [14.清除注释或多余空格](14.清除注释或多余空格.md)
* [README](README.md)
* [SUMMARY](SUMMARY.md)
