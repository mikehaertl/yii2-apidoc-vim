<?php
namespace mikehaertl\yii2\apidoc\vim\templates;
use yii\apidoc\models\ClassDoc;
use yii\apidoc\renderers\ApiRenderer as BaseApiRenderer;
use yii\base\ViewContextInterface;
use yii\web\View;
use Yii;
use yii\helpers\Console;

/**
 * @author Michael Härtl <haertl.mike@gmail.com>
 */
class ApiRenderer extends BaseApiRenderer implements ViewContextInterface
{
    const URL_PATTERN='/\{\{(.*?)\|(.*?)\}\}/s';
    const CODE_PATTERN='/\s*(?:~~~|```php)(.*?)(?:~~~|```)\s*/is';
    const UL_PATTERN='|\s*<ul>(.*?)</ul>\s*|is';
    const OL_PATTERN='|\s*<ol>(.*?)</ol>\s*|is';
    const LI_PATTERN='|\s*<li>(.*?)</li>\s*|is';
    const A_PATTERN='|<a.*?>(.*?)</a>|is';

    // Separator between classname and property/method
    const SEP='::';

    protected $_view;
    protected $_codeBlocks = [];

    /**
     * @inheritDoc
     */
    public function render($context, $targetDir)
    {
        $types = array_merge($context->classes, $context->traits, $context->interfaces);
        $typeCount = count($types) + 1;

        if ($this->controller !== null) {
            Console::startProgress(0, $typeCount, 'Rendering files: ', false);
        }
        $done = 0;
        foreach ($types as $type) {
            $fileContent = $this->getView()->render('type', [
                'type' => $type,
                'apiContext' => $context,
                'types' => $types,
            ], $this);
            file_put_contents($targetDir . '/' . $this->generateFileName($type->name), $fileContent);

            if ($this->controller !== null) {
                Console::updateProgress(++$done, $typeCount);
            }
        }

        if ($this->controller !== null) {
            Console::updateProgress(++$done, $typeCount);
            Console::endProgress(true);
            $this->controller->stdout('done.' . PHP_EOL, Console::FG_GREEN);
        }
    }

    /**
     * @return View the view instance
     */
    public function getView()
    {
        if ($this->_view === null) {
            $this->_view = new View(['context' => $this]);
        }

        return $this->_view;
    }

    /**
     * @inheritDoc
     */
    public function getViewPath()
    {
        return Yii::getAlias('@mikehaertl/yii2/apidoc/vim/templates/views');
    }

    protected function generateFileName($typeName)
    {
        return strtolower(strtr($typeName, ['\\'=>'-', '/'=>'-'])) . '.txt';
    }

    protected function generateLink($text, $href, $options = [])
    {
    }

    /**
     * Generate an url to a type in apidocs
     * @param $typeName
     * @return mixed
     */
    public function generateApiUrl($typeName)
    {
    }

    /**
     * Render a line with title on the left and tag on position $column. Fills
     * up space between with as many tabs and as few spaces as possible.
     *
     * @param string $title on the left
     * @param string $tag on the right, including '*'s
     * @param int $column position where tag should start
     * @param int $tabstop width of a single tab (spaces)
     * @param string $ignore character to ignore for width (e.g. '*')
     * @return string string with as many tabs as possible between title and tag
     */
    public function renderTitleTag($title,$tag,$column=40,$tabstop=8,$ignore='') 
    {
        $out=sprintf("%-{$column}s%s",$title,$tag);	// first use spaces
        return $this->spaces2tabs($out,$tabstop,$ignore);	// then convert to tabs
    }

    /**
     * @param mixed $class 
     * @return string the inheritance "breadcrumb", separated with ' >> '
     */
    public function renderInheritance($class)
    {
        $parents = [];
        if($class instanceof ClassDoc) {
            while ($class->parentClass !== null) {
                if (isset($this->apiContext->classes[$class->parentClass])) {
                    $class = $this->apiContext->classes[$class->parentClass];
                    $parents[] = $this->convertNamespace($class);
                } else {
                    $parents[] = '|'.$this->convertNamespace($class->parentClass).'|';
                    break;
                }
            }
        }
        return count($parents) ? ' >> '.implode(' >> ',$parents) : '';
    }

    /**
     * @param mixed $type
     * @return string the subclasses
     */
    public function renderSubclasses($type)
    {
        $classes = [];
        foreach($type->subclasses as $class) {
            $classes[] = '|'.$this->convertNamespace($class).'|';
        }

        return implode($classes, "\n");
    }

    /**
     * Render method signature including param defaults
     * 
     * @param MethodDoc $method model
     * @return string the formatted signature
     */
    public function renderMethodSignature($method)
    {
        $params=[];
        foreach($method->params as $param)
            $params[]=$param->name.$this->paramDefault($param);

        return $method->name.'('.implode(', ',$params).')';
    }

    /**
     * Render description comment
     * 
     * @param mixed $description 
     * @param int $indent indent text to this column
     * @return string the rendered description
     */
    public function renderDescription($content,$indent=0,$wrap=78)
    {
        $content=$this->processMarkdown($content);
        $content=$this->wrapindent($content,$indent,$wrap);
        return $content;
    }

