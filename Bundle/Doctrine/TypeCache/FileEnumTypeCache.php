<?php

namespace Enum\Bundle\Doctrine\TypeCache;

class FileEnumTypeCache implements EnumTypeCache
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * FileEnumTypeCache constructor.
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return boolean
     */
    public function exists()
    {
        return file_exists($this->filePath);
    }

    /**
     * @return void
     */
    public function load()
    {
        require_once $this->filePath;
    }

    /**
     * @param string $className
     * @param string $classBody
     * @return void
     */
    public function save($className, $classBody)
    {
        file_put_contents($this->filePath, $classBody, FILE_APPEND);
    }

    /**
     * @return void
     */
    public function clear()
    {
        file_put_contents($this->filePath, '');
    }
}