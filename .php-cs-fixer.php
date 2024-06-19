<?php
/**
 * Configuration for php-cs-fixer
 *
 * @link https://github.com/PHP-CS-Fixer/PHP-CS-Fixer
 */
$config = new PhpCsFixer\Config();
return $config->setRules(
    [
        '@PSR12' => true,
        'method_argument_space' => [
            'on_multiline' => 'ignore',
            'keep_multiple_spaces_after_comma' => true,
        ],
        'method_chaining_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
    ]
);