    /**
     * Render a single method parameter including code examples
     * 
     * @param ParamDoc $param model
     * @param int $indent how many columns to indent the string
     * @param int $wrap number of columns (incl. indent) of a line
     * @return string the formated text block for a parameter
     */
    public function renderMethodParam($param,$indent=0,$wrap=78)
    {
        $content=$this->processMarkdown($param->description);
        $content="[$param->name] ($param->type) ".$content;
        $content=$this->wrapindent($content,$indent,$wrap);
        return $content;
    }

    /**
     * Render the return parameter
     * 
     * @param MethodDoc $method
     * @param int $indent how many columns to indent the string
     * @param int $wrap number of columns (incl. indent) of a line
     * @return string the formated text block of a return value
     */
    public function renderMethodReturnValue($method,$indent=0,$wrap=78)
    {
        if (empty($method->returnType))
            return "(void)\n";
        $content=$this->processMarkdown($method->return);
        $content="($method->returnType) $content\n";
        $content=$this->wrapindent($content,$indent,$wrap);
        return $content;
    }

    /**
     * @param string $class name of the class
     * @param mixed $property optional property/method name
     * @param string $surround string to put around created tag (default '')
     * @access public
     * @return string name of the vim tag
     */
    public function createTag($class,$property=null,$surround='')
    {
        $class = $this->convertNamespace($class);
        if ($property===null)
            return $surround.$class.$surround;
        return $surround.$class.self::SEP.$property.$surround;
    }

    /**
     * @param string $text
     * @return string the text with tag names converted to dot notation
     */
    public function convertNamespace($text)
    {
        return strtr($text, ['\\'=>'/']);
    }

    /**
     * @param string $content with markdown text
     * @return string text converted to Vim help file format
     */
    protected function processMarkdown($content)
    {
        // Cut out and store code blocks, replace with placeholder
        $content=preg_replace_callback(self::CODE_PATTERN,[$this,'captureCodeBlocks'],$content);

        // Replace all linebreaks with space, but keep paragraphs
        $content=trim(strtr($content,[ "\n\n"=>'---LB---',"\n"=>' ']));
        $content=strtr($content,[ "---LB---" => "\n\n"]);

        $content = strtr($content, [
            '`' => "'",
            '[[' => "'",
            ']]' => "'",
        ]);

        // Now put back code blocks in same order
        $content=preg_replace_callback('/###CODE###/',[ $this,'insertCodeBlocks' ],$content);

        return $content;
    }

    /**
     * Captures code blocks and replaces them with a placeholder
     *
     * @param array $matches from a preg_replace_callback call
     * @return string code placeholder '###CODE###'
     */
    protected function captureCodeBlocks($matches)
    {
        $code=$this->stripEmptyLines($matches[1]);
        $this->_codeBlocks[]=htmlspecialchars_decode($code);
        return '###CODE###';
    }

    /**
     * Returns the next captured code block on each call
     *
     * @param array $matches from a preg_replace_callback call
     * @return string the stored code block
     */
    protected function insertCodeBlocks($matches)
    {
        $code = array_shift($this->_codeBlocks);
        // Indent by 4 spaces
        $code = preg_replace('/^(?=.+)/m',sprintf("%2s",''),$code);
        return "\n\n".$code."\n\n";
    }

    /**
     * @param string $content a multi line string
     * @access protected
     * @return string with empty lines at beginning and end deleted
     */
    protected function stripEmptyLines($content)
    {
        return preg_replace('/(^\s*\n|\n\s*$)/','',$content);
    }

    /**
     * @param ParamDoc $param model
     * @return string the parameter signature
     */
    protected function paramDefault($param) 
    {
        if (!$param->isOptional)
            return '';

        switch($param->type) {
            case 'string': return "='$param->defaultValue'";
            case 'integer': 
            case 'int': return '='.$param->defaultValue;
            default: 
                return '='.strtr(
                    var_export($param->defaultValue,true),
                    [ "\n"=>'',' '=>'' ]
                );
        }
    }

    /**
     * @param string $text to indent and wrap
     * @param mixed $indent how many columns to indent
     * @param mixed $wrap total column count (incl. indent)
     * @return string the text indented with spaces and wrapped
     */
    protected function wrapindent($text,$indent,$wrap)
    {
        $text=wordwrap($text,$wrap-$indent,"\n");
        return preg_replace('/^(?=.+)/m',sprintf("%{$indent}s",''),$text);
    }

    /**
     * @param string text where start is aligned to a tab or left border
     * @param int $ts tabstop (default 8)
     * @param string $ignore character to ignore, e.g. '*'. Will be removed before conversion
     * @return string text with as many spaces as possible replaced with tabs
     */
    protected function spaces2tabs($text,$ts=8,$ignore='')
    {
        $len = strlen($text);
        $out = '';
        $offset = 0;
        for($x=0; $x < $len; $x+=$ts) {
            $addTab = false;
            for($y=0; $y < $ts && ($x+$y+$offset) < $len; $y++) {
                $char = $text[$x+$y+$offset];
                if($char===$ignore) {
                    $offset++;
                    $out .= $char;
                    $char = $text[$x+$y+$offset];
                }
                if($char===' ') {
                    $addTab = true;
                } else {
                    $out .= $char;
                    $addTab = false;
                }
            }
            if($addTab) {
                $out .= "\t";
            }
        }
        return $out;
    }
}
