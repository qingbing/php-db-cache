# php-db-cache
## 描述
DbCache：database缓存的相关解析和操作，可以单独使用

## 注意事项
 - 组件继承自"qingbing/php-abstracts-store"
 - 缓存的参数配置参考 qingbing/php-config 组件
 - 将缓存的"isWorking"设置成false，则缓存获取始终为"$default"
 - 缓存的键名支持 字符串、数字、数组等可序列化的变量
 - 缓存的键值支持 字符串、数字、数组等可序列化的变量
 - 缓存支持批量的设置、获取、删除操作

## 使用方法
```php
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
```

## ====== 异常代码集合 ======
异常代码格式：1019 - XXX - XX （组件编号 - 文件编号 - 代码内异常）
```
 - 101900101 : db-cache 定义的数据库驱动有误，必须继承"\Components\Db"
```