<?php

namespace Tisseo\TidBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class SplitedMailValidator extends ConstraintValidator
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
        if ($value !== null) {
            $regexValidator = new RegexValidator();
            $regexValidator->initialize($this->context);

            $values = explode(',', $value);
            foreach ($values as $email) {
                /**
                 * The validation with email's constraint is not yet compatible with swiftMail
                 * so it must to add a regex's constraint.
                 */
                $regexValidator->validate($email,
                    new Regex(array(
                        'pattern' => "/^([\dA-z_\.\-+]+\@[\dA-z\.\+-]+\.[\dA-z\.]+)$/",
                        'message' => 'error.invalid_email'
                    ))
                );
            }
        }
    }
}
