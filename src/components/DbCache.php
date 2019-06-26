<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-12-12
 * Version      :   1.0
 */

namespace Components;

use Abstracts\Store;
use Helper\Exception;
use Helper\Format;

class DbCache extends Store
{
    /* @var string 命名空间 */
    public $namespace = 'pf';
    /* @var string key的前缀 */
    public $prefix = 'pf_';

    /* @var string 使用数据库的配置文件 */
    public $dbConfigFile = 'database';
    /* @var string 使用数据库的配置文件中的类型 */
    public $dbConfigGroup = 'master';

    /* @var string 数据库缓存表名 */
    public $tableName = '{{cache}}';

    private $_db;

    /**
     * 返回当前模型的数据库连接；
     * 如果使用非默认的DB连接，该方法应该被重写
     * @return Db
     * @throws \Exception
     */
    public function getConnection()
    {
        if (null === $this->_db) {
            $this->_db = Db::getInstance([
                'c-file' => $this->dbConfigFile,
                'c-group' => $this->dbConfigGroup,
            ]);
        }
        return $this->_db;
    }

    /**
     * 属性赋值后执行函数
     * @throws \Exception
     */
    public function init()
    {
        if (!$this->getConnection() instanceof Db) {
            throw new Exception('db-cache 定义的数据库驱动有误，必须继承"\Components\Db"');
        }
    }

    /**
     * 获取最终的id
     * @param mixed $key
     * @return string
     */
    protected function buildKey($key)
    {
        return md5($this->prefix . (is_string($key) ? $key : json_encode($key)));
    }

    /**
     * @param $id
     * @return array
     * @throws \Exception
     */
    protected function getById($id)
    {
        return $this->getConnection()
            ->getFindBuilder()
            ->setTable($this->tableName)
            ->setWhere('`id`=:id', [
                ':id' => $id,
            ])
            ->queryRow();
    }

    /**
     * 获取 id 的信息
     * @param mixed $id
     * @return mixed
     * @throws \Exception
     */
    protected function getValue($id)
    {
        if (false === ($record = $this->getById($id))) {
            return null;
        }
        if (strtotime($record['expire_at']) < time()) {
            return false;
        }
        return $record['data'];
    }

    /**
     * 保存 id 的信息
     * @param string $id
     * @param string $value
     * @param int $ttl
     * @return bool
     * @throws \Exception
     */
    protected function setValue($id, $value, $ttl)
    {
        if (false === $this->getById($id)) {
            // 没有设置过
            $num = $this->getConnection()
                ->getInsertBuilder()
                ->setTable($this->tableName)
                ->setColumns([
                    'id' => $id,
                    'namespace' => $this->namespace,
                    'data' => $value,
                    'expire_at' => Format::datetime(time() + $ttl),
                ])
                ->execute();
        } else {
            $num = $this->getConnection()
                ->getUpdateBuilder()
                ->setTable($this->tableName)
                ->setColumns([
                    'namespace' => $this->namespace,
                    'data' => $value,
                    'expire_at' => Format::datetime(time() + $ttl),
                ])
                ->setWhere('`id`=:id', [
                    'id' => $id,
                ])
                ->execute();
        }
        return is_integer($num);
    }

    /**
     * 删除 id 的信息
     * @param string $id
     * @return bool
     * @throws \Exception
     */
    protected function deleteValue($id)
    {
        $num = $this->getConnection()
            ->getDeleteBuilder()
            ->setTable($this->tableName)
            ->setWhere('`id`=:id', [
                ':id' => $id,
            ])
            ->execute();
        return is_integer($num);
    }

    /**
     * 清理当前命名空间下的存取信息
     * @return bool
     * @throws \Exception
     */
    protected function clearValues()
    {
        $num = $this->getConnection()
            ->getDeleteBuilder()
            ->setTable($this->tableName)
            ->setWhere('`namespace`=:namespace', [
                ':namespace' => $this->namespace,
            ])
            ->execute();
        return is_integer($num);
    }
}