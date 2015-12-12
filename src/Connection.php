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
    private static $storageExtension = 'rst';

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

    public static function getSchemaExtension()
    {
        return static::$schemaExtension;
    }

    public static function getStorageExtension()
    {
        return static::$storageExtension;
    }

}