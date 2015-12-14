<?php

namespace Rmtram\TextDatabase\Writer;

use Rmtram\TextDatabase\Connection;

class StorageWriter extends AbstractWriter
{

    /**
     * @var string
     */
    private $table;

    /**
     * @var array
     */
    private $data;

    /**
     * @param $table
     * @param array $data
     */
    public function __construct($table, array $data)
    {
        $this->table = $table;
        $this->data = $data;
    }

    /**
     * Get write path
     * @return string
     */
    public function getPath()
    {
        return sprintf('%s%s.%s',
            Connection::getPath(),
            $this->table,
            Connection::getStorageExtension());
    }

    /**
     * export data.
     * @return string
     */
    protected function export()
    {
        return serialize($this->data);
    }

}