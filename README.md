# templatePHP

A template engine to generate strings from a template. Also it is prossible to colorize parts with ANSI codes with the help of the [ansiPHP](https://gitlab.com/chrissileinus/ansiPHP) class.

## Why

On the search of a simple template engine to generate some output on the bash i found [nicmart/StringTemplate](https://github.com/nicmart/StringTemplate). But in the need for static callable methods with colorizing features, I decided to write something similar.

## Usage

```php
$template = "my name is {user.name&user.color} and my age is {age&f_blue} {[{weight%6.2f}]&test.style}";

$replacements = [
  'user' => [
    'name' => "chris"
  ],
  'age' => 41,
  'weight' => 83.4,
  'test' => [
    'style' => "bold,f_magenta"
  ]
];

$result = Chrissileinus\Template\Str::replaceF(
  $template,
  $replacements,
  [
    'user' => [
      'color' => "f_yellow"
    ]
  ]
);

echo $result . PHP_EOL;
```