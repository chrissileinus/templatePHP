<?php

namespace Chrissileinus\Template;

class nestedKeyArray implements \ArrayAccess, \IteratorAggregate
{
  private $array;
  private $keySeparator;

  public function __construct(array &$array, $keySeparator = '.')
  {
    $this->array = $array;
    $this->keySeparator = $keySeparator;
  }

  public function getIterator()
  {
    return new nestedKeyIterator(new recursiveArrayOnlyIterator($this->array));
  }

  public function offsetExists($offset)
  {
    $keys = explode($this->keySeparator, $offset);
    $ary = &$this->array;

    foreach ($keys as $key) {
      if (!isset($ary[$key]))
        return false;
      $ary = &$ary[$key];
    }

    return true;
  }

  public function offsetGet($offset)
  {
    $keys = explode($this->keySeparator, $offset);
    $result = &$this->array;

    foreach ($keys as $key) {
      $result = &$result[$key];
    }

    return $result;
  }

  public function offsetSet($offset, $value)
  {
    $this->setNestedOffset($this->array, explode($this->keySeparator, $offset), $value);
  }

  public function offsetUnset($offset)
  {
    $this->unsetNestedOffset($this->array, explode($this->keySeparator, $offset));
  }


  public function toArray()
  {
    return $this->array;
  }

  private function setNestedOffset(array &$target, array $offsets, $value)
  {
    $currKey = array_shift($offsets);

    if (!$offsets) {
      $target[$currKey] = $value;
    } else {
      if (!isset($target[$currKey]))
        $target[$currKey] = array();
      $this->setNestedOffset($target[$currKey], $offsets, $value);
    }

    return $this;
  }

  private function unsetNestedOffset(array &$target, array $offsets)
  {
    $currKey = array_shift($offsets);

    if (!$offsets) {
      unset($target[$currKey]);
    } elseif (isset($target[$currKey])) {
      $this->unsetNestedOffset($target[$currKey], $offsets);
    }

    return $this;
  }
}
