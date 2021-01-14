<?php
namespace Mogic\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use PHP_CodeSniffer\Standards\PEAR\Sniffs\Commenting\FunctionCommentSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Commenting\DocCommentSniff;

/**
 * Check the length of the function and whether it has a doc
 *
 * DisallowDocOfShortFunctionSniff
 *
 * @author Paula Schwalm <schwalm@mogic.com>
 */
class Sniffs_DisallowDocOfShortFunctionSniff extends FunctionCommentSniff
{
    /**
     * Allowed values are public, protected, and private.
     *
     * @var string
     */
    public $minimumVisibility = 'private';

    public int $maxLength = 3;

    /**
     * Returns an array of tokens
     *
     * @return void
     */
    public function register()
    {
        return [
            T_FUNCTION,
        ];
    }

    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The current file being checked.
     * @param int  $stackPtr  The stackPtr of the current token in the stack
     *                        passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $scopeModifier = $phpcsFile->getMethodProperties($stackPtr)['scope'];
        if ($scopeModifier === 'protected'
            && $this->minimumVisibility === 'public'
            || $scopeModifier === 'private'
            && ($this->minimumVisibility === 'public'
            || $this->minimumVisibility === 'protected')
        ) {
            return;
        }

        $tokens   = $phpcsFile->getTokens();
        $ignore   = Tokens::$methodPrefixes;
        $ignore[] = T_WHITESPACE;

        $commentEnd = $phpcsFile->findPrevious(
            $ignore, ($stackPtr - 1), null, true
        );
        if ($tokens[$commentEnd]['code'] === T_COMMENT) {
            // Inline comments might just be closing comments for
            // control structures or functions instead of function comments
            // using the wrong comment type. If there is other code on the line,
            // assume they relate to that code.
            $prev = $phpcsFile->findPrevious(
                $ignore, ($commentEnd - 1), null, true
            );
            if ($prev !== false
                && $tokens[$prev]['line'] === $tokens[$commentEnd]['line']
            ) {
                $commentEnd = $prev;
            }
        }

        $token = $tokens[$stackPtr];

        if (!isset($token['scope_opener'])) {
            return 0;
        }

        $firstToken = $tokens[$token['scope_opener']];
        $lastToken = $tokens[$token['scope_closer']];
        $length = $lastToken['line'] - $firstToken['line'];

        $commentEnd = $phpcsFile->findPrevious(
            $ignore, ($stackPtr - 1), null, true
        );
        if ($tokens[$commentEnd]['code'] === T_COMMENT) {
            // Inline comments might just be closing comments for
            // control structures or functions instead of function comments
            // using the wrong comment type. If there is other code on the line,
            // assume they relate to that code.
            $prev = $phpcsFile->findPrevious(
                $ignore, ($commentEnd - 1), null, true
            );
            if ($prev !== false
                && $tokens[$prev]['line'] === $tokens[$commentEnd]['line']
            ) {
                $commentEnd = $prev;
            }
        }

        $methodName = $phpcsFile->getDeclarationName($stackPtr);
        $isShortFunction  = ($length < $this->maxLength &&
        ($methodName !== '__construct' && $methodName !== '__destruct'));

        if (!$isShortFunction) {
            $docCommentSniff = new DocCommentSniff;
            $docCommentSniff->process($phpcsFile, $stackPtr);
        }

        if ($tokens[$commentEnd]['code'] !== T_DOC_COMMENT_CLOSE_TAG
            && $tokens[$commentEnd]['code'] !== T_COMMENT
        ) {
            if (!$isShortFunction) {

                $function = $phpcsFile->getDeclarationName($stackPtr);
                $phpcsFile->addError(
                    'Missing doc comment for function %s()',
                    $stackPtr,
                    'Missing',
                    [$function]
                );
                $phpcsFile->recordMetric(
                    $stackPtr, 'Function has doc comment', 'no'
                );
                return;
            }

            $phpcsFile->recordMetric(
                $stackPtr, 'Function has doc comment', 'no'
            );
            return;
        } else {
            if ($isShortFunction) {
                $function = $phpcsFile->getDeclarationName($stackPtr);
                $phpcsFile->addError(
                    'Function %s() does not require a doc comment',
                    $stackPtr,
                    'Missing',
                    [$function]
                );
                $phpcsFile->recordMetric(
                    $stackPtr, 'Function has doc comment', 'yes'
                );
                return;
            }
            $phpcsFile->recordMetric(
                $stackPtr, 'Function has doc comment', 'yes'
            );
        }

        if ($tokens[$commentEnd]['code'] === T_COMMENT) {
            $phpcsFile->addError(
                'You must use "/**" style comments for a function comment',
                $stackPtr, 'WrongStyle'
            );
            return;
        }

        if ($tokens[$commentEnd]['line'] !== ($tokens[$stackPtr]['line'] - 1)) {
            $error = 'There must be no blank lines after the function comment';
            $phpcsFile->addError($error, $commentEnd, 'SpacingAfter');
        }

        $commentStart = $tokens[$commentEnd]['comment_opener'];
        foreach ($tokens[$commentStart]['comment_tags'] as $tag) {
            if ($tokens[$tag]['content'] === '@see') {
                // Make sure the tag isn't empty.
                $string = $phpcsFile->findNext(
                    T_DOC_COMMENT_STRING, $tag, $commentEnd
                );
                if ($string === false
                    || $tokens[$string]['line'] !== $tokens[$tag]['line']
                ) {
                    $error = 'Content missing for @see tag in function comment';
                    $phpcsFile->addError($error, $tag, 'EmptySees');
                }
            }
        }

        parent::processReturn($phpcsFile, $stackPtr, $commentStart);
        parent::processThrows($phpcsFile, $stackPtr, $commentStart);
        parent::processParams($phpcsFile, $stackPtr, $commentStart);
    }
}
