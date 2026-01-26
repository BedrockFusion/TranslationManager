<?php

$finder = PhpCsFixer\Finder::create()
	->in(__DIR__ . "/src")
	->name("*.php")
	->ignoreDotFiles(true)
	->ignoreVCS(true);

return (new PhpCsFixer\Config())
	->setRiskyAllowed(false)
	->setRules([
		"@PSR12" => true,
		"array_syntax" => ["syntax" => "short"],
		"binary_operator_spaces" => ["default" => "single_space"],
		"blank_line_after_opening_tag" => true,
		"cast_spaces" => ["space" => "single"],
		"no_trailing_whitespace" => true,
		"no_unused_imports" => true,
		"no_whitespace_in_blank_line" => true,
		"ordered_imports" => ["sort_algorithm" => "alpha"],
		"phpdoc_align" => ["align" => "left"],
		"phpdoc_scalar" => true,
		"phpdoc_separation" => true,
		"phpdoc_trim" => true,
		"single_import_per_statement" => true,
		"single_line_after_imports" => true,
	])
	->setFinder($finder);
