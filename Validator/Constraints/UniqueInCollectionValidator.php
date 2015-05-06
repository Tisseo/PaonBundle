<?php

namespace Tisseo\TidBundle\Validator\Constraints;

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

        if( $constraint->propertyPath ) {
            $propertyAccessor = PropertyAccess::createPropertyAccessor();
            $value = $propertyAccessor->getValue($value, $constraint->propertyPath);
        }

        if( in_array($value, $this->collectionValues)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', $value)
                ->addViolation();
        }

        $this->collectionValues[] = $value;
    }
}
