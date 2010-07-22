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
require_once dirname(__FILE__) . '/Directory.php';
require_once dirname(__FILE__) . '/File.php';
/**
 * vfsStream Wrapper
 *
 * @package    vfsStreamHelper
 * @author     Sebastian Marek <proofek@gmail.com>
 * @copyright  2010 Sebastian Marek <proofek@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @since      Class available since Release 0.1
 */
class vfsStreamHelper_Wrapper
{
    /**
     * Registers vfsStream
     *
     * @param PHPUnit_Framework_TestCase $testCase
     */
    public function __construct(PHPUnit_Framework_TestCase $testCase, $mountPoint = 'root')
    {
        @include_once 'vfsStream/vfsStream.php';
        if (!class_exists('vfsStreamWrapper')) {

            $this->markTestSkipped('vfsStream is not available - skipping');

        } else {
            vfsStream::setup($mountPoint);
        }
    }

    /**
     * Creates a {@link vfsStreamDirectory} directory(ies)
     *
     * <i>$directory</i> can either be a string or
     * {@link vfsStreamHelper_Directory} object or an array of
     * {@link vfsStreamHelper_Directory} objects
     *
     * It will return null if multiple {@link vfsStreamDirectory} are created
     * or a {@link vfsStreamDirectory} object if a single directory is created
     *
     * @param mixed $directory
     *
     * @return mixed
     */
    public function createDirectory($directory)
    {
        if (is_string($directory)) {

            return $this->createDirectoryFromString($directory);

        } elseif (is_array($directory)) {

            $this->createDirectories($directory);

        } else {

             return $this->createDirectoryFromObject($directory);
        }
    }

    /**
     * Creates a {@link vfsStreamFile} file(s)
     *
     * <i>$file</i> can either be a string or
     * {@link vfsStreamHelper_File} object or an array of
     * {@link vfsStreamHelper_File} objects
     *
     * It will return null if multiple {@link vfsStreamFile} are created
     * or a {@link vfsStreamFile} object if a single file is created
     *
     * @param mixed $file
     *
     * @return mixed
     */
    public function createFile($file)
    {
        if (is_string($file)) {

            return $this->createFileFromString($file);

        } elseif (is_array($file)) {

            $this->createFiles($file);

        } else {

            return $this->createFileFromObject($file);
        }
    }

    /**
     * Creates a virtual directory
     *
     * @param string $directory Directory name
     *
     * @return vfsStreamDirectory
     */
    protected function createDirectoryFromString($directory)
    {
        return $this->createDirectory(new vfsStreamHelper_Directory($directory));
    }

    /**
     * Creates a virtual directory
     *
     * @param vfsStreamHelper_Directory $directory
     *
     * @return vfsStreamDirectory
     */
    protected function createDirectoryFromObject(vfsStreamHelper_Directory $directory)
    {
        return vfsStream::newDirectory($directory->getName())
                          ->at($directory->getRoot());
    }

    /**
     * Creates multiple virtual directories
     *
     * @param array $directories Directories list
     *
     * @return void
     */
    protected function createDirectories(array $directories)
    {
        foreach ($directories as $directory) {

            $this->createDirectoryFromObject($directory);
        }
    }

    /**
     * Creates an empty virtual file
     *
     * @param string $file File name
     *
     * @return vfsStreamFile
     */
    protected function createFileFromString($file)
    {
        return $this->createFileFromObject(new vfsStreamHelper_File($file));
    }

    /**
     * Creates a virtual file
     *
     * @param vfsStreamHelper_File $file
     *
     * @return vfsStreamFile
     */
    protected function createFileFromObject(vfsStreamHelper_File $file)
    {
        return  vfsStream::newFile($file->getName())
                         ->withContent($file->getContent())
                         ->at($file->getRoot());
    }

    /**
     * Creates multiple virtual files
     *
     * @param array $files Files list
     *
     * @return void
     */
    protected function createFiles(array $files)
    {
        foreach ($files as $file) {

            $this->createFileFromObject($file);
        }
    }
}