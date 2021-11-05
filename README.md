# templatePHP

A template engine to generate strings from a template. It is also prossible to colorize parts with ANSI codes with the help of the [ansiPHP](https://gitlab.com/chrissileinus/ansiPHP) class.

## Why

On the search of a simple template engine to generate some output on the bash i found [nicmart/StringTemplate](https://github.com/nicmart/StringTemplate). But in the need for static callable methods with colorizing features, I decided to write something similar.

## Usage

### replace

Placeholders are delimited by default by `{` and `}`, but you can specify others through the class constructor.

If array key is not specified then write the index into the template.

```php
// @returns string: "This is my value: nic"
Chrissileinus\Template\Str::replace("This is my value: {0}", ['nic']);
```

With specified keys are practical on longer templates.

```php
// @returns string: "My name is Christian Backus"
Chrissileinus\Template\Str::replace(
  "My name is {name} {surname}",
  [
    'name' => 'Christian',
    'surname' => 'Backus'
  ]
);
```

You are also able to access nested arrays.

```php
// @returns string: "My name is Christian and her name is Aline"
Chrissileinus\Template\Str::replace(
  "My name is {my.name} and her name is {her.name}",
  [
    'my' => ['name' => 'Christian'],
    'her' => ['name' => 'Aline']
  ]
);
```

You are also able to submit several arrays. All arrays get merged by `array_replace_recursive` with the given order.

```php
// @returns string: "My name is Christian and her name is Aline"
Chrissileinus\Template\Str::replace(
  "My name is {my.name} and her name is {her.name}",
  [
    'my' => ['name' => 'Christian']
  ],
  [
    'her' => ['name' => 'Aline']
  ]
);
```

### replaceFormat | replaceF

With the usage of sprintf we are able to use the [convertion specifications](https://www.php.net/manual/en/function.sprintf.php) for the placeholders.

```php
// @returns string: "My name is chris and my age is 041 [ 83.40]"
Chrissileinus\Template\Str::replaceFormat(
  "My name is {user.name} and my age is {age%03d} [{weight%6.2f}]",
  [
    'user' => [
      'name' => "chris"
    ],
    'age' => 41,
    'weight' => 83.4,
  ]
);
```

Additionally we also able to colorize the result with the ANSI Commands. Helpful for output in terminal applications.

Just a additional `&` and a list of color and styling commands. They can also be spezified in the input array.

```php
// @returns string: "My name is chris and my age is 041 [ 83.40]"
Chrissileinus\Template\Str::replaceFormat(
  "My name is {user.name&user.color} and {my&user.color} age is {age&f_blue} {[{weight%6.2f}]&test.style}",
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
);
```
