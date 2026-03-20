<?php

// src/Form/ChangePasswordType.php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => '<span class="text-danger">*</span> Jelenlegi jelszó',
                'label_html' => true,
                'mapped' => false,
                'constraints' => [new Assert\NotBlank()],
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => '<span class="text-danger">*</span> Új jelszó',
                'label_html' => true,
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'A jelszó nem lehet üres.',
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Legalább {{ limit }} karakter hosszú jelszó szükséges.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[0-9])(?=.*[\W_]).+$/',
                        'message' => 'A jelszónak tartalmaznia kell legalább 1 számot és 1 speciális karaktert.',
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => '<span class="text-danger">*</span> Új jelszó megerősítése',
                'label_html' => true,
                'mapped' => false,
                'constraints' => [new Assert\NotBlank()],
            ]);
    }
}
