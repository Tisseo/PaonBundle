<?php

namespace Tisseo\PaonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueInCollection extends Constraint
{
    public $message = 'error.unique_in_collection';

    public $propertyPath;

    public function __construct($propertyPath = null)
    {
        $this->propertyPath = $propertyPath;
    }
}
