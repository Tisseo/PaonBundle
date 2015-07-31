<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tisseo\PaonBundle\Validator\Constraints\UniqueInCollection;

use Tisseo\EndivBundle\Entity\Line;
use Tisseo\EndivBundle\Entity\LineGroupGis;
use Tisseo\EndivBundle\Entity\LineGroupGisContent;
use Tisseo\PaonBundle\Form\Type\LineGroupGisContentType;

class LineGroupGisType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('name', 'text', array(
            'label' => 'line_group_gis.labels.group_name',
        ));

        $builder->add('nbBus', 'text', array(
            'label' => 'line_group_gis.labels.nb_bus'
        ));

        $builder->add('comment', 'textarea', array(
            'label' => 'line_group_gis.labels.comment',
        ));

        $builder->add('name', 'text', array(
            'label' => 'line_group_gis.labels.group_name',
        ));

        $builder->add('LineGroupGisContents', 'collection', array(
            'type' => new LineGroupGisContentType(),
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'options' => array('em' => $options['em']),
            'constraints' => new UniqueInCollection('line')
        ));


        $builder->setAction($options['action']);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Tisseo\EndivBundle\Entity\LineGroupGis',
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
        return 'paon_line_group_gis';
    }
}
