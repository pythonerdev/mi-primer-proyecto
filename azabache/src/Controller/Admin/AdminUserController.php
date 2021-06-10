<?php

namespace App\Controller\Admin;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use App\Entity\User;
use App\Form\Users\UserCreateType;
use App\Form\Users\UserEditType;

class AdminUserController extends AbstractController
{  
    private $passwordEncoder;
    private $security;
    private $session;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, Security $security, SessionInterface $session)
    {
        $this->passwordEncoder = $passwordEncoder;    
        $this->security = $security;   
        $this->session = $session; 
    }

    public function index()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('admin_user/index.html.twig', [
            'users' => $users,
        ]);
    }
    
    public function createUser(Request $request){
        $user = new User();
        $form = $this->createForm(UserCreateType::class, $user);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user_new = new User();
            $user_new = $user;

            $exist_username = $this->getDoctrine()->getRepository(User::class)->findBy(['username' => $user->getUsername()]);

            if(empty($exist_username) || $exist_username == null){
                $user_new->setRoles($user->getRoles());
                $user_new->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
    
                $em = $this->getDoctrine()->getManager();
                $em->persist($user_new);
                $em->flush();
    
                $session = new Session();
                $session->getFlashBag()->add('message', 'User created');

                return $this->redirect($request->request->get('referer'));
            }else{
                $session = new Session();
                $session->getFlashBag()->add('message', 'Username already in use');
            }
        }
        
        return $this->render('admin_user/user_create.html.twig', [
            'user_create' => $form->createView()
        ]);
    }
    
    public function editUser(Request $request, User $user){

        $form = $this->createForm(UserEditType::class, $user);

        $username_old = $user->getUsername();
        $form->handleRequest($request);
        $username_new = $user->getUsername();

        if($form->isSubmitted() && $form->isValid()){
            $user_new = new User();
            $user_new = $user;
            if($username_old != $username_new){
                $exist_username = $this->getDoctrine()->getRepository(User::class)->findBy(['username' => $user->getUsername()]);
                if(empty($exist_username) || $exist_username == null){
                    $user_new->setRoles($user->getRoles());
                    
                    if($form->get('newpassword')->getData() != ''){
                        $user_new->setPassword($this->passwordEncoder->encodePassword($user, $form->get('newpassword')->getData()));
                    }else{
                        $user_new->setPassword($user->getPassword());
                    }

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user_new);
                    $em->flush();

                    $session = new Session();
                    $session->getFlashBag()->add('message', 'Edited user');
                    return $this->redirect($request->request->get('referer'));
                }else{
                    $session = new Session();
                    $session->getFlashBag()->add('message', 'Username already in use');
                }
            }else{
                $user_new->setRoles($user->getRoles());
                
                if($form->get('newpassword')->getData() != ''){
                    $user_new->setPassword($this->passwordEncoder->encodePassword($user, $form->get('newpassword')->getData()));
                }else{
                    $user_new->setPassword($user->getPassword());
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($user_new);
                $em->flush();

                $session = new Session();
                $session->getFlashBag()->add('message', 'Edited user');
                return $this->redirect($request->request->get('referer'));
            }
        }
        
        return $this->render('admin_user/user_edit.html.twig', [
            'user_edit' => $form->createView()
        ]);
    }
    public function editAccount(Request $request, SluggerInterface $slugger){
        $user_active = $this->security->getUser();
        $form = $this->createForm(UserEditType::class, $user_active);
        $fileold = $user_active->getavatarFilename();      
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $avatarFile = $form->get('avatarfile')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();
    
                // Move the file to the directory where brochures are stored
                try {
                    $avatarFile->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
    
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user_active->setavatarFilename($newFilename);
            }           


            $user_new = new User();
            $user_new = $user_active;
            
            if($form->get('newpassword')->getData() != ''){
                 $user_new->setPassword($this->passwordEncoder->encodePassword($user_active, $form->get('newpassword')->getData()));
            }else{
                 $user_new->setPassword($user_active->getPassword());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user_new);
            $em->flush();
            if($fileold){
                $filesystem = new Filesystem(); 
                $filesystem->remove([$this->getParameter('avatar_directory').'/'.$fileold]);  
            }
            $this->session->set('_locale', $user_new->getLocale());

            $session = new Session();
            $session->getFlashBag()->add('message', 'Edited user');
            return $this->redirect($request->request->get('referer'));
        }
        
        return $this->render('account_user/account_edit.html.twig', [
            'user_edit' => $form->createView()
        ]);
    }    

    public function activedeactiveUser(Request $request, User $user){
        
        $em = $this->getDoctrine()->getManager();

        if($user->getActive() == 0){
            $user->setActive(1);
        }else{
            $user->setActive(0);
        }

        $em->persist($user);
        $em->flush();
        
        return $this->redirectToRoute('admin_users');
    }     

}
