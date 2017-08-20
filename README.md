# mleko/wingman

Sort composer.json based on the well-known composer.json keys. 
List and order of keys is derived from composer [docs](https://getcomposer.org/doc/04-schema.md).

[![Build Status](https://travis-ci.org/mleko/wingman.svg?branch=master)](https://travis-ci.org/mleko/wingman)

## CLI usage
Install `mleko/wingman` as global package
```
$ composer global require mleko/wingman
```

Format specific `composer.json` file

```
$ wingman format /project-dir/composer.json
Formatting file: /project-dir/composer.json
```

Format `composer.json` in current directory

```
$ cd /project-dir 
$ wingman
Formatting file: /project-dir/composer.json
```

## Composer script
Install `mleko/wingman` as dependency
```
$ composer require --dev mleko/wingman
```

add `post-update-cmd` script `Mleko\\Wingman\\Composer\\EventHandler::format`

example
```
{
    ...
    "scripts": {
            "post-update-cmd": [
                "Mleko\\Wingman\\Composer\\EventHandler::format"
            ]
        }
}
```
