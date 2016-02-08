# PHP Forbidden Functions

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nilportugues/php_forbidden_functions/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nilportugues/php_forbidden_functions/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/ba34c91a-4ee4-4b0d-8c7c-8ad3019a6fcc/mini.png)](https://insight.sensiolabs.com/projects/ba34c91a-4ee4-4b0d-8c7c-8ad3019a6fcc) [![Latest Stable Version](https://poser.pugx.org/nilportugues/php_forbidden/v/stable)](https://packagist.org/packages/nilportugues/php_forbidden) [![Total Downloads](https://poser.pugx.org/nilportugues/php_forbidden/downloads)](https://packagist.org/packages/nilportugues/php_forbidden) [![License](https://poser.pugx.org/nilportugues/php_forbidden/license)](https://packagist.org/packages/nilportugues/php_forbidden)


Why? Because someone should look for forbidden functions that should be avoided in production.

Typical examples are `print_r`, `var_dump`, `exit` and `die` function calls.

## Installation

Use [Composer](https://getcomposer.org) to install the package:

```
$ composer require --dev nilportugues/php_forbidden
```

## Usage

It is really this simple:

```
$ php bin/php_forbidden check <path/to/directory>
```

```
$ php bin/php_forbidden check <path/to/file>
```

You can also pipe STDIN in, if you want to use this tool with Gulp for instance:

```
$ cat <path/to/file> | php bin/php_forbidden check
```

which means that this also works writing code directly from the shell (if you have some reason to do it):

```
$ php bin/php_forbidden check
<?php
// Insert your code
// and press CTRL+D to send EOF
```

### Configuration file

When run the first time, if no `php_forbidden_function.yml` file is found, it will be generated.

A configuration for instance, should formatted as follows:

```yml
forbidden:
  - file_get_contents
  - fopen
  - die
  - var_dump
  - print_r
```

You can specify an alternate location for the configuration file by passing in the `-c` parameter. Example:

```
$  php bin/php_forbidden check -c configs/php_forbidden_function.yml src/
```

## Contribute

Contributions to the package are always welcome!

* Report any bugs or issues you find on the [issue tracker](https://github.com/nilportugues/php_forbidden_functions/issues/new).
* You can grab the source code at the package's [Git repository](https://github.com/nilportugues/php_forbidden_functions).


## Support

Get in touch with me using one of the following means:

 - Emailing me at <contact@nilportugues.com>
 - Opening an [Issue](https://github.com/nilportugues/php_forbidden_functions/issues/new)


## Authors

* [Nil Portugués Calderó](http://nilportugues.com)
* [The Community Contributors](https://github.com/nilportugues/php_forbidden_functions/graphs/contributors)


## License
The code base is licensed under the [MIT license](LICENSE).
