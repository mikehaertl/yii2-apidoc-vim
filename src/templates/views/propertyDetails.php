<?php
if(!count($type->nativeProperties))
    return;
$typeName = $type->name;
$inherited = array_filter($type->properties, function ($property) use ($typeName) {
    return $property->definedBy !== $typeName;
});
?>


PROPERTY DETAILS
------------------------------------------------------------------------------

<?php

if (count($inherited)) {
    echo "Inherited properties:\n\n";
    foreach ($inherited as $property) {
        echo ' |' . $this->context->convertNamespace($property->definedBy).'::'.$property->name."|\n";
    }
    echo "\n";
}
?>
<?php foreach($type->nativeProperties as $property): ?>

<?= $this->context->renderTitleTag($property->name,$this->context->createTag($type->name,$property->name,'*'))."\n\n"; ?>
<?= $this->context->renderDescription("($property->type) ".$property->description,1); ?>

<?php endforeach; ?>
