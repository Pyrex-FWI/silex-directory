<?php

namespace Cpyree;


use RecursiveIterator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\VarDumper\VarDumper;

class Dir implements \RecursiveIterator
{
    private $workingPath;

    private $subDirs;

    private $subFiles;

    public function __construct($workingPath)
    {
        $this->setWorkingPath($workingPath);

        if (is_dir($this->getWorkingPath())) {
            VarDumper::dump(Finder::create()->in($this->getWorkingPath())->directories()->count());
            VarDumper::dump(Finder::create()->in($this->getWorkingPath())->files()->count());
        }
    }

    /**
     * @return mixed
     */
    private function getWorkingPath()
    {
        return $this->workingPath;
    }

    /**
     * @param mixed $workingPath
     * @return Dir
     */
    private function setWorkingPath($workingPath)
    {
        $this->workingPath = $workingPath;
        return $this;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        // TODO: Implement current() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        // TODO: Implement next() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        // TODO: Implement key() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        // TODO: Implement valid() method.
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        // TODO: Implement rewind() method.
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Returns if an iterator can be created for the current entry.
     * @link http://php.net/manual/en/recursiveiterator.haschildren.php
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     */
    public function hasChildren()
    {
        // TODO: Implement hasChildren() method.
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Returns an iterator for the current entry.
     * @link http://php.net/manual/en/recursiveiterator.getchildren.php
     * @return RecursiveIterator An iterator for the current entry.
     */
    public function getChildren()
    {
        // TODO: Implement getChildren() method.
    }
}