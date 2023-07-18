<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_dashboard');
        }
        if ($security->isGranted('ROLE_CUSTOMER')) {
            return $this->redirectToRoute('customer_dashboard');
        }
        if ($security->isGranted('ROLE_INSTRUCTOR')) {
            return $this->redirectToRoute('instructor_dashboard');
        }
        return $this->render('home/index.html.twig', [
        ]);
    }
}
