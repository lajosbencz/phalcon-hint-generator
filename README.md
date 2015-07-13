# Phalcon Hint Generator

## Generates PHP code hints from Phalcon Zephir source for IDEs

A sloppy, slow and inefficient "parser" to generate code hints for the latest **Phalcon PHP Framework**... but hey, it works! :]

I've found that the development of Phalcon (thankfully) to obsolete the handy IDE code hinting provided by [Phalcon Devtools](https://github.com/phalcon/phalcon-devtools).
Advice, bug reports, pull requests are welcome!


### Usage

```
php phalcon-hint-generator.php <directory/cphalcon/phalcon/> <directory/output>
```
Current version will be read from *version.zep* and a sub folder is created with that name.
The file *inject-services.txt* (optional) must exist in the same directory as the PHP script.


### Features

 * Namespace detection
 * DocBlock generation/extension from parameters and hinted returns
 * Allows insertion of public properties to ```\Phalcon\Di\Injectable``` from custom text file


### Known issues

 * Relative class names without ```use``` will be preceded with absolute ```\``` prefix
 * Parameter type ```long```, if used only in DocBlock will remain there hence PHP is looking for a class
