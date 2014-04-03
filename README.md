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

## Use

There's no real configuration required for this plugin. After installing it, you can
ask for help on Yii 2 classes like this:

```vim
:help yii\web\Controller
```

If you want to use keyword search (which allows to press `<S-k>` over any keyword)
then you should add this line to your `.vimrc`:

```vim
autocmd BufNewFile,Bufread *.php set keywordprg="help"
```

## Create custom version

The package also contains the Yii 2 command that was used to create the help files.
You should install the package `mikehaertl/yii2-vim-apidoc` with composer.
