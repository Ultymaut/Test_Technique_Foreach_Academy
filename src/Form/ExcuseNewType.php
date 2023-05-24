<?php

namespace App\Form;

use App\Entity\Excuse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;

class ExcuseNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('http_code',IntegerType::class, [
        'attr' => [
            'class' => 'form-control'
        ],
        'label' => 'HTTP_code',
        'label_attr' => [
            'class' => 'form-label mt-4'
        ],
        'constraints' => [
            new Assert\NotBlank(),
            new Assert\NotNull()
        ]
    ])
            ->add('tag', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Tag',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\NotNull()
                ]
            ])
            ->add('message', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Message',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\NotNull()
                ]
            ])
            ->add('submit' , SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-primary mt-4'
                ],
                'label' => 'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Excuse::class,
        ]);
    }
}
