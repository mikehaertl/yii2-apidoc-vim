Yii 2 API docs for Vim
======================

## Installation

Add `mikehaertl/yii2-apidoc-vim` to your [Vundle](https://github.com/gmarik/vundle) configuration.

Alternatively you can download the files from [here](https://github.com/mikehaertl/yii2-apidoc-vim/tags),
extract the package and move the `docs/` directory to your `~/.vim` directory.

If the help is not available after installation, you can try to issue this command in VIM:

```vim
:helptags ~/.vimrc/doc
```

## How to use

There is no configuration required for this plugin.

```vim
:help yii/web/Controller
```

You should note though, that we use a forward slash (`/`)  instead of a backslash (`\`) as
namespace separator. This was neccessary because when searching for tags, Vim uses regular
expressions. So any backslash would indicate the start of a search pattern and you would
have to type e.g. `yii\\base\\Controller`. Autocomplete wouldn't work very well either.

## How to create the doc files

To create the doc files, it's recommended to start with an empty directory and create
the following `composer.json` file there:

```
{
    "minimum-stability": "dev",
    "require": {
        "mikehaertl/yii2-apidoc-vim": "*",
        "yiisoft/yii2": "2.0.0",
        "cebe/markdown": "dev-master as 0.9.0",
        "cebe/markdown-latex": "*",
        "phpdocumentor/reflection": "*"
    },
    "require-dev": {
        "yiisoft/yii2-dev": "2.0.0"
    }
}
```

Then run `composer update` to install all the required packages. After this is done, the
doc can be created with:

```
./vendor/bin/apidocvim api vendor/yiisoft/yii2-dev/framework vendor/mikehaertl/yii2-apidoc-vim/doc
./vendor/bin/apidocvim api vendor/yiisoft/yii2-dev/extensions vendor/mikehaertl/yii2-apidoc-vim/doc
```

## How to customize the created doc files

You can modify the view files and adjust the render methods in `src/templates/ApiRenderer.php`.
