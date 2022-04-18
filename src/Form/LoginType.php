<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('_username', EmailType::class, [
                'attr' => [
                    'label' => 'E-mail',
                ],
            ])
            ->add('_password', PasswordType::class, [
                'attr' => [
                    'label' => 'Password',
                ],
            ])
            ->add('_remember_me', CheckboxType::class, [
                'required' => false,
                ])
            ->add('login', SubmitType::class);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'theme' => 'tailwind_3_login_form.html.twig',
            ]);
    }
}
