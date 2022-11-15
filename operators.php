<?php header("X-Clacks-Overhead: GNU Terry Pratchett"); ?>

<?php
	$operators = array(
		'e' => array('label' => 'is', 'op' => '='),
		'ne' => array('label' => 'is not', 'op' => '!='),
		'l' => array('label' => 'is less than', 'op' => '<'),
		'le' => array('label' => 'is less than or equal to', 'op' => '<='),
		'g' => array('label' => 'is greater than', 'op' => '>'),
		'ge' => array('label' => 'is greater than or equal to', 'op' => '>='),
		'start' => array('label' => 'starts with', 'op' => 'like'),
		'end' => array('label' => 'ends with', 'op' => 'like'),
		'c' => array('label' => 'contains', 'op' => 'like'),
	);
?>
