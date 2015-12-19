<?php

namespace Rmtram\TextDatabase\EntityManager;

use Rmtram\TextDatabase\Reader\Reader;

final class Memory
{
    /**
     * @var self
     */
    private static $instances = [];

    /**
     * @var string
     */
    private $table;

    /**
     * @var array
     */
    private $loaded = false;

    /**
     * @var array
     */
    private $items = [];

    /**
     * @param $table
     * @return self
     */
    public static function make($table)
    {
        if (empty($table)) {
            throw new \InvalidArgumentException('bad!! empty table.');
        }
        if (!isset(self::$instances[$table])) {
            self::$instances[$table] = new self($table);
        }
        return self::$instances[$table];
    }

    /**
     * @param $table
     */
    private function __construct($table)
    {
        $this->table = $table;
        $this->initializeStorage();
    }

    public function get()
    {
        return $this->items;
    }

    public function set($items)
    {
        return $this->items = $items;
    }

    private function reset()
    {
        $this->loaded = false;
        $this->initializeStorage();
    }

    /**
     * setup load storage.
     */
    private function initializeStorage()
    {
        if (false === $this->loaded) {
            $reader = new Reader();
            $items = $reader
                ->throws(false)
                ->getStorage($this->table);
            if (false !== $items) {
                $this->items = $items;
            }
            else {
                $this->items = [];
            }
        };
    }
}