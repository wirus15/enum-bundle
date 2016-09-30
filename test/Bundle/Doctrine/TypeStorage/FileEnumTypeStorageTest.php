<?php

namespace test\Enum\Bundle\Doctrine\TypeStorage;

use Enum\Bundle\Doctrine\TypeStorage\FileEnumTypeStorage;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use test\Enum\Fixtures\FooBarType;

class FileEnumTypeStorageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;

    /**
     * @var FileEnumTypeStorage
     */
    private $sut;

    protected function setUp()
    {
        $this->root = vfsStream::setup('enum');
        $this->sut = new FileEnumTypeStorage($this->root->url());
    }

    public function testItSavesTypeClassFiles()
    {
        $this->sut->save(FooBarType::class, 'foobar_type_class_content');

        $filename = base64_encode(FooBarType::class).'.php';

        $this->assertTrue($this->root->hasChild($filename));

        $child = $this->root->getChild($filename);
        $this->assertEquals(
            '<?php foobar_type_class_content',
            file_get_contents($child->url())
        );
    }

    public function testItReturnsFalseIfClassFileDoesNotExist()
    {
        $this->assertFalse($this->sut->load('NonExistingClass'));
    }

    public function testItLoadsClassFromFile()
    {
        $this->assertFalse(class_exists('TotallyNewClass', false));

        $this->sut->save('TotallyNewClass', 'final class TotallyNewClass {}');
        $this->sut->load('TotallyNewClass');

        $this->assertTrue(class_exists('TotallyNewClass', true));
    }
}
