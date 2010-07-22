<?php
/**
 * vfsStreamHelper
 *
 * Copyright (c) 2010, Sebastian Marek <proofek@gmail.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Testing
 * @package    vfsStreamHelper
 * @author     Sebastian Marek <proofek@gmail.com>
 * @copyright  2010 Sebastian Marek <proofek@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @since      File available since Release 0.1
 */

require_once 'PHPUnit/Framework/TestCase.php';

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vfsStreamHelper/Wrapper.php';
/**
 *
 *
 * @package    vfsStreamHelper
 * @author     Sebastian Marek <proofek@gmail.com>
 * @copyright  2010 Sebastian Marek <proofek@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @since      Class available since Release 0.1
 */
class vfsStreamHelper_WrapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers vfsStreamHelper_Wrapper::__construct
     */
    public function testConstructorRegistersStreamWithDefaultMountPoint()
    {
        $wrapper = new vfsStreamHelper_Wrapper($this);
        $root = vfsStreamWrapper::getRoot();

        $this->assertContains(vfsStream::SCHEME, stream_get_wrappers());
        $this->assertType('vfsStreamDirectory', $root);
        $this->assertEquals('root', $root->getName());
        $this->assertEquals(vfsStreamContent::TYPE_DIR, $root->getType());
    }

    /**
     * @covers vfsStreamHelper_Wrapper::__construct
     */
    public function testConstructorRegistersStreamWithCustomMountPoint()
    {
        $mountPoint = 'tmp';
        $wrapper = new vfsStreamHelper_Wrapper($this, $mountPoint);
        $root = vfsStreamWrapper::getRoot();

        $this->assertContains(vfsStream::SCHEME, stream_get_wrappers());
        $this->assertType('vfsStreamDirectory', $root);
        $this->assertEquals($mountPoint, $root->getName());
        $this->assertEquals(vfsStreamContent::TYPE_DIR, $root->getType());
    }

    /**
     * @covers vfsStreamHelper_Wrapper::createDirectory
     * @covers vfsStreamHelper_Wrapper::createDirectoryFromString
     */
    public function testCreateDirectoryFromString()
    {
        $newDir = 'testDir';
        $wrapper = new vfsStreamHelper_Wrapper($this);

        $root = vfsStreamWrapper::getRoot();
        $vfsDir = $wrapper->createDirectory($newDir);

        $this->assertEquals($newDir, $vfsDir->getName());
        $this->assertEquals(vfsStreamContent::TYPE_DIR, $vfsDir->getType());

        $rootChildren = $root->getChildren();
        $this->assertEquals(1, count($rootChildren));
        $this->assertEquals('root', $root->getName());
        $this->assertEquals($newDir, $rootChildren[0]->getName());
        $this->assertEquals(vfsStreamContent::TYPE_DIR, $rootChildren[0]->getType());
    }

    /**
     * @covers vfsStreamHelper_Wrapper::createDirectory
     * @covers vfsStreamHelper_Wrapper::createDirectoryFromObject
     */
    public function testCreateDirectoryFromObject()
    {
        $wrapper = new vfsStreamHelper_Wrapper($this);

        $newDir = new vfsStreamHelper_Directory('testNextDir');

        $root = vfsStreamWrapper::getRoot();
        $vfsDir = $wrapper->createDirectory($newDir);

        $this->assertEquals($newDir->getName(), $vfsDir->getName());
        $this->assertEquals(vfsStreamContent::TYPE_DIR, $vfsDir->getType());

        $rootChildren = $root->getChildren();
        $this->assertEquals(1, count($rootChildren));
        $this->assertEquals('root', $root->getName());
        $this->assertEquals($newDir->getName(), $rootChildren[0]->getName());
        $this->assertEquals(vfsStreamContent::TYPE_DIR, $rootChildren[0]->getType());
    }

    /**
     * @covers vfsStreamHelper_Wrapper::createDirectory
     * @covers vfsStreamHelper_Wrapper::createDirectories
     */
    public function testCreateMultipleDirectories()
    {
        $wrapper = new vfsStreamHelper_Wrapper($this);

        $directories = array(
            new vfsStreamHelper_Directory('testFirstDir'),
            new vfsStreamHelper_Directory('testSecondDir'),
            new vfsStreamHelper_Directory('testThirdDir'),
        );

        $root = vfsStreamWrapper::getRoot();
        $wrapper->createDirectory($directories);

        $rootChildren = $root->getChildren();
        $this->assertEquals(3, count($rootChildren));
        $this->assertEquals('root', $root->getName());
        foreach ($directories as $index => $entry) {
            $this->assertEquals($entry->getName(), $rootChildren[$index]->getName());
            $this->assertEquals(vfsStreamContent::TYPE_DIR, $rootChildren[$index]->getType());
        }
    }

    /**
     * @covers vfsStreamHelper_Wrapper::createFile
     * @covers vfsStreamHelper_Wrapper::createFileFromString
     */
    public function testCreateFileFromString()
    {
        $newFile = 'testFile.txt';
        $wrapper = new vfsStreamHelper_Wrapper($this);

        $root = vfsStreamWrapper::getRoot();
        $vfsFile = $wrapper->createFile($newFile);

        $this->assertEquals($newFile, $vfsFile->getName());
        $this->assertEquals(vfsStreamContent::TYPE_FILE, $vfsFile->getType());
        $this->assertEquals('', $vfsFile->getContent());

        $rootChildren = $root->getChildren();
        $this->assertEquals(1, count($rootChildren));
        $this->assertEquals('root', $root->getName());
        $this->assertEquals($newFile, $rootChildren[0]->getName());
        $this->assertEquals(vfsStreamContent::TYPE_FILE, $rootChildren[0]->getType());
    }

    /**
     * @covers vfsStreamHelper_Wrapper::createFile
     * @covers vfsStreamHelper_Wrapper::createFileFromObject
     */
    public function testCreateFileFromObject()
    {
        $wrapper = new vfsStreamHelper_Wrapper($this);

        $filename    = 'testFile.txt';
        $fileContent = 'Some content';
        $newFile = new vfsStreamHelper_File($filename, $fileContent);

        $root = vfsStreamWrapper::getRoot();
        $vfsFile = $wrapper->createFile($newFile);

        $this->assertEquals($newFile->getName(), $vfsFile->getName());
        $this->assertEquals($fileContent, $vfsFile->getContent());
        $this->assertEquals(vfsStreamContent::TYPE_FILE, $vfsFile->getType());

        $rootChildren = $root->getChildren();
        $this->assertEquals(1, count($rootChildren));
        $this->assertEquals('root', $root->getName());
        $this->assertEquals($newFile->getName(), $rootChildren[0]->getName());
        $this->assertEquals(vfsStreamContent::TYPE_FILE, $rootChildren[0]->getType());
    }

    /**
     * @covers vfsStreamHelper_Wrapper::createFile
     * @covers vfsStreamHelper_Wrapper::createFiles
     */
    public function testCreateMultipleFiles()
    {
        $wrapper = new vfsStreamHelper_Wrapper($this);

        $files = array(
            new vfsStreamHelper_File('testFirstFile.txt'),
            new vfsStreamHelper_File('testSecondFile.txt'),
            new vfsStreamHelper_File('testThirdFile.txt'),
        );

        $root = vfsStreamWrapper::getRoot();
        $wrapper->createFile($files);

        $rootChildren = $root->getChildren();
        $this->assertEquals(3, count($rootChildren));
        $this->assertEquals('root', $root->getName());
        foreach ($files as $index => $entry) {
            $this->assertEquals($entry->getName(), $rootChildren[$index]->getName());
            $this->assertEquals(vfsStreamContent::TYPE_FILE, $rootChildren[$index]->getType());
        }
    }
}