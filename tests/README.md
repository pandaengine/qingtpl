
# 执行整个测试套件

```
cd .
phpunit --bootstrap ./autoload.php ./

```

# 测试单个用例

```
#phpunit ./
#phpunit -c phpunit.xml ./
#phpunit -c phpunit.testing.xml

phpunit --bootstrap ./autoload.php ./ConstTest.php
phpunit --bootstrap ./autoload.php ./ConditionalTest.php

phpunit --bootstrap ./autoload.php ./FunctionTest.php
phpunit --bootstrap ./autoload.php ./FunctionCompileTest.php

phpunit --bootstrap ./autoload.php ./IncludetextTest.php
phpunit --bootstrap ./autoload.php ./IncludeTest.php

phpunit --bootstrap ./autoload.php ./LiteralTest.php
phpunit --bootstrap ./autoload.php ./VarTest.php
phpunit --bootstrap ./autoload.php ./SectionTest.php

phpunit --bootstrap ./autoload.php ./ConditionTest.php
phpunit --bootstrap ./autoload.php ./Demo01Test.php
phpunit --bootstrap ./autoload.php ./CommentTest.php

```

