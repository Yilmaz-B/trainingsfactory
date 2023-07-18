<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\Registration;
use App\Entity\Training;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CostumerController extends AbstractController
{
    #[Route('/customer', name: 'app_customer')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $trainings = $doctrine->getRepository(Training::class)->findAll();

        return $this->render('home/index.html.twig', [
            'trainings' => $trainings,
        ]);
    }
    #[Route('customer/dashoard', name: 'customer_dashboard')]
        public function dashboard(ManagerRegistry $doctrine): Response
        {
            $userId = $this->getUser()->getId();
            $lessons=$doctrine->getRepository(Lesson::class)->findAll();
            return $this->render('costumer/lessons.html.twig', [
                'lessons' => $lessons,
                'users' => $userId
            ]);
        }
    #[Route('customer/lessons', name: 'customer_lesson')]
    public function showLessons(ManagerRegistry $doctrine ): Response
    {
        $userId = $this->getUser()->getId();
        $lessons=$doctrine->getRepository(Lesson::class)->findAll();
        return $this->render('costumer/lessons.html.twig', [
            'lessons' => $lessons,
            'users' => $userId
        ]);
    }
    #[Route('/customer/my-lessons', name: 'customer_my_lesson')]
    public function showMyLessons(ManagerRegistry $doctrine ): Response
    {
        $userId = $this->getUser()->getId();
        $lessonsId=$doctrine->getRepository(Registration::class)->findBy(array("participants"=>$this->getUser()->getId()));

        $lessons = [];
        foreach ($lessonsId as $lessonId){
            $lesson=$doctrine->getRepository(Lesson::class)->findBy(array("id"=>$lessonId->getLesson()->getId()));
//            dd($lesson);
            $lessons[] = $lesson[0];
        }
        return $this->render('costumer/lessons.html.twig', [
            'lessons' => $lessons,
            "myLessons"=>true,
            'users' => $userId
        ]);
    }
    #[Route('customer/registration/{id}', name: 'customer_registration')]
    public function registration(ManagerRegistry $doctrine, $id ): Response
    {
        $lessonId=$doctrine->getRepository(Registration::class)->findBy(array("participants"=>$this->getUser()->getId()));

        foreach ($lessonId as $lesson){
            if($lesson->getLesson()->getId()==$id && $lesson->getParticipants()->getId()==$this->getUser()->getId()){
                $this->addFlash('error', 'You are already registered for this lesson!');
                return $this->redirectToRoute('customer_lesson');
            }
        }
        $registration = new Registration();
        $registration->setParticipants($this->getUser());
        $registration->setLesson($doctrine->getRepository(Lesson::class)->findBy(array("id"=>$id))[0]);
        $doctrine->getManager()->persist($registration);
        $doctrine->getManager()->flush();
        $this->addFlash('success', 'Registration successful!');

        return $this->redirectToRoute('customer_my_lesson');
    }
    #[Route('customer/delete_lessons/{id}', name: 'customer_delete_lesson')]
    public function deleteLesson(ManagerRegistry $doctrine, $id ): Response
    {
        $registration=$doctrine->getRepository(Registration::class)->findBy(array("participants"=>$this->getUser()->getId(),"lesson"=>$id));
        $doctrine->getManager()->remove($registration[0]);
        $doctrine->getManager()->flush();
        $this->addFlash('success', 'Lesson deleted!');
        return $this->redirectToRoute('customer_my_lesson');
    }

    // -----------------PROFILE------------------

    // Read for profile
    #[Route('/customer/profile/{id}', name: 'customer_profile')]
    public function profile(EntityManagerInterface $em, int $id): Response
    {
        $userInfo = $em->getRepository(User::class)->find($id);
        $userId = $this->getUser()->getId();
        return $this->render('costumer/showCustomer.html.twig', [
            'users' => $userId,
            'userInfo' => $userInfo
        ]);
    }

    // Update for profile
    #[Route('/customer/profile/update/{id}', name: 'customer_update_customer')]
    public function updateCustomer(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $userId = $this->getUser()->getId();
        $user = $em->getRepository(User::class)->find($id);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->remove('hiring_date');
        $form->remove('salary');
        $form->remove('social_sec_number');

        $form->remove('agreeTerms');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Profile updated');
            return $this->redirectToRoute('customer_profile', array('id' => $userId));
        }
        return $this->renderForm('costumer/updateCustomer.html.twig', [
            'userForm' => $form,
            'users' => $userId
        ]);
    }
}
