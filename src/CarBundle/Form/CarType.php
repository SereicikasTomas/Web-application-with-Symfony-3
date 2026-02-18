<?php

namespace CarBundle\Form;

use CarBundle\Entity\Car;
use CarBundle\Entity\Make;
use CarBundle\Entity\Model;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('price', TextType::class, [
                'required' => true,
                'constraints' =>
                [
                    new NotBlank()
                ]
            ])
            ->add('year', TextType::class, [
                'required' => true,
                'constraints' =>
                [
                    new NotBlank()
                ]
            ])
            ->add('navigation')
            ->add('description')
            ->add('model', EntityType::class, [
                'required' => true,
                'class' => Model::class
            ])
            ->add('make', EntityType::class, [
                'required' => true,
                'class' => Make::class
            ])
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'carbundle_car';
    }


}
