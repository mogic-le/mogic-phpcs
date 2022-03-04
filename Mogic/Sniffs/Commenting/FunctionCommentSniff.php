<?php
namespace Mogic\Sniffs\Commenting;

use PHP_CodeSniffer\Standards\PEAR\Sniffs\Commenting\FunctionCommentSniff
    as PHPCS_PEAR_FunctionCommentSniff;
use PHP_CodeSniffer\Files\File;

/**
 * Check if the function comment is correct.
 *
 * This class does not require @return when the function signature
 * has a return type declaration
 *
 * @author Christian Weiske <weiske@mogic.com>
 */
class FunctionCommentSniff
    extends PHPCS_PEAR_FunctionCommentSniff
{
    /**
     * Check if @return or a return type exists.
     *
     * @param File $phpcsFile    The file being scanned.
     * @param int  $stackPtr     The position of the current token
     *                           in the stack passed in $tokens.
     * @param int  $commentStart The position in the stack where the comment
     *                           started.
     */
    public function processReturn(File $phpcsFile, $stackPtr, $commentStart): void
    {
        $props = $phpcsFile->getMethodProperties($stackPtr);
        if ($props['return_type_token'] !== false) {
            //has a return type, this is fine
            return;
        }

        parent::processReturn($phpcsFile, $stackPtr, $commentStart);
    }
}
