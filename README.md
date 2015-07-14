# Phalcon Hint Generator

## Generates PHP code hints from (Phalcon) Zephir source for IDEs

A simple "parser" to generate code hints for the latest **Phalcon PHP Framework** or any Zephir based PSR-0 project.

I've found that the development of Phalcon (thankfully) obsoletes the handy IDE code hinting provided by [Phalcon Devtools](https://github.com/phalcon/phalcon-devtools).
Bug reports, pull requests are welcome! :]

It should also work with any Zephir project, just ignore the warning message about the missing version file.


### Usage

```
php phalcon-hint-generator.php <directory/cphalcon/phalcon/> <directory/output>
```
Current version of Phalcon will be read from *version.zep* and a sub folder is created with that name.
The file *phalcon-hint-services.txt* (optional) should exist in the same directory as the PHP script.


### Features

 * Allows insertion of public properties to ```\Phalcon\Di\Injectable``` from a custom text file
 * Not limited to Phalcon, you could use it for any Zephir project
 * Expands property ```get```, ```set```, ```<any>``` according to Zephir standard
 * DocBlock generation/extension from parameters and hinted returns
 * Namespace detection
