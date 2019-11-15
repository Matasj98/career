<?php


namespace App\Form;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName')
            ->add('lastName')
            ->add('roles', ChoiceType::class, [
                'choices' => ['Administratorius' => 'ROLE_ADMIN',
                    'Vadovas' => 'ROLE_BOSS',
                    'Darbuotojas' => 'ROLE_USER',],
                'multiple' => true,
                'expanded' => true])
            ->add('profession')
            ->remove('username');
    }

    public function getParent()
    {
        return BaseRegistrationFormType::class;
    }
}