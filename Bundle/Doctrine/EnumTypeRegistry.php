<?php

namespace Enum\Bundle\Doctrine;

use Doctrine\DBAL\Types\Type;
use Enum\Bundle\Doctrine\Generator\EnumTypeGenerator;
use Enum\Enum;
use Enum\EnumException;

class EnumTypeRegistry
{
    /**
     * @var EnumTypeGenerator
     */
    private $typeGenerator;

    /**
     * @var EnumTypeStorage
     */
    private $typeStorage;

    /**
     * TypeRegistry constructor.
     * @param EnumTypeGenerator $typeGenerator
     * @param EnumTypeStorage $typeStorage
     */
    public function __construct(EnumTypeGenerator $typeGenerator, EnumTypeStorage $typeStorage)
    {
        $this->typeGenerator = $typeGenerator;
        $this->typeStorage = $typeStorage;

        $autoloader = new EnumTypeAutoloader($this->typeStorage);
        $autoloader->register();
    }

    /**
     * @param string $typeName
     * @param string $enumClass
     * @throws EnumException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function addType($typeName, $enumClass)
    {
        if ($this->hasType($typeName)) {
            return;
        }

        if (!preg_match('/^[A-Za-z0-9_]+$/', $typeName)) {
            throw new EnumException('Enum type name contains invalid characters. Only letters, numbers and underscores are allowed.');
        }

        if (!is_subclass_of($enumClass, Enum::class)) {
            throw new \InvalidArgumentException("$enumClass is not a valid enum class.");
        }

        $typeClass = $this->typeGenerator->getTypeClassName($enumClass);

        if (!$this->typeStorage->exists($typeClass)) {
            $result = $this->typeGenerator->generate($typeName, $enumClass);
            $this->typeStorage->save($result->getClassName(), $result->getContent());
        }

        Type::addType($typeName, $typeClass);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasType($name)
    {
        return Type::hasType($name);
    }
}