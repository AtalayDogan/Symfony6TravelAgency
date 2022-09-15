<?php

namespace App\Form;

use App\Entity\Message;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[

                'attr'=> ['autofocus'=>true, 'class'=>'form-control p-4']
            ])
            ->add('email',EmailType::class,[

                'attr'=> ['autofocus'=>true, 'class'=>'form-control p-4']
            ])
            ->add('phone',TextType::class,[

                'attr'=> ['autofocus'=>true, 'class'=>'form-control p-4']
            ])
            ->add('subject',TextType::class,[

                'attr'=> ['autofocus'=>true, 'class'=>'form-control p-4']
            ])
            ->add('message',TextareaType::class,[

                'attr'=> ['autofocus'=>true, 'class'=>'form-control py-3 px-4']
            ])
            ->add('GONDER',SubmitType::class,[

                'attr'=> ['autofocus'=>true, 'class'=>'btn btn-primary py-3 px-4']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'contactform',
        ]);
    }
}
