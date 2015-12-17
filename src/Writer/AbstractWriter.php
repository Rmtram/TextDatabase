<?php

namespace Rmtram\TextDatabase\Writer;

use Cake\Filesystem\File;
use Rmtram\TextDatabase\Exceptions\ExistsFileException;

abstract class AbstractWriter
{

    /**
     * export data.
     * @return string
     */
    abstract protected function export();

    /**
     * Get write path
     * @return string
     */
    abstract public function getPath();

    /**
     * write serialize data.
     * @param bool $overwrite
     * @return bool
     */
    public function write($overwrite = false)
    {
        $path = $this->getPath();
        if (true === $this->writable() && false === $overwrite) {
            throw new ExistsFileException('exists ' . $path);
        }
        $file = new File($path);
        if (!$file->write($this->export())) {
            throw new \RuntimeException('save failed ' . $path);
        }
        return true;
    }

    /**
     * is writable in target path
     * @return bool
     */
    public function writable()
    {
        return is_writable($this->getPath());
    }

}