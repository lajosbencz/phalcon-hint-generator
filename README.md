# Phalcon Hint Generator

## Generates PHP code hints from (Phalcon) Zephir source for IDEs

A sloppy "parser" to generate code hints for the latest **Phalcon PHP Framework**... but hey, it works! :]

I've found that the development (thankfully) of Phalcon to obsolete the handy IDE code hinting provided by [Phalcon Devtools](https://github.com/phalcon/phalcon-devtools).
Advice, bug reports, pull requests are welcome!

It should also work with any Zephir project, just ignore the warning message about the missing version file.


### Usage

```
php phalcon-hint-generator.php <directory/cphalcon/phalcon/> <directory/output>
```
Current version will be read from *version.zep* and a sub folder is created with that name.
The file *phalcon-hint-services.txt* (optional) must exist in the same directory as the PHP script.


### Features

 * Allows insertion of public properties to ```\Phalcon\Di\Injectable``` from custom text file
 * Not limited to Phalcon, you could use it for any Zephir project (testing needed though)
 * Expands property ```get```, ```set```, ```<any>``` according to Zephir standard
 * DocBlock generation/extension from parameters and hinted returns
 * Namespace detection


### Known issues

 * Some original DocBlock comments get messed up...
