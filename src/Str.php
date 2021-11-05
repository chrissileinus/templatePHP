<?php
/*
 * Created on Wed Nov 03 2021
 *
 * Copyright (c) 2021 Christian Backus (Chrissileinus)
 *
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Chrissileinus\Template;

class Str
{
  public static $left = '{';
  public static $right = '}';

  /**
   * replace
   *
   * @param  string $template
   * @param  array $replacements
   * @return string
   */
  public static function replace(string $template, array ...$replacements): string
  {
    $replacements = array_replace_recursive([], ...$replacements);

    $result = $template;

    foreach (new nestedKeyIterator(new recursiveArrayOnlyIterator($replacements)) as $key => $value) {
      $result = str_replace(self::$left . $key . self::$right, $value, $result);
    }

    return $result;
  }

  public static function replaceF(string $template, array ...$replacements): string
  {
    return self::replaceFormat($template, ...$replacements);
  }

  public static function replaceFormat(string $template, array ...$replacements): string
  {
    //Performance: if there are no '%' fallback to self::string
    if (strstr($template, '%') == false && strstr($template, '&') == false) {
      return self::replace($template, ...$replacements);
    }

    $result = $template;

    $replacements = iterator_to_array(new nestedKeyIterator(new recursiveArrayOnlyIterator(array_replace_recursive([], ...$replacements))));

    foreach ($replacements as $key => $value) {
      $pattern = "/" . self::$left . $key . "(%[^" . self::$right . "&]+)?" . "(?:&([^" . self::$right . "]+))?" . self::$right . "/";
      preg_match_all($pattern, $template, $matches);

      $substs = array_map(function ($match, $ansi) use ($value, $replacements) {
        $tmp = $match !== '' ? sprintf($match, $value) : $value;

        $ansis = [];
        if ($ansi) {
          foreach (explode(',', $ansi) as $code) {
            if (array_key_exists($code, $replacements)) {
              foreach (explode(',', $replacements[$code]) as $_code) {
                $ansis[] = $_code;
              }
              continue;
            }
            $ansis[] = $code;
          }
          $tmp = \Chrissileinus\Ansi\Text::inject($tmp, ...$ansis);
        }

        return $tmp;
      }, $matches[1], $matches[2]);

      $result = str_replace($matches[0], $substs, $result);
      $matches = [];
    }

    if (strstr($result, '&') !== false) {
      $pattern = "/" . self::$left . "([^&]+)(?:&([^" . self::$right . "]+))" . self::$right . "/";
      preg_match_all($pattern, $result, $matches);

      $substs = array_map(function ($value, $ansi) use ($replacements) {
        $tmp = $value;

        if ($ansi) {
          foreach (explode(',', $ansi) as $code) {
            if (array_key_exists($code, $replacements)) {
              foreach (explode(',', $replacements[$code]) as $_code) {
                $ansis[] = $_code;
              }
              continue;
            }
            $ansis[] = $code;
          }
          $tmp = \Chrissileinus\Ansi\Text::inject($tmp, ...$ansis);
        }

        return $tmp;
      }, $matches[1], $matches[2]);

      $result = str_replace($matches[0], $substs, $result);
      $matches = [];
    }

    return $result;
  }
}
