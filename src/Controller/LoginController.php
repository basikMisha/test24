<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    #[Route('/redirect', name: 'app_login_redirect')]
    public function redirectLogin(Security $security): Response
    {
        if($security->isGranted('ROLE_ADMIN')) {

            return $this->redirectToRoute('app_claims_index');

        } elseif($security->isGranted('ROLE_MANAGER')) {

            return $this->redirectToRoute('app_manager_claims_index');

        } else {

            return $this->redirectToRoute('app_user_claims_index');

        }
    }
}
