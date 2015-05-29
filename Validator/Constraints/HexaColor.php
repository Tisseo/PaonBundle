<?php

namespace Tisseo\PaonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class HexaColor extends Constraint
{
    public $message = 'error.hexa_color';
}
