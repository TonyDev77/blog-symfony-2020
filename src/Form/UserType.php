<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    // Nota. Dê uma olhada no App 'Notas' (do mac). Lá tem explicado como foi criado essa classe.
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name')
            ->add('last_name')
            ->add('email', EmailType::class)
            ->add('username')
            ->add('password', PasswordType::class)
            // Os dados abaixo não precisam aparecer na view, pois serão automáticos
//            ->add('created_at')
//            ->add('updated_at')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class, // Esse cara faz um 'bind'(vínculo) do formulário c/ a entidade
        ]);
    }
}
