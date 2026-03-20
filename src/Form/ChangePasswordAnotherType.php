<?php
// src/Form/ChangePasswordType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordAnotherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Jelenlegi jelszó',
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank(message: 'Add meg a jelenlegi jelszavadat.'),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options'  => [
                    'label' => 'Új jelszó',
                ],
                'second_options' => [
                    'label' => 'Új jelszó ismét',
                ],
                'invalid_message' => 'A két jelszó nem egyezik.',
                'constraints' => [
                    new Assert\NotBlank(message: 'Add meg az új jelszót.'),
                    new Assert\Length(
                        min: 8,
                        minMessage: 'A jelszónak legalább {{ limit }} karakter hosszúnak kell lennie.'
                    ),
                ],
            ])
        ;
    }
}
