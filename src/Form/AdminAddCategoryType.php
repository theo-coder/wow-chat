<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminAddCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom de la catégorie'
            ])
            ->add('authorized_roles', ChoiceType::class, [
                'required' => false,
                'label' => 'Rôle autorisé à voir la catégorie',
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    'ROLE_USER' => 'Utilisateur',
                    'ROLE_INSIDER' => 'Insider',
                    'ROLE_EXTERNAL' => 'Externe',
                    'ROLE_COLLABORATOR' => 'Collaborateur',
                    'ROLE_ADMIN' => 'Administrateur'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
