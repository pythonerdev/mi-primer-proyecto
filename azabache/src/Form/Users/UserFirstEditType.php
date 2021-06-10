<?php
namespace App\Form\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserFirstEditType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, [
            'label_attr' => ['for' => 'username', 'class' => 'control-label mb-1'],
            'label' => 'New username',
            'attr' => ['class' => 'col-12 form-control', 'minlength' => 5, 'maxlength' => 30, 'required' => true]
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Send new data',
            'attr' => ['class' => 'mt-3 btn btn-lg btn-success btn-block', 'disabled' => true],
        ]);
    }
}