<?php

namespace Enum\Bundle\Doctrine\TypeStorage;

use Enum\Bundle\Doctrine\EnumTypeStorage;

class FileEnumTypeStorage implements EnumTypeStorage
{
    /**
     * @var string
     */
    private $path;

    /**
     * FileEnumTypeStorage constructor.
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

    /**
     * @param string $className
     * @return bool
     */
    public function exists($className)
    {
        return file_exists($this->getTypeFilePath($className));
    }

    /**
     * @param string $className
     * @param string $classContent
     */
    public function save($className, $classContent)
    {
        file_put_contents($this->getTypeFilePath($className), '<?php ' . $classContent);
    }

    /**
     * @param string $className
     * @return bool
     */
    public function load($className)
    {
        if ($this->exists($className)) {
            require_once($this->getTypeFilePath($className));

            return true;
        }

        return false;
    }

    /**
     * @param string $className
     * @return string
     */
    private function getTypeFilePath($className)
    {
        return $this->path . '/' . base64_encode($className) . '.php';
    }
}