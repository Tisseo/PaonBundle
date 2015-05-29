<?php

namespace Tisseo\PaonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @Annotation
 */
class UniqueInCollectionValidator extends ConstraintValidator
{
    private $collectionValues = array();

    public function validate($value, Constraint $constraint)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        if($constraint->propertyPath) {
            foreach ($value as $element) {
                $el = $propertyAccessor->getValue($element, $constraint->propertyPath);

                if (in_array($el, $this->collectionValues)) {
                    $this->context->buildViolation($constraint->message)->addViolation();
                    break;
                }

                $this->collectionValues[] = $el;
            }
        }
    }
}
