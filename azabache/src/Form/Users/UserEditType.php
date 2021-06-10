<?php
namespace App\Form\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Validator\Constraints\File;

use App\Entity\Rol;
use App\Entity\Locale;

class UserEditType extends AbstractType{

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
            'label_attr' => ['for' => 'name', 'class' => 'control-label mt-1'],
            'attr' => ['class' => 'col-12 form-control']
        ])
        ->add('surname', TextType::class, [
            'label' => 'Apellidos',
            'label_attr' => ['for' => 'surname', 'class' => 'control-label mt-2'],
            'attr' => ['class' => 'col-12 form-control']
        ])
        ->add('email', EmailType::class, [
            'label_attr' => ['for' => 'email', 'class' => 'control-label mt-2'],
            'attr' => ['class' => 'col-12 form-control']
        ])
        ->add('iniciales', TextType::class, [
            'label_attr' => ['for' => 'iniciales', 'class' => 'control-label mt-2'],
            'attr' => ['class' => 'col-12 form-control']
        ])
        ->add('username', TextType::class, [
            'label' => 'Usuario',
            'label_attr' => ['for' => 'username', 'class' => 'control-label mb-1'],
            'attr' => ['class' => 'col-12 form-control', 'minlength' => 5, 'maxlength' => 30, 'required' => true]
        ])
        ->add('newpassword', TextType::class, [
            'label_attr' => ['for' => 'newpassword', 'class' => 'control-label mt-2'],
            'mapped' => false,
            'required' => false,
            'attr' => ['class' => 'col-12 form-control', 'minlength' => 5, 'maxlength' => 30]
        ])              
        ->add('password', HiddenType::class)
        ->add('locale', ChoiceType::class, [
            'label' => 'Idioma',
            'label_attr' => ['for' => 'locale', 'class' => 'form-check-label mt-2'],
            'expanded' => false,    
            'attr' => ['class' => 'col-12 form-control'],                             
            'choices' => 
                $locale_repo                
            ,                     
        ])
        ->add('avatarfile', FileType::class, [
            'label' => 'Avatar (JPG/PNG file)',
            'label_attr' => ['for' => 'avatarfile', 'class' => 'custom-file-label mt-3 mb-1'],
            // unmapped means that this field is not associated to any entity property
            'mapped' => false,
            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,
            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid JPG/PNG document',
                ])
            ],
            'attr' => array(
                'class' => 'custom-file-input'
            )            
        ]);          
        if(in_array('ROLE_ADMIN', $user)){
            $builder->add('roles', ChoiceType::class, [
                'label_attr' => ['for' => 'roles', 'class' => 'form-check-label mt-2'],
                'multiple' => true,
                'attr' => ['class' => 'col-12 form-control multiple'],                    
                'choices' => 
                    $roles_repo                
                ,                     
            ]);
        }else{
            $builder->add('roles', ChoiceType::class, [
                'label_attr' => ['for' => 'roles', 'class' => 'form-check-label mt-2'],
                'multiple' => true,
                'attr' => ['class' => 'col-12 form-control multiple'],   
                'disabled' => true,
                'choices' => $roles_repo,
            ]);
        }

        $builder->add('submit', SubmitType::class, [
            'label' => 'Guardar',
            'attr' => ['class' => 'mt-5 btn btn-lg btn-info btn-block'],
        ]);
    }
}