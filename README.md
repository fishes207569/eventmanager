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
                'class'=>'ccheng\eventmanager\Module',
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

组件配置信息（图像上传与事件级别与事件相关系统依赖）
配置信息需配置在 params 中，返回未配置信息读取的回调函数
```
event_manager_config:
[
    "event_level"=>[
        "success"=>[
            "label"=>"良好",
            "color"=>"#324523"
        ],
        ...
    ],
    "event_system"=>[],
]

qcloud_config:
[
    "q_cloud_config"=>StdClass{
            public $bucket;
            public $app_id;
            public $secret_id;
            public $secret_key;
        },
    "q_cloud_config_for_external"=>[
            'region'      => 'sh',
            'credentials' => [
                'secretId'  => null,
                'secretKey' => null,
            ],
    ]
]
```