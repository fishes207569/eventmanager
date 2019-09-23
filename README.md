JsonDiff for Yii2
=================
Event manager By Biz

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist ccheng/event-manager "*"
```

or add

```
"event-manager": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

执行数据迁移以添加表结构:

```shell
yii migrate --migrationPath=@vendor/ccheng/event-manager/src/migrations
```

添加事件管理模块
```php
return [
	'modules' => [
		'event' => [
                    'class' => 'EventManager\Module',
                    'layout'=>'{layout}'
		]
		...
	]
];
```

事件列表路由：
```
http://localhost/path/to/index.php?r=event/event/index
```

时间时间线路由
```
http://localhost/path/to/index.php?r=event/event/history
```