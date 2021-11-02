<?php

include __DIR__ . '/../vendor/autoload.php';

$template = "my name is {name&f_yellow} and my arge is {age&f_blue} {[{weight%6.2f}]&bold,f_magenta}";

$replacemants = [
  'name' => "chris",
  'age' => 21,
  'weight' => 83.4
];

$result = Chrissileinus\Template\Engine::replaceF($template, $replacemants);

echo $result . PHP_EOL;
