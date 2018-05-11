<?php
if(!count($type->nativeMethods))
    return;
$typeName = $type->name;
$inherited = array_filter($type->methods, function ($method) use ($typeName) {
    return $method->definedBy !== $typeName;
});
?>


METHOD DETAILS
------------------------------------------------------------------------------

<?php

if (count($inherited)) {
    echo "Inherited methods:\n\n";
    foreach ($inherited as $method) {
        echo ' |' . $this->context->convertNamespace($method->definedBy).'::'.$method->name."|\n";
    }
    echo "\n";
}
foreach($type->nativeMethods as $method) {
    echo "\n";
    echo $this->context->renderTitleTag(
        $this->context->createTag($type->name,$method->name,'*'),
        $method->visibility,
        65,
        8,
        '*'
    ). "\n";

    echo ">\n";
    echo ' '.$this->context->renderMethodSignature($method)."\n";
    echo "<\n";
    echo 'return '. $this->context->renderMethodReturnValue($method)."\n";
    if (!empty($method->description) || !empty($method->shortDescription)) {
        if (!empty($method->shortDescription)) {
            echo $this->context->renderDescription($method->shortDescription,1)."\n";
        }
        if (!empty($method->description)) {
            if (!empty($method->shortDescription)) {
                echo "\n";
            }
            echo $this->context->renderDescription($method->description,1)."\n";
        }
        if ($method->params) {
            echo "\n";
        }
    }
    if ($method->params) {
        foreach($method->params as $param) {
            echo $this->context->renderMethodParam($param,4)."\n";
        }
    }
    echo "\n";
}
