Event manager for Yii2
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

事件时间线路由
```
http://localhost/path/to/index.php?r=event/event/history
```

组件配置信息（图像上传与事件级别与事件相关系统依赖）
配置信息需配置在 params 中，返回未配置信息读取的回调函数
```php
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

添加事件组件接口
```php
    'controllerMap' => [
        'event' => 'ccheng\eventmanager\api\EventController',
    ],
```

事件组件接口文档
###### 一、事件添加

- **请求规则**

|  接口要求项   |          值              |
|-------------|--------------------------|
| Http请求方式 | POST                     |
| URL         | /event/create |

- **查询参数定义**
`查询参数为JSON数据`

| 参数     | 类型      |   必须   |       说明       |    备注                                     |
|---------|-----------|---------|-----------------|---------------------------------------------|
| from_system    | string       |   是    |  系统来源          |                               |
| type | string    |   是    |  请求类型            |        固定值： EventNotify  |
| key | string    |   是    |  请求key            |        自行定义  |
| data | string    |   是    |  请求参数            |          |


- **请求参数（data）定义**

| 参数     | 类型      |   必须   |       说明       |    备注                                     |
|---------|-----------|---------|-----------------|---------------------------------------------|
| event_name | string       |   是    | 事件标题          |                                |
| event_content | string    |   是    |  事件内容           |  富文本编辑器生成的HTML                                        |
| event_date  | string       |   是    |  事件发生的日期时间          |     例如：2019-10-01 10:02:05                           |                                 |
| event_author | string       |   是    |  事件内容作者          |    作者名字                             |
| event_level | string    |   是    |  事件级别           |         可选值:success-一般，info-严重，warning-重大，error-灾难                                |
| event_tags | string       |   是    | 事件标签          |    标签用,分隔                            |

- **输入参数示例**

``` javascript
{
"from_system": "QNN",
"key": "EventNotify_300610375da9388240f27",
"type": "EventNotify",
"data": {
        "event_name":"测试API7999",
        "event_content":"<p>测试标签<strong>富贵树规范</strong><img src=\"http://b.hiphotos.baidu.com/image/h%3D300/sign=ad628627aacc7cd9e52d32d909032104/32fa828ba61ea8d3fcd2e9ce9e0a304e241f5803.jpg\" title=\"1570697662419637.png\" alt=\"图片.png\"/>",
        "event_date":"2019-10-16 17:15:00",
        "event_author":"李四",
        "event_level":"success",
        "event_from_system":"qnn",
        "event_tags":"a,b,c"
        }
}
```

- **返回参数定义**

| 参数     | 类型      |   必须   |       说明       |    备注                                     |
|---------|-----------|---------|-----------------|---------------------------------------------|
| code    | int       |   是    |  返回码          | 0:成功，1:失败                                |
| message | string    |   是    |  说明            |                                             |
| data | object |   是    |  说明            |      事件详情                                       |

- **返回示例**

``` javascript
{
    "code": 0,
    "message": "ok",
    "data": {
        "event_id": 48,
        "event_name": "测试API7999",
        "event_content": "<p>测试标签<strong>富贵树规范</strong><img src=\"http://b.hiphotos.baidu.com/image/h%3D300/sign=ad628627aacc7cd9e52d32d909032104/32fa828ba61ea8d3fcd2e9ce9e0a304e241f5803.jpg\" title=\"1570697662419637.png\" alt=\"图片.png\" onclick=\"window.open(this.src,'_blank')\"/>",
        "event_date": "2019-10-16",
        "event_from_system": "qnn",
        "event_author": "李11四",
        "event_level": "success",
        "event_time": "17:15:00",
        "event_tags": "a,b,c"
    }
}
```

###### 二、事件更新

- **请求规则**

|  接口要求项   |          值              |
|-------------|--------------------------|
| Http请求方式 | POST                     |
| URL         | /event/update?id=xxx |

- **查询参数定义**
`查询参数为JSON数据`

| 参数     | 类型      |   必须   |       说明       |    备注                                     |
|---------|-----------|---------|-----------------|---------------------------------------------|
| from_system    | string       |   是    |  系统来源          |                               |
| type | string    |   是    |  请求类型            |        固定值： EventNotify  |
| key | string    |   是    |  请求key            |        自行定义  |
| data | string    |   是    |  请求参数            |          |

- **请求参数（data）定义**

| 参数     | 类型      |   必须   |       说明       |    备注                                     |
|---------|-----------|---------|-----------------|---------------------------------------------|
| event_name | string       |   是    | 事件标题          |                                |
| event_content | string    |   是    |  事件内容           |  富文本编辑器生成的HTML                                        |
| event_date  | string       |   是    |  事件发生的日期时间          |     例如：2019-10-01 10:02:05                           |
| event_from_system | string    |   是    |  事件来源系统           |     固定值：qnn                                        |
| event_author | string       |   是    |  事件内容作者          |    作者名字                             |
| event_level | string    |   是    |  事件级别           |         可选值:success-一般，info-严重，warning-重大，error-灾难                                |
| event_tags | string       |   是    | 事件标签          |    标签用,分隔                            |

- **输入参数示例**

``` javascript
{
"from_system": "QNN",
"key": "EventNotify_300610375da9388240f27",
"type": "EventNotify",
"data": {
        "event_name":"测试API8000",
        "event_content":"<p>测试标签<strong>富贵树规范</strong><img src=\"http://b.hiphotos.baidu.com/image/h%3D300/sign=ad628627aacc7cd9e52d32d909032104/32fa828ba61ea8d3fcd2e9ce9e0a304e241f5803.jpg\" title=\"1570697662419637.png\" alt=\"图片.png\"/>",
        "event_date":"2019-10-16 17:15:00",
        "event_author":"李四",
        "event_level":"success",
        "event_from_system":"qnn",
        "event_tags":"a,b,c"
        }
}
```

- **返回参数定义**

| 参数     | 类型      |   必须   |       说明       |    备注                                     |
|---------|-----------|---------|-----------------|---------------------------------------------|
| code    | int       |   是    |  返回码          | 0:成功，1:失败                                |
| message | string    |   是    |  说明            |                                             |
| data | object |   是    |  说明            |      事件详情                                       |

- **返回示例**

``` javascript
{
    "code": 0,
    "message": "ok",
    "data": {
        "event_id": 48,
        "event_name": "测试API8000",
        "event_content": "<p>测试标签<strong>富贵树规范</strong><img src=\"http://b.hiphotos.baidu.com/image/h%3D300/sign=ad628627aacc7cd9e52d32d909032104/32fa828ba61ea8d3fcd2e9ce9e0a304e241f5803.jpg\" title=\"1570697662419637.png\" alt=\"图片.png\" onclick=\"window.open(this.src,'_blank')\"/>",
        "event_date": "2019-10-16",
        "event_from_system": "qnn",
        "event_author": "李11四",
        "event_level": "success",
        "event_time": "17:15:00",
        "event_tags": "a,b,c"
    }
}
```

###### 三、事件查询

- **请求规则**

|  接口要求项   |          值              |
|-------------|--------------------------|
| Http请求方式 | GET                     |
| URL         | /event/view?id=xxx |

- **返回参数定义**

| 参数     | 类型      |   必须   |       说明       |    备注                                     |
|---------|-----------|---------|-----------------|---------------------------------------------|
| code    | int       |   是    |  返回码          | 0:成功，1:失败                                |
| message | string    |   是    |  说明            |                                             |
| data | object |   是    |  说明            |      事件详情                                       |

- **返回示例**

``` javascript
{
    "code": 0,
    "message": "ok",
    "data": {
        "event_id": 48,
        "event_name": "测试API8000",
        "event_content": "<p>测试标签<strong>富贵树规范</strong><img src=\"http://b.hiphotos.baidu.com/image/h%3D300/sign=ad628627aacc7cd9e52d32d909032104/32fa828ba61ea8d3fcd2e9ce9e0a304e241f5803.jpg\" title=\"1570697662419637.png\" alt=\"图片.png\" onclick=\"window.open(this.src,'_blank')\"/>",
        "event_date": "2019-10-16",
        "event_from_system": "qnn",
        "event_author": "李11四",
        "event_level": "success",
        "event_time": "17:15:00",
        "event_tags": "a,b,c"
    }
}
```
