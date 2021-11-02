<?php

namespace Chrissileinus\Template;

/**
 * Class recursiveArrayOnlyIterator
 *
 * It's like RecursiveArrayIterator, but prevents it to iterate through objects
 *
 * @package StringTemplate
 */
class recursiveArrayOnlyIterator extends \RecursiveArrayIterator
{
  public function hasChildren()
  {
    return is_array($this->current()) || $this->current() instanceof \Traversable;
  }
}
