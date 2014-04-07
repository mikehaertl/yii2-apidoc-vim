<?php if(!count($type->nativeMethods)) return; ?>


METHOD DETAILS
------------------------------------------------------------------------------
<?php foreach($type->methods as $method): ?>

<?= $this->context->renderTitleTag(
	$this->context->createTag($type->name,$method->name,'*'),
	$method->visibility,
	65,
	8,
	'*'
).($method->definedBy != $type->name ? "\n" : "\n>\n"); ?>
<?php if($method->definedBy != $type->name): ?>
 See |<?php echo $this->context->convertNamespace($method->definedBy).'::'.$method->name ?>|
<?php else: ?>
 <?= $this->context->renderMethodSignature($method)."\n<\n"; ?>
<?= $this->context->renderMethodReturnValue($method)."\n"; ?>
<?= empty($method->description) ? '':$this->context->renderDescription($method->description,1)."\n"; ?>
<?php foreach($method->params as $param): ?>
<?= $this->context->renderMethodParam($param,4)."\n" ?>
<?php endforeach; ?>
<?php endif; ?>

<?php endforeach; ?>
