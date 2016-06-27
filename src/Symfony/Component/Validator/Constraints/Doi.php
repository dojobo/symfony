<?php

namespace Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Dominic Bordelon <dominicbordelon@gmail.com>
 */
class Doi extends Constraint
{
    const TOO_SHORT_ERROR = 'e825b3db-99d1-4d7a-907f-102cf4a935ba';
    const INVALID_CHARACTERS_ERROR = '8d161ab0-1331-4a81-8a11-2dea140d39c9';
    const SLASH_REQUIRED_ERROR = '0e8c498f-7b18-4de4-bc7c-f297a0f345d8';
    const DOI_REGISTRY_REQUIRED_ERROR = 'b638348b-1d8e-43dd-850c-9763062e0ab8';

    protected static $errorNames = array(
        self::INVALID_CHARACTERS_ERROR => 'INVALID_CHARACTERS_ERROR',
        self::TOO_SHORT_ERROR => 'TOO_SHORT_ERROR',
        self::SLASH_REQUIRED_ERROR => 'SLASH_REQUIRED_ERROR',
        self::DOI_REGISTRY_REQUIRED_ERROR => 'DOI_REGISTRY_REQUIRED_ERROR'
    );

    public $message = 'This is not a valid DOI.';
}
