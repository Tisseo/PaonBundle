<?php

namespace Tisseo\DatawarehouseBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * HexaColorValidator
 */
class HexaColorValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constrain for the validation
     *
     * @return Boolean Whether or not the value is valid
     */
    public function validate($value, Constraint $constraint)
    {
        if (!preg_match('/^#[a-zA-Za0-9]{6}$/', $value, $matches))
        {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }
    }
}
