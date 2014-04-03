<?php
$l=strlen($type->name)+2; 
echo "\n*".$type->name.'*  '.$this->context->renderInheritance($type);
printf("\n%'={$l}s\n",''); 
?>

<?= $this->context->renderDescription($type->description,1); ?>

<?= $this->render('propertyDetails',array('type'=>$type)); ?>

<?= $this->render('methodDetails',array('type'=>$type)); ?>


 vim:tw=78:ts=8:ft=help:norl:
