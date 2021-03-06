# Rongcloud 融云IM
```
融云是国内首家专业的即时通讯云服务提供商，专注为互联网、移动互联网开发者提供免费的即时通讯基础能力和云端服务。通过融云平台，开发者不必搭建服务端硬件环境，就可以将即时通讯、实时网络能力快速集成至应用中。
   
针对开发者所需的不同场景，融云平台提供了一系列产品、技术解决方案，包括：客户端 IM 界面组件、客户端 IM 基础通讯能力库、Web IM 基础通讯能力库、服务端 REST API 等。支持单聊、群聊、讨论组、聊天室、客服即时通讯场景；
   
消息类型上支持文字、表情、图片、语音、视频、地理位置、红包、实时音视频、通知消息等消息类型。如果这些类型都不能满足，您还可以通过自定义消息来实现个性化需求。
```
## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist gulltour/rongcloud "*"
```

or add

```
"gulltour/rongcloud": "*"
```

to the require section of your `composer.json` file.


## Usage

Update config file *config/web.php* or *common/main.php*:

```php
return [
    ...
    'components' => [
        'im'=>[
                'class'=>'gulltour\rongcloud\rongCloud',
                'appKey' => 'RongCloud_APP_KEY',
                'secretKey' => 'RongCloud_SECRET_KEY',
            ],
    ],
    ...
];
```

```php
Yii::$app->im->groupCreate($userids, $groupId, $groupName);
```
