<?php
$name = $this->context->convertNamespace($type->name);
$inheritance = $this->context->renderInheritance($type);
$l=strlen($name)+2; 
echo "\n*$name*  $inheritance";
printf("\n%'={$l}s\n",''); 
?>
<?php if(!empty($type->subclasses)): ?>

SUBCLASSES
------------------------------------------------------------------------------

<?= $this->context->renderSubclasses($type) ?>

<?php endif; ?>
<?php if(!empty($type->description)): ?>

DESCRIPTION
------------------------------------------------------------------------------

<?= $this->context->renderDescription($type->description,1); ?>

<?php endif; ?>
<?= $this->render('propertyDetails',array('type'=>$type)); ?>

<?= $this->render('methodDetails',array('type'=>$type)); ?>


 vim:tw=78:ts=8:ft=help:norl:
