<?php

namespace Tisseo\TidBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Tisseo\TidBundle\Form\DataTransformer\EntityToIntTransformer;

class LineGroupGisContentType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*$entityTransformer = new EntityToIntTransformer($options["em"]);
        $entityTransformer->setEntityClass("Tisseo\\EndivBundle\\Entity\\LineGroupGis");
        $entityTransformer->setEntityRepository("TisseoEndivBundle:LineGroupGis");
        $entityTransformer->setEntityType("lineGroupGis");*/

        $builder->add('line','entity', array(
            'label' => 'line_group_gis.labels.line',
            'property' => 'number',
            'em' => $options['em'],
            'class' => 'Tisseo\EndivBundle\Entity\Line',
        ));

        /*$builder->add(
            $builder->create(
                'lineGroupGis',
                'hidden'
            )->addModelTransformer($entityTransformer)
        );*/

        $builder->setAction($options['action']);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Tisseo\EndivBundle\Entity\LineGroupGisContent',
            )
        );

        $resolver->setRequired(array(
            'em'
        ));

        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));

    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tid_line_group_gis_content';
    }
}