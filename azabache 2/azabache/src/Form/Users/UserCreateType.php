<?php
namespace App\Form\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Security\Core\Security;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Rol;
use App\Entity\Locale;

class UserCreateType extends AbstractType{

    /** @var EntityManagerInterface */
    private $em;
    private $security;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->security->getUser()->getRoles();

        $roles = array();
        $roles_repo = array();
        $rolRepository = $this->em->getRepository(Rol::class);
        $roles = $rolRepository->findAll();

        $locale = array();
        $locale_repo = array();
        $localeRepository = $this->em->getRepository(Locale::class);
        $locale = $localeRepository->findAll();        

        foreach($roles as $rol){
            $roles_repo[$rol->getDescription()] = $rol->getType();
        }
        foreach($locale as $language){
            $locale_repo[$language->getDescription()] = $language->getType();
        }

        $builder->add('name', TextType::class, [
            'label' => 'Nombre',
            'label_attr' => ['for' => 'name', 'class' => 'control-label mb-1'],
            'attr' => ['class' => 'col-12 form-control']
        ])
        ->add('surname', TextType::class, [
            'label' => 'Apellidos',
            'label_attr' => ['for' => 'surname', 'class' => 'control-label mb-1'],
            'attr' => ['class' => 'col-12 form-control']
        ])
        ->add('email', EmailType::class, [
            'label_attr' => ['for' => 'email', 'class' => 'control-label mb-1'],
            'attr' => ['class' => 'col-12 form-control']
        ])
        ->add('iniciales', TextType::class, [
            'label_attr' => ['for' => 'dni', 'class' => 'control-label mb-1'],
            'attr' => ['class' => 'col-12 form-control']
        ])
        ->add('username', TextType::class, [
            'label' => 'Usuario',
            'label_attr' => ['for' => 'username', 'class' => 'control-label mb-1'],
            'attr' => ['class' => 'col-12 form-control']
        ])       
        ->add('password', TextType::class, [
            'label_attr' => ['for' => 'password', 'class' => 'control-label mb-1'],
            'attr' => ['class' => 'col-12 form-control']
        ])
        ->add('locale', ChoiceType::class, [
            'label' => 'Idioma',
            'label_attr' => ['for' => 'locale', 'class' => 'form-check-label mt-2'],
            'expanded' => false,    
            'attr' => ['class' => 'col-12 form-control'],                             
            'choices' => 
                $locale_repo                
            ,                     
        ])
        ->add('roles', ChoiceType::class, [
            'multiple' => true,
            'attr' => ['class' => 'col-12 form-control multiple'],            
            'choices' => [
                $roles_repo
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Guardar',
            'attr' => ['class' => 'mt-5 btn btn-lg btn-info btn-block'],
        ]);
    }
}