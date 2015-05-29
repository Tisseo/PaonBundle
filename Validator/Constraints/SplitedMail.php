<?php

namespace Tisseo\PaonBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SplitedMail extends Constraint
{
    public $message = 'error.splited_mail';
}
