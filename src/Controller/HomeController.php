<?php

namespace App\Controller;

use App\Entity\Training;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Security $security, ManagerRegistry $doctrine): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin');
        }
        if ($security->isGranted('ROLE_CUSTOMER')) {
            return $this->redirectToRoute('app_customer');
        }
        if ($security->isGranted('ROLE_INSTRUCTOR')) {
            return $this->redirectToRoute('app_instructor');
        }

        $trainings = $doctrine->getRepository(Training::class)->findAll();

        return $this->render('home/index.html.twig', [
            'trainings' => $trainings,
        ]);
    }

}
