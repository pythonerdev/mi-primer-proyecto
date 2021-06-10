<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;

class EjemploController extends AbstractController
{

    public function index(): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();            
        return $this->render('ejemplo/index.html.twig', [
            'controller_name' => 'EjemploController',
            'users' => $users
        ]);
    }
}
