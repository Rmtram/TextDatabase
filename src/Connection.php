<?php

namespace Rmtram\TextDatabase;

final class Connection
{

    /**
     * @var string
     */
    private static $schemaExtension = 'rtb';

    /**
     * @var string
     */
    private static $storageExtension = 'rtd';

    /**
     * @var string
     */
    private static $path;

    /**
     * @return string
     */
    public static function getPath()
    {
        return static::$path;
    }

    /**
     * @param string $path
     */
    public static function setPath($path)
    {
        static::$path = $path;
    }

    /**
     * @return string
     */
    public static function getSchemaExtension()
    {
        return static::$schemaExtension;
    }

    /**
     * @return string
     */
    public static function getStorageExtension()
    {
        return static::$storageExtension;
    }

}