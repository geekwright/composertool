<?php

use Geekwright\ComposerTool\ComposerUtility;

class ComposerUtilityTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ComposerUtility
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new ComposerUtility();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testGetComposerJsonPath()
    {
        $path = $this->object->getComposerJsonPath();
        $this->assertFileExists($path . '/composer.json');
    }

    public function testComposerExecute()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    public function testGetLastOutput()
    {
        $output = $this->object->getLastOutput();
        $this->assertIsArray($output);
        $this->assertEmpty($output);
    }

    public function testGetLastError()
    {
        $output = $this->object->getLastError();
        $this->assertIsArray($output);
        $this->assertEmpty($output);
    }

    public function testSetComposerExe()
    {
        $object = new class() extends ComposerUtility {
            public function getExe()
            {
                return $this->exe;
            }
        };
        $myexe = 'mycomposercmd';
        $object->setComposerExe($myexe);
        $this->assertEquals($myexe, $object->getExe());
    }
}
