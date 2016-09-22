<?php

namespace Enum\Bundle\Doctrine;

class GenerationResult
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private $content;

    /**
     * GenerationResult constructor.
     * @param string $className
     * @param string $content
     */
    public function __construct($className, $content)
    {
        $this->className = $className;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}