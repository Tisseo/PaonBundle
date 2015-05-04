<?php

namespace Tisseo\TidBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LineSchemaType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name','text', array(
            'label' => 'Name',
            'read_only' => true,
        ));

        $builder->add('comment','textarea', array(
            'label' => 'Commentaire'
        ));

        $builder->add('file','file', array(
            'label' => 'Fichier',
            'required' => false
        ));

        $builder->add('save', 'submit', array(
            'attr' => array('class' => 'btn btn-success'),
            'label' => 'Déposer un fichier'
        ));

        $builder->setAction($options['action']);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Tisseo\EndivBundle\Entity\Schematic',
                'attr' => array(
                    'enctype' => 'multipart/form-data'
                )
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tid_line_schema';
    }
}
