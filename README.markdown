1. MANUAL

Integration with PHPUnit

<?php

require_once 'vfsStreamHelper/Wrapper.php';

class MyClassTest extends PHPUnit_Framework_TestCase
{
    /**
     * - It will skip the test if vfsStream is not installed
     * - It will register vfsStream in default root directory called 'root'
     * - creates 'tmp' directory in root directory
     */
    public function testCreateDirectoryInDefaultRootDirectory()
    {
        $vfsStreamWrapper = new vfsStreamHelper_Wrapper($this);
        $vfsStreamWrapper->createDirectory("tmp");

        $this->assertFileExists(vfsStream::url('root/tmp'));
    }

    /**
     * - It will skip the test if vfsStream is not installed
     * - It will register vfsStream in default root directory called 'root'
     * - creates empty 'myFile.txt' file in root directory
     */
    public function testCreateEmptyFileInDefaultRootDirectory()
    {
        $vfsStreamWrapper = new vfsStreamHelper_Wrapper($this);
        $vfsStreamWrapper->createFile("myFile.txt");

        $this->assertFileExists(vfsStream::url('root/myFile.txt'));
    }

    /**
     * - It will skip the test if vfsStream is not installed
     * - It will register vfsStream in root directory called 'myDir'
     * - creates 'home' directory in root directory
     */
    public function testCreateDirectoryInCustomRootDirectory()
    {
        $vfsStreamWrapper = new vfsStreamHelper_Wrapper($this, 'myDir');
        $vfsStreamWrapper->createDirectory("home");

        $this->assertFileExists(vfsStream::url('myDir/home'));
    }

    /**
     * - It will skip the test if vfsStream is not installed
     * - It will register vfsStream in default root directory called 'root'
     * - creates directory in different possible ways
     */
    public function testDifferentWaysOfCreatingDirectories()
    {
        $vfsStreamWrapper = new vfsStreamHelper_Wrapper($this);

        // create a single directory
        $vfsStreamWrapper->createDirectory("tmp");

        $this->assertFileExists(vfsStream::url('root/tmp'));

        // create nested directories
        $vfsStreamWrapper->createDirectory("home/proofek/downloads");

        $this->assertFileExists(vfsStream::url('root/home'));
        $this->assertFileExists(vfsStream::url('root/home/proofek'));
        $this->assertFileExists(vfsStream::url('root/home/proofek/downloads'));

        // create a directory using vfsStreamHelper_Directory in default root
        $vfsStreamWrapper->createDirectory(new vfsStreamHelper_Directory('etc'));

        $this->assertFileExists(vfsStream::url('root/etc'));

        // create a directory using vfsStreamHelper_Directory in a subdirectory
        $vfsStreamWrapper->createDirectory(
            new vfsStreamHelper_Directory('init.d', 'etc')
        );

        $this->assertFileExists(vfsStream::url('root/etc/init.d'));

        // create multiple directories
        $vfsStreamWrapper->createDirectory(
            array(
                new vfsStreamHelper_Directory('user1', 'home'),
                new vfsStreamHelper_Directory('user2', 'home'),
                new vfsStreamHelper_Directory('usr'),
            )
        );

        $this->assertFileExists(vfsStream::url('root/home/user1'));
        $this->assertFileExists(vfsStream::url('root/home/user2'));
        $this->assertFileExists(vfsStream::url('root/usr'));
    }

    /**
     * - It will skip the test if vfsStream is not installed
     * - It will register vfsStream in default root directory called 'root'
     * - creates files in different possible ways
     */
    public function testDifferentWaysOfCreatingFiles()
    {
        $vfsStreamWrapper = new vfsStreamHelper_Wrapper($this);

        // create a single empty file in default root directory
        $vfsStreamWrapper->createFile("myFile.txt");

        $this->assertFileExists(vfsStream::url('root/myFile.txt'));
        $this->assertEquals('', file_get_content(vfsStream::url('root/myFile.txt')));

        // create a single empty file using vfsStreamHelper_File in default root
        $vfsStreamWrapper->createFile(
            new vfsStreamHelper_File('anotherFile.txt')
        );
        $this->assertFileExists(vfsStream::url('root/anotherFile.txt'));
        $this->assertEquals('', file_get_content(vfsStream::url('root/anotherFile.txt')));

        // create a single file with contents using vfsStreamHelper_File in default root
        $fileContent = "First line in the file\nSecond line in the file\n";
        $vfsStreamWrapper->createFile(
            new vfsStreamHelper_File('thirdFile.txt', $fileContent)
        );
        $this->assertFileExists(vfsStream::url('root/thirdFile.txt'));
        $this->assertEquals($fileContent, file_get_contents(vfsStream::url('root/thirdFile.txt')));

        // create a single file with contents using vfsStreamHelper_File in
        // a subdirectory
        $fileContent = "First line in the file\nSecond line in the file\n";
        $vfsStreamWrapper->createDirectory("tmp");
        $vfsStreamWrapper->createFile(
            new vfsStreamHelper_File('file.txt', $fileContent, 'tmp')
        );
        $this->assertFileExists(vfsStream::url('root/tmp/file.txt'));
        $this->assertEquals(
            $fileContent,
            file_get_contents(vfsStream::url('root/tmp/file.txt'))
        );

        // create multiple files
        $vfsStreamWrapper->createDirectory("etc");
        $vfsStreamWrapper->createFile(
            array(
                new vfsStreamHelper_File('file1.txt', 'some content', 'etc'),
                new vfsStreamHelper_File('file2.txt', null, 'etc'),
                new vfsStreamHelper_File('file3.txt'),
            )
        );

        $this->assertFileExists(vfsStream::url('root/etc/file1.txt'));
        $this->assertEquals(
            'some content',
            file_get_contents(vfsStream::url('root/etc/file1.txt'))
        );
        $this->assertFileExists(vfsStream::url('root/etc/file2.txt'));
        $this->assertEquals(
            '',
            file_get_contents(vfsStream::url('root/etc/file2.txt'))
        );
        $this->assertFileExists(vfsStream::url('root/file3.txt'));
        $this->assertEquals(
            '',
            file_get_contents(vfsStream::url('root/file3.txt'))
        );
    }
}
?>

2. CHANGELOG

vfsStreamHelper v0.1
======================

* Integration with PHPUnit - marks a test as skipped if vfsStream is not available/not installed
* more flexible wrappers to control vfsDirectory and vfsFile creation

3. TODO:
* support for permissions
* support for modes
