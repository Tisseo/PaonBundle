<?php

namespace Tisseo\PaonBundle\Permission;

use CanalTP\SamEcoreApplicationManagerBundle\Permission\AbstractBusinessPermissionModule;

class BusinessPermissionModule extends AbstractBusinessPermissionModule
{
    public function getName()
    {
        return 'paon_business_module';
    }
}
