<?php

namespace Tisseo\PaonBundle\Listener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Tisseo\EndivBundle\Entity\Schematic;

class SchematicUploadListener
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Schematic) {
            return;
        }

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Schematic) {
            return;
        }

        $this->uploadFile($entity);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Schematic) {
            return;
        }

        $fileName = $entity->getFile();

        if ($fileName !== null) {
            $entity->setFile(new File($this->targetDir.'/'.$fileName));
        }
    }

    private function uploadFile(Schematic $schematic)
    {
        if (!$schematic instanceof Schematic) {
            return;
        }

        $file = $schematic->getFile();

        // only upload new files
        if (!$file instanceof UploadedFile) {
            return;
        }

        $fileName = $this->upload($schematic);

        $schematic->setFile($fileName);
    }

    private function upload(Schematic $schematic)
    {
        $file = $schematic->getFile();

        $fileName = $schematic->getLine()->getNumber() .
        '_' . date_format($schematic->getDate(), 'Ymd') .
        '_' . sha1(uniqid(mt_rand(), true)) .
        '.' . $file->guessExtension();

        $file->move($this->targetDir, $fileName);

        return $fileName;
    }
}
