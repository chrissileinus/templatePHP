<?php

include __DIR__ . '/../vendor/autoload.php';

echo Chrissileinus\Template\Str::replace(
  "This is my value: {0}",
  ['nic']
) . PHP_EOL;

echo Chrissileinus\Template\Str::replace(
  "My name is {name} {surname}",
  [
    'name' => 'Christian',
    'surname' => 'Backus'
  ]
) . PHP_EOL;

echo Chrissileinus\Template\Str::replace(
  "My name is {my.name} and her name is {her.name}",
  [
    'my' => ['name' => 'Christian'],
    'her' => ['name' => 'Aline']
  ]
) . PHP_EOL;

echo Chrissileinus\Template\Str::replace(
  "My name is {my.name} and her name is {her.name}",
  [
    'my' => ['name' => 'Christian']
  ],
  [
    'her' => ['name' => 'Aline']
  ]
) . PHP_EOL;

echo Chrissileinus\Template\Str::replaceFormat(
  "My name is {user.name} and my age is {age%03d} [{weight%6.2f}]",
  [
    'user' => [
      'name' => "chris"
    ],
    'age' => 41,
    'weight' => 83.4,
  ]
) . PHP_EOL;

echo Chrissileinus\Template\Str::replaceFormat(
  "My name is {user.name&user.color} and {my&user.color} age is {age%03d&f_blue} {[{weight%6.2f}]&test.style}",
  [
    'user' => [
      'name' => "chris"
    ],
    'age' => 41,
    'weight' => 83.4,
  ],
  [
    'user' => [
      'color' => "f_yellow"
    ],
    'test' => [
      'style' => "bold,f_magenta"
    ]
  ]
) . PHP_EOL;
