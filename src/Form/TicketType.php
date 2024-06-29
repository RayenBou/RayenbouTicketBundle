<?php

namespace Rayenbou\TicketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['title']) {
            $builder
                ->add('title', TextType::class, [
                    'label' => 'Title',
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'This field cannot be empty.',
                        ]),
                        new Length([
                            'min' => 5,
                            'maxMessage' => 'This field cannot exceed {{ limit }} characters.',
                        ]),
                    ],
                ]);
        }
        $builder->add('description', TextType::class, [
            'label' => 'message',
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'This field cannot be empty.',
                ]),
                new Length([
                    'min' => 5,
                    'maxMessage' => 'This field cannot exceed {{ limit }} characters',
                ]),
            ],

        ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'title' => true,
        ]);
    }
}
