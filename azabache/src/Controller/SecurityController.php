<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Muestra un mensaje de eror si hubiera
        $error = $authenticationUtils->getLastAuthenticationError();
        // Último nombre de usuario introducido
        $lastUsername = $authenticationUtils->getLastUsername();

        // Si ha iniciado sesión
        if ($this->getUser()) {
            // Si el usuario es administrador
            if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                // Reedirige al dashboard de admin
                return $this->redirectToRoute('index');
            // Si el usuario no es administrador
            } else {
                // Reedirige al dashboard común
                return $this->redirectToRoute('index');
            }
        // Si no ha iniciado sesión
        } else {
            // Devuelve la vista de inicio de sesión junto al último nombre de usuario introducido y el error si hubiera
            return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error
            ]);
        }
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        // Si llega a este método de cierre de sesión, muestra un error
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
