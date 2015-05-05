<?php

namespace Tisseo\TidBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;
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
        //$field = $this->context->getRoot()->get($constraint->field);
        if ($value !== null) {

            $emailValidator = new EmailValidator();
            $emailValidator->initialize($this->context);

            $values = explode(',', $value);
            foreach ($values as $email) {
                $emailValidator->validate($email,
                    new Email(array('strict' => true))
                );
            }
        }
    }
}
