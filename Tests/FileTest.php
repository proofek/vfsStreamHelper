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

require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vfsStreamHelper/File.php';
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
class vfsStreamHelper_FileTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(vfsStream::newDirectory('/'));
    }

    /**
     * @covers vfsStreamHelper_File::__construct
     * @covers vfsStreamHelper_File::getName
     * @covers vfsStreamHelper_File::getContent
     * @covers vfsStreamHelper_File::getRoot
     */
    public function testNewFileWithContentAndNoRoot()
    {
        $name = 'firstFile';
        $content = 'Some content for the file';
        $file = new vfsStreamHelper_File($name, $content);
        $root = vfsStreamWrapper::getRoot();

        $this->assertType('vfsStreamDirectory', $root);
        $this->assertAttributeEquals($name, '_name', $file);
        $this->assertAttributeEquals($content, '_content', $file);
        $this->assertAttributeEquals($root, '_root', $file);
        $this->assertEquals($name, $file->getName());
        $this->assertEquals($content, $file->getContent());
        $this->assertEquals($root, $file->getRoot());
    }

    /**
     * @covers vfsStreamHelper_Directory::__construct
     * @covers vfsStreamHelper_Directory::getName
     * @covers vfsStreamHelper_Directory::getRoot
     */
    public function testNewFileWithRoot()
    {
        $newDir = 'secondDir';
        $parentDir = 'firstDir';
        $root = vfsStream::newDirectory($parentDir)->at(vfsStreamWrapper::getRoot());

        $directory = new vfsStreamHelper_Directory($newDir, $parentDir);

        $this->assertType('vfsStreamDirectory', $root);
        $this->assertAttributeEquals($newDir, '_name', $directory);
        $this->assertAttributeEquals($root, '_root', $directory);
        $this->assertEquals($newDir, $directory->getName());
        $this->assertEquals($root, $directory->getRoot());
    }
}