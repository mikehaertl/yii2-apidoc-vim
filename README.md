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

> **Note:** This is only required if want to build your own flavour of the help files.

Start in an empty directory and install the `yii2-apidoc-vim` package via composer:

```
composer require mikehaertl\yii2-apidoc-vim dev-master

```

Now you can run the `apidocvim` command to extract the docs in Vim format from any
source files:

```
./apidocvim api path/to/some/source/code /path/to/output/dir
```

You can also use the `update-all` command to automatically extract the doc files from
the currenty yii2 packages installed in `vendor/`.

## How to customize the created doc files

You can modify the view files in `src/templates/views` and adjust the render methods in
`src/templates/ApiRenderer.php`.
