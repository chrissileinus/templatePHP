<?php

include __DIR__ . '/../vendor/autoload.php';

$template = "my name is {user.name&user.color} and my age is {age&f_blue} {[{weight%6.2f}]&test.style}";

$replacements = [
  'user' => [
    'name' => "chris"
  ],
  'age' => 21,
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
