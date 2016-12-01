<?php

namespace Tisseo\PaonBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use CanalTP\SamEcoreApplicationManagerBundle\SamApplication;

class TisseoPaonBundle extends Bundle implements SamApplication
{
    public function getCanonicalName()
    {
        return 'paon';
    }
}
