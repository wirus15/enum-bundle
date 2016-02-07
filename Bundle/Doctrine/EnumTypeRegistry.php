<?php

namespace Enum\Bundle\Doctrine;

use Doctrine\DBAL\Types\Type;
use Enum\Enum;
use Enum\EnumException;

class EnumTypeRegistry
{
    /**
     * @var EnumTypeGenerator
     */
    private $typeGenerator;

    /**
     * TypeRegistry constructor.
     * @param EnumTypeGenerator $typeGenerator
     */
    public function __construct(EnumTypeGenerator $typeGenerator)
    {
        $this->typeGenerator = $typeGenerator;
    }

    /**
     * @param string $name
     * @param string $enumClass
     * @throws EnumException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function addType($name, $enumClass)
    {
        if ($this->hasType($name)) {
            return;
        }

        if (!preg_match('/^[A-Za-z0-9_]+$/', $name)) {
            throw new EnumException('Enum type name contains invalid characters. Only letters, numbers and underscores are allowed.');
        }

        if (!is_subclass_of($enumClass, Enum::class)) {
            throw new \InvalidArgumentException("$enumClass is not a valid enum class.");
        }

        $typeClass = $this->typeGenerator->generate($name, $enumClass);

        Type::addType($name, $typeClass);
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