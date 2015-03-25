<?php

namespace Tisseo\TidBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'label',
            'text',
            array(
                'label' => 'comment.labels.label',
                'required' => true
            )
        );
        $builder->add(
            'commentText',
            'textarea',
            array(
                'label' => 'comment.labels.comment_text',
                'required' => true
            )
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Tisseo\EndivBundle\Entity\Comment'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tid_comment';
    }
}
