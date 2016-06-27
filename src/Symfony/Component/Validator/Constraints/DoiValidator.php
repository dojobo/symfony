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
 * @see https://en.wikipedia.org/wiki/Digital_object_identifier
 */
class DoiValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Doi) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Doi');
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = (string) $value;
        $canonical = $value;

        // DOI can be expressed with preceding doi: or as part of a URL
        if (substr($canonical, 0, 4) === "doi:") {
            $canonical = substr($canonical, 4);
        } elseif (substr($canonical, 0, 18) === "http://dx.doi.org/") {
            $canonical = substr($canonical, 18);
        }

        // 10.1000/182
        // ^^^ must start with these three characters
        if (substr($canonical, 0, 3) !== "10.") {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Doi::DOI_REGISTRY_REQUIRED_ERROR)
                ->addViolation();

            return;
        }

        // 10.1000/182
        //        ^ everything before first slash is prefix
        //          everything after it (excluding the slash) is the suffix
        $slashPosition = strpos($canonical, '/');

        // strpos() returns false if not found:
        if ($slashPosition == false) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Doi::SLASH_REQUIRED_ERROR)
                ->addViolation();

            return;
        }

        $prefix = substr($canonical, 0, $slashPosition);
        $suffix = substr($canonical, $slashPosition + 1);

        // prefix MUST be at least 4 characters (though probably it is at least 7)
        if (strlen($prefix) < 4) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Doi::TOO_SHORT_ERROR)
                ->addViolation();

            return;
        }

        if (strlen($suffix) < 1) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Doi::TOO_SHORT_ERROR)
                ->addViolation();

            return;
        }
    }
}
