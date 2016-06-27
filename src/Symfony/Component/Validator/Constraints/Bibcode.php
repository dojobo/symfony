<?php

namespace Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Dominic Bordelon <dominicbordelon@gmail.com>
 */
class Bibcode extends Constraint
{
    const TOO_SHORT_ERROR = '106403bb-7f22-4343-a984-bbdfed600748';
    const TOO_LONG_ERROR = '205c95ed-ef57-471a-9ca0-12bfdaaa3ae4';
    const INVALID_CHARACTERS_ERROR = '0dd2bb08-c072-4260-be20-73d725ea4482';

    protected static $errorNames = array(
        self::TOO_SHORT_ERROR => 'TOO_SHORT_ERROR',
        self::TOO_LONG_ERROR => 'TOO_LONG_ERROR',
        self::INVALID_CHARACTERS_ERROR => 'INVALID_CHARACTERS_ERROR',
    );

    public $message = 'This is not a valid bibcode.';
}
