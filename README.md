# NewdayCms - 哩呵后台管理系统

![](https://img.shields.io/github/stars/newday-me/think-cms.svg) ![](https://img.shields.io/github/forks/newday-me/think-cms.svg) ![](https://img.shields.io/github/tag/newday-me/think-cms.svg) ![](https://img.shields.io/github/release/newday-me/think-cms.svg) ![](https://img.shields.io/github/issues/newday-me/think-cms.svg)

**演示地址：[http://cms.newday.me](http://cms.newday.me "http://cms.newday.me")。**

## 一、CMS构建
* **ThinkPHP 5.0**

CMS的核心使用了ThinkPHPCMS的核心使用了ThinkPHP 5.0强力驱动。这个版本是一个颠覆和重构版本，采用全新的架构思想，是快速开发系统的一种最佳选择。

*  **AmazeUI**

CMS的UI使用了国产的一个轻量级HTML5前端框架，个人感觉界面风格比Bootstrap看起来更舒服，组件也不少。

*  **其他插件**

CMS还使用了alertifyjs、ace editor、wang editor、rquirejs等插件。

## 二、功能设计

CMS的功能设计主要参考了OneThink，并实现了以下模块：

* 用户管理
* 权限管理
* 配置管理
* 菜单管理
* 操作日志
* 附件管理
* 数据库备份
* 队列管理(待开发)

## 三、表单组件化

只需要简单地配置，就可以快速生成表单项。
已支持生成的表单项有：文本、文本域、标签、时间、颜色、图片、文件、单选、多选、下拉框、编辑器、JSON。

* **文本**

```php
{:block('text', ['title' => '用户昵称', 'name' => 'user_nick', 'value' => ''])}
```

* **标签**

```php
{:block('tag', ['title' => '文章标签', 'name' => 'article_tags', 'value' => ''])}
```

* **图片**

```php
{:block('image', ['title' => '文章封面', 'name' => 'article_cover', 'value' => ''])}
```

* **下拉选择**

```php
{:block('select', ['title' => '文章分类', 'name' => 'article_cate', 'value' => '', 'list' => $cate_list])}
```

* **编辑器**

```php
{:block('editor', ['title' => '文章内容', 'name' => 'article_content', 'value' => ''])}
```

## 四、演示截图

* **控制台**

![](https://raw.githubusercontent.com/newday-me/think-cms/master/public/images/001.png)

* **网站设置**

![](https://raw.githubusercontent.com/newday-me/think-cms/master/public/images/002.png)

* **配置管理**

![](https://raw.githubusercontent.com/newday-me/think-cms/master/public/images/003.png)

* **权限管理**

![](https://raw.githubusercontent.com/newday-me/think-cms/master/public/images/004.png)

* **数据库备份**

![](https://raw.githubusercontent.com/newday-me/think-cms/master/public/images/005.png)

* **附件管理**

![](https://raw.githubusercontent.com/newday-me/think-cms/master/public/images/006.png)

## 五、写在后面

因为水平有限和个人的编写习惯，如果哪里写得不好或者有错误的地方，欢迎大家Fork代码然后Pull Request。
CMS还在起始阶段，如果你对CMS有什么建议或者想法，也欢迎通过邮件联系我（newday_me@163.com）。
谢谢！！！