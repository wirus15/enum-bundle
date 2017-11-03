<?php

declare(strict_types=1);

namespace test\Enum\Bundle\DependencyInjection;

use Enum\Bundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;
use test\Enum\Fixtures\FooBar;
use test\Enum\Fixtures\OneTwo;

class ConfigurationTest extends TestCase
{
    public function testConfiguration()
    {
        $configFile = __DIR__.'/../../Fixtures/config.yml';
        $config = Yaml::parse(file_get_contents($configFile));
        $configTree = new Configuration();
        $processor = new Processor();
        $processedConfig = $processor->processConfiguration($configTree, $config);

        $this->assertEquals([
            'doctrine' => [
                'type_storage' => 'memory',
            ],
            'enums' => [
                'foo_bar' => [
                    'class' => FooBar::class,
                    'doctrine_type' => null,
                    'translation_prefix' => null,
                ],
                'one_two' => [
                    'class' => OneTwo::class,
                    'doctrine_type' => 'one_two_type',
                    'translation_prefix' => 'one_two_prefix',
                ],
            ],
        ], $processedConfig);
    }
}
