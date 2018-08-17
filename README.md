
# QingTpl超轻量视图编译引擎

QingTpl模版编译引擎,模版编译组件,qingmvc tamplate compile component

[语法手册](docs/SUMMARY.md)

- http://qingmvc.com
- http://qingcms.com
- http://logo234.com  
- http://mangdian.net  

## 简单的视图编译器

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

# 语法手册

* [0.简介](docs/0.简介.md)
* [1.0.配置和使用](docs/1.0.配置和使用.md)
* [1.1.配置和使用.QingMVC](docs/1.1.配置和使用.QingMVC.md)
* [2.0.模版常量](docs/2.0.模版常量.md)
* [2.1.模版常量.宏定义define](docs/2.1.模版常量.宏定义define.md)
* [3.条件编译](docs/3.条件编译.md)
* [4.0.函数](docs/4.0.函数.md)
* [4.1.前置编译函数](docs/4.1.前置编译函数.md)
* [5.0.包含文件](docs/5.0.包含文件.md)
* [5.1.包含视图文件](docs/5.1.包含视图文件.md)
* [5.2.包含纯文本文件](docs/5.2.包含纯文本文件.md)
* [6.静态内容-不解析内容](docs/6.静态内容-不解析内容.md)
* [7.变量](docs/7.变量.md)
* [8.区块](docs/8.区块.md)
* [9.条件判断](docs/9.条件判断.md)
* [10.循环遍历](docs/10.循环遍历.md)
* [11.其他](docs/11.其他.md)
* [12.插件](docs/12.插件.md)
* [13.模版注释](docs/13.模版注释.md)
* [14.清除注释或多余空格](docs/14.清除注释或多余空格.md)
* [README](docs/README.md)
* [SUMMARY](docs/SUMMARY.md)

