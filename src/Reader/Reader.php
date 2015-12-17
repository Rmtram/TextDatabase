<?php

namespace Rmtram\TextDatabase\Reader;

use Cake\Filesystem\File;
use Rmtram\TextDatabase\Connection;

/**
 * Class Reader
 * @package Rmtram\TextDatabase\Reader
 */
class Reader
{
    /**
     * @var bool
     */
    private $throws = true;

    /**
     * @param $table
     * @return mixed|null
     */
    public function getStorage($table)
    {
        return $this->get(
            $this->getStoragePath($table));
    }

    /**
     * @param $table
     * @return mixed|null
     */
    public function getSchema($table)
    {
        return $this->get(
            $this->getSchemaPath($table));
    }

    /**
     * @param $table
     * @return bool
     */
    public function existsStorage($table)
    {
        return is_readable($this->getStoragePath($table));
    }

    /**
     * @param $table
     * @return bool
     */
    public function existsSchema($table)
    {
        return is_readable($this->getSchemaPath($table));
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function throws($bool)
    {
        if (is_bool($bool)) {
            $this->throws = $bool;
        }
        return $this;
    }

    /**
     * @param $path
     * @return mixed
     */
    protected function get($path)
    {
        $file = new File($path);
        if (!$file->readable()) {
            if (true === $this->throws) {
                throw new \RuntimeException('not readable ' . $path);
            }
            return false;
        }
        $serialize = $file->read();
        $ret = unserialize($serialize);
        if (empty($ret)) {
            if (true === $this->throws) {
                throw new \UnexpectedValueException('empty data');
            }
            return false;
        }
        return $ret;
    }

    /**
     * @param $table
     * @return string
     */
    protected function getStoragePath($table)
    {
        return sprintf('%s%s.%s',
            Connection::getPath(),
            $table,
            Connection::getStorageExtension());
    }

    /**
     * @param $table
     * @return string
     */
    protected function getSchemaPath($table)
    {
        return sprintf('%s%s.%s',
            Connection::getPath(),
            $table,
            Connection::getSchemaExtension());
    }
}