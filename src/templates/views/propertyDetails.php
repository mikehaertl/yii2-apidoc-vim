<?php if(!count($type->nativeProperties)) return; ?>


PROPERTY DETAILS
------------------------------------------------------------------------------
<?php foreach($type->properties as $property): ?>

<?= $this->context->renderTitleTag($property->name,$this->context->createTag($type->name,$property->name,'*'))."\n"; ?>
<?php if($property->definedBy != $type->name): ?>
 See |<?php echo $this->context->convertNamespace($property->definedBy).'::'.$property->name ?>|
<?php else: ?>
<?= $this->context->renderDescription("($property->type) ".$property->description,1); ?>

<?php endif; ?>
<?php endforeach; ?>
