<?php

namespace Mekit\Bundle\DomainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DomainType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('provider', 'url')
            ->add('expiration')

            //referenceable elements (reference selector fields)
            ->add('referenceableElement',
                'mekit_referenceable_element',
                [
                    'label' => false,
                    'required' => false
                ]
            )
        ;

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mekit\Bundle\DomainBundle\Entity\Domain'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mekit_domain';
    }
}
