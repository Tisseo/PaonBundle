<?php

namespace Tisseo\PaonBundle\Twig\Extension;

class GlobalsExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    private $schematicsDirectory;

    public function __construct($schematicsDirectory)
    {
        $this->schematicsDirectory = $schematicsDirectory;
    }

    public function getGlobals()
    {
        return array(
            'schematics_directory' => $this->schematicsDirectory
        );
    }

    public function getName()
    {
        return 'TisseoPaonBundle:GlobalsExtension';
    }
}
