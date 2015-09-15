<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tisseo\PaonBundle\Validator\Constraints\UniqueInCollection;
use Tisseo\PaonBundle\Form\Type\LineGroupGisContentType;

class LineGroupGisType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                array(
                    'label' => 'tisseo.paon.line_group_gis.label.group_name'
                )
            )
            ->add(
                'nbBus',
                'integer',
                array(
                    'label' => 'tisseo.paon.line_group_gis.label.nb_bus'
                )
            )
            ->add(
                'comment',
                'textarea',
                array(
                    'label' => 'tisseo.paon.line_group_gis.label.comment'
                )
            )
            ->add(
                'name',
                'text',
                array(
                    'label' => 'tisseo.paon.line_group_gis.label.group_name'
                )
            )
            ->add(
                'LineGroupGisContents',
                'collection',
                array(
                    'type' => new LineGroupGisContentType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'options' => array('em' => $options['em']),
                    'constraints' => new UniqueInCollection('line')
                )
            )
            ->add(
                'add',
                'button'
            )
            ->setAction($options['action'])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
                array(
                    'data_class' => 'Tisseo\EndivBundle\Entity\LineGroupGis',
                    'attr' => array(
                        'class' => 'form-with-collection'
                    )
                )
            )
            ->setRequired(
                array('em')
            )
            ->setAllowedTypes(
                array('em' => 'Doctrine\Common\Persistence\ObjectManager')
            )
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'paon_line_group_gis';
    }
}
