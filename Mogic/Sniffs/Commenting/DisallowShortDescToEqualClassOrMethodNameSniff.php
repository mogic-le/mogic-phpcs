<?php
namespace Mogic\Sniffs\Commenting;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Check if the short description in doc block is the same as the class name or
 * the method name
 *
 * @author Michael Frischbutter <frischbutter@mogic.com>
 */
class Sniffs_Commenting_DisallowShortDescToEqualClassOrMethodNameSniff
    implements Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return void
     */
    public function register()
    {
        return [
            T_CLASS,T_TRAIT,T_INTERFACE,T_DOC_COMMENT_STRING,
            T_DOC_COMMENT_OPEN_TAG,
        ];
    }

    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The current file being checked.
     * @param int  $stackPtr  The position of the current token in the stack
     *                        passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();

        //get class name
        $className = $phpcsFile->findNext(T_STRING, $stackPtr);
        $classNameContent = trim($tokens[$className]['content']);

        $find = [
            T_DOC_COMMENT_OPEN_TAG,
            T_DOC_COMMENT_STRING,
        ];

        $comment = $phpcsFile->findPrevious(
            $find, ($stackPtr - 1), null, false
        );

        //value to verify that the current name belongs to a class
        $isClass = $phpcsFile->findNext(T_CLASS, ($stackPtr + 1), null, false, null, true);
        $isTrait = $phpcsFile->findNext(T_TRAIT, ($stackPtr + 1), null, false, null, true);
        $isInterface = $phpcsFile->findNext(T_INTERFACE, ($stackPtr + 1), null, false, null, true);

        //process the class description to compare it properly
        $docBlockShortDesc
            = strtolower(
                preg_replace('/[^a-z0-9]/i', '', $tokens[$stackPtr]['content'])
            );
        if (substr($docBlockShortDesc, 0, 5) === 'class') {
            $docBlockShortDesc = trim(
                substr_replace(
                    $docBlockShortDesc, '', 0, 5
                )
            );
        }

        if ($tokens[$comment]['type'] === 'T_DOC_COMMENT_OPEN_TAG'
            && $docBlockShortDesc === strtolower($classNameContent)
            && ($tokens[$isClass]['type'] === 'T_CLASS'
            ||$tokens[$isTrait]['type'] === 'T_TRAIT'
            ||$tokens[$isInterface]['type'] === 'T_INTERFACE')
        ) {
            $error = 'Docblock short description is too similar to: "%s"';
            $phpcsFile->addError(
                $error, $stackPtr, 'ShortDescSameAsClassName', [$classNameContent]
            );
        }
    }
}
