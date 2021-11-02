<?php

namespace Chrissileinus\Template;

class Str
{
  public static $left = '{';
  public static $right = '}';

  public static function replace(string $template, array $replacements): string
  {
    $result = $template;

    foreach (new nestedKeyIterator(new recursiveArrayOnlyIterator($replacements)) as $key => $value) {
      $result = str_replace(self::$left . $key . self::$right, $value, $result);
    }

    return $result;
  }

  public static function replaceF(string $template, array $replacements): string
  {
    //Performance: if there are no '%' fallback to self::string
    if (strstr($template, '%') == false && strstr($template, '&') == false) {
      return self::replace($template, $replacements);
    }

    $result = $template;

    foreach (new nestedKeyIterator(new recursiveArrayOnlyIterator($replacements)) as $key => $value) {
      $pattern = "/" . self::$left . $key . "(%[^" . self::$right . "&]+)?" . "(?:&([^" . self::$right . "]+))?" . self::$right . "/";
      preg_match_all($pattern, $template, $matches);

      $substs = array_map(function ($match, $ansi) use ($value) {
        $tmp = $match !== '' ? sprintf($match, $value) : $value;

        if ($ansi) {
          $tmp = \Chrissileinus\Ansi\Text::inject($tmp, ...explode(',', $ansi));
        }

        return $tmp;
      }, $matches[1], $matches[2]);

      $result = str_replace($matches[0], $substs, $result);
      $matches = [];
    }

    if (strstr($result, '&') !== false) {
      $pattern = "/" . self::$left . "([^&]+)(?:&([^" . self::$right . "]+))" . self::$right . "/";
      preg_match_all($pattern, $result, $matches);

      var_dump($matches);

      $substs = array_map(function ($value, $ansi) {
        $tmp = $value;

        if ($ansi) {
          $tmp = \Chrissileinus\Ansi\Text::inject($tmp, ...explode(',', $ansi));
        }

        return $tmp;
      }, $matches[1], $matches[2]);

      $result = str_replace($matches[0], $substs, $result);
      $matches = [];
    }

    return $result;
  }
}
