<?php

namespace Chrissileinus\Template;


use RecursiveArrayIterator;

/**
 * Class NestedKeyIterator
 *
 * This iterator iterates recoursively through an array,
 * returning as the current key an imploded list of the stacked keys
 * separated by a given separator.
 *
 * Example:
 * <code>
 * $ary = [
 *      'foo' => 'bar',
 *      'baz' => ['foo' => ['uh' => 'ah'], 'oh' => 'eh']
 * ]
 * foreach (new NestedKeyIterator($ary) as $key => $value) {
 *  echo "$key: value\n",
 * }
 * </code>
 * prints
 *  foo: bar
 *  baz.foo.uh: ah
 *  baz.oh: eh
 *
 * @package StringTemplate
 */
class nestedKeyIterator extends \RecursiveIteratorIterator
{
  private $stack = [];
  private $keySeparator;

  public function __construct(\Traversable $iterator, $separator = '.', $mode = \RecursiveIteratorIterator::LEAVES_ONLY, $flags = 0)
  {
    $this->keySeparator = $separator;
    parent::__construct($iterator, $mode, $flags);
  }

  public function callGetChildren()
  {
    $this->stack[] = parent::key();
    return parent::callGetChildren();
  }

  public function endChildren()
  {
    parent::endChildren();
    array_pop($this->stack);
  }

  public function key()
  {
    $keys = $this->stack;
    $keys[] = parent::key();

    return implode($this->keySeparator, $keys);
  }
}
