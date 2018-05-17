Yii2 高级模板，配置的后台样式
===============================

### 简介

系统基于yii2.0框架开发，保留框架原有的特性，适合作为二次开发的基础系统，也可以直接拿来作为后台系统。管理系统的模块在不断完善中，现有的功能包含，完整的RBAC权限管理~

### 功能

1. 基础功能：登录，登出，密码修改等常见的功能

2. 菜单配置：可视化配置菜单，可以根据配置用户的权限显示隐藏菜单

3. 权限机制：角色、权限增删改查，以及给用户赋予角色权限

4. 规则机制：除了权限角色之外有规则机制，即可以给对应的权限配置规则

5. 二次开发：完全可以基于该系统做二次开发，开发一套适合自己的后台管理系统，节约权限控制以及部分基础功能开发的时间成本，后台系统开发的不二之选

6. 持续更新：新的功能模块会持续更新，请关注

###安装

#### 1. 安装/echoooxx/Yii2模板
---

```
php composer.phar global require "fxp/composer-asset-plugin:~1.1.1"
php composer.phar create-project --prefer-dist echoooxx/yii2-app-advanced advanced
```

#### 2. 安装Composer
---

```
composer install
```

#### 3. 导入表结构(migration)
---

需要顺序执行

- 导入rbac migration

```
yii migrate --migrationPath=@yii/rbac/migrations
```
- 导入admin migration

```
yii migrate --migrationPath=@echoooxx/admin/migrations
```

### 后台登录密码
---

```
User: admin
Password: (ABCD1234)
```

### 页面中指定目录高亮
---
```
<?php $this->beginBlock('hightlightnav'); ?>
    $(function() {
    /* 子导航高亮 */
    hightlightnav('<controllerName>/<actionName>');
    });
<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['hightlightnav'], \yii\web\View::POS_END); ?>
```

###相关配置
---
```
在param.php中配置
'homeUrl' => '/',
```

DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
tests                    contains various tests for the advanced application
    codeception/         contains tests developed with Codeception PHP Testing Framework
```
