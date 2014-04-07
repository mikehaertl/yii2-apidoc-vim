Yii 2 API docs for Vim
======================

## Installation

Add `mikehaertl/yii2-vim-apidoc` to your [Vundle](https://github.com/gmarik/vundle) configuration.

Alternatively you can download the files from [here](https://github.com/mikehaertl/yii2-vim-apidocs/tags),
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

You should note though, that we use a dot instead of a backslash (`\`) as namespace separator.
This was neccessary because Vim tries to read any tag search argument as regular expression.
So any backslash would indicate the start of a search pattern and you would have to type e.g.
`yii\\base\\Controller`. Autocomplete wouldn't work very well either.

## Create custom version

The package also contains the Yii 2 command that was used to create the help files.
You should install the package `mikehaertl/yii2-vim-apidoc` with composer. Then you
can create the docs with

```
./vendor/bin/apidocvim api ./vendor/yiisoft/yii2 ./docs
```

You can modify the view files and adjust the render methods in `src/templates/ApiRenderer.php`.
