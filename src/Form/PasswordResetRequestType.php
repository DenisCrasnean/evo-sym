<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class PasswordResetRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('e-mail', EmailType::class, [
                'invalid_message' => 'The e-mail provided is not valid.',
                'required' => true,
            ])
            ->add('reset_password', SubmitType::class);
    }
}
