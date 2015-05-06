<?php

namespace Tisseo\TidBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueInCollection extends Constraint
{
    public $message = 'error.unique_in_collection (%string%)';

    public $propertyPath;

    public function __construct($propertyPath = null)
    {
        $this->propertyPath = $propertyPath;
    }
}
