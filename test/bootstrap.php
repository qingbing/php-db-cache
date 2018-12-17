<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-12-12
 * Version      :   1.0
 */
require("../vendor/autoload.php");

// 目录分隔符
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
// APP应用的客户端编码
defined('APP_CHARSET') or define('APP_CHARSET', 'utf-8');
// php是否调试模式
defined('APP_DEBUG') or define('APP_DEBUG', false);
// 定义环境变量
defined("PHP_ENV") or define("PHP_ENV", "dev");
// 定义配置存放目录
defined("CONFIG_PATH") or define("CONFIG_PATH", dirname(realpath(".")) . "/conf");
// 定义配置缓存的存放目录
defined("RUNTIME_PATH") or define("RUNTIME_PATH", dirname(realpath(".")) . "/runtime");


// 获取缓存实例
$cache = \Components\DbCache::getInstance('db-cache');

// ====== 普通用法 ======
$key = "name";
// 设置缓存
$status = $cache->set($key, "ss");
var_dump($status);
// 获取缓存
$name = $cache->get($key);
var_dump($name);
// 删除缓存
$status = $cache->delete($key);
var_dump($status);
// 判断换成是否存在
$status = $cache->has($key);
var_dump($status);


// ====== 批量用法 ======
// 批量设置缓存
$status = $cache->setMultiple([
    "name" => 'ss',
    "author" => [
        'qingbing',
        '10000',
    ],
]);
var_dump($status);
// 批量获取缓存
$values = $cache->getMultiple(["name", "author"]);
var_dump($values);
// 批量删除缓存
$status = $cache->deleteMultiple(["name", "author"]);
var_dump($status);


// ====== 键、值随意化 ======
$key = ["sex", "name"];
// 设置缓存
$status = $cache->set($key, ["女", ["xxx"]]);
var_dump($status);
// 获取缓存
$status = $cache->get($key);
var_dump($status);
// 删除缓存
$status = $cache->delete($key);
var_dump($status);


// ====== 清空缓存 ======
// 清空命名空间换成
$status = $cache->clear();
var_dump($status);
