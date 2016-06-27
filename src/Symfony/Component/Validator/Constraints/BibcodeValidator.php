<?php

namespace Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validates whether the value is a valid bibcode.
 *
 * @author Dominic Bordelon <dominicbordelon@gmail.com
 *
 * @see https://en.wikipedia.org/wiki/Bibcode
 */
class BibcodeValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Bibcode) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Bibcode');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;
        
        $length = strlen($value);

        if ($length < 19) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Bibcode::TOO_SHORT_ERROR)
                ->addViolation();

            return;
        }

        if ($length > 19) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Bibcode::TOO_LONG_ERROR)
                ->addViolation();

            return;
        }

        // YYYYJJJJJVVVVMPPPPA
        $year = substr($value, 0, 4);
        $journal = substr($value, 4, 5);
        $volume = substr($value, 9, 4);
        $mColumn = $value{13};
        $page = substr($value, 14, 4);
        $author = $value{18};

        // 1970ApJ...161L..77K
        // ^^^^ digits only
        if (!ctype_digit($year)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Bibcode::INVALID_CHARACTERS_ERROR)
                ->addViolation();

            return;
        }

        // 1970ApJ...161L..77K
        //     ^^^^^ letters, with periods for padding
        if (!preg_match("/^[a-zA-Z]+\.*/", $journal)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Bibcode::INVALID_CHARACTERS_ERROR)
                ->addViolation();

            return;
        }

        // 1970ApJ...161L..77K
        // 2016SoPh..tmp..106M
        //          ^^^^ padding + volume number or 'tmp'
        if (!preg_match("/\.*(\d+|tmp)/", $volume)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Bibcode::INVALID_CHARACTERS_ERROR)
                ->addViolation();

            return;
        }

        // 1970ApJ...161L..77K
        //              ^ letter or number or padding dot
        if (!preg_match("/\d|[a-zA-Z]|\./", $mColumn)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Bibcode::INVALID_CHARACTERS_ERROR)
                ->addViolation();

            return;
        }

        // 1970ApJ...161L..77K
        //               ^^^^ padding + numbers
        if (!preg_match("/\.*\d*/", $page)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Bibcode::INVALID_CHARACTERS_ERROR)
                ->addViolation();

            return;
        }

        // 1970ApJ...161L..77K
        //                   ^ letter or dot
        if (!preg_match("/[a-zA-Z]|\./", $author)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Bibcode::INVALID_CHARACTERS_ERROR)
                ->addViolation();

            return;
        }
    }
}
