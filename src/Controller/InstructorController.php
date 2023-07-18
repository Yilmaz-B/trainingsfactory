<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\Training;
use App\Entity\User;
use App\Form\LessonType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InstructorController extends AbstractController
{

    // -----------------GENERAL------------------

    #[Route('/instructor', name: 'app_instructor')]
    public function index( ManagerRegistry $doctrine): Response
    {
        $trainings = $doctrine->getRepository(Training::class)->findAll();

        return $this->render('home/index.html.twig', [
            'trainings' => $trainings,
        ]);
    }

    #[Route('/instructor/dashboard', name: 'instructor_dashboard')]
    public function dasboard(ManagerRegistry $doctrine): Response
    {
        $userId = $this->getUser()->getId();
        $lessons=$doctrine->getRepository(Lesson::class)->findBy(array("instructor"=>$this->getUser()->getId()));
//        dd($lessons);
        return $this->render('instructor/lesson.html.twig', [
            'lessons' => $lessons,
            'users' => $userId
        ]);
    }

    // -----------------LESSONS------------------

    #[Route('/instructor/lessons', name: 'instructor_lesson')]
    public function showLessons(ManagerRegistry $doctrine, LoggerInterface $logger): Response
    {
        $lessons=$doctrine->getRepository(Lesson::class)->findBy(array("instructor"=>$this->getUser()->getId()));
        $userId = $this->getUser()->getId();

        return $this->render('instructor/lesson.html.twig', [
            'lessons' => $lessons,
            'users' => $userId
        ]);
    }

    // Insert for instructors
    #[Route('/instructor/insert/lesson', name: 'instructor_insert_lesson')]
    public function insertInstructors(EntityManagerInterface $entityManager, Request $request,): Response
    {
        $userId = $this->getUser()->getId();
        $lesson = new Lesson();
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid())
        {
            $lesson->setInstructor($this->getUser());
            $entityManager->persist($lesson);
            $entityManager->flush();
            $this->addFlash('success', 'Succesfully added a lesson!');
            return $this->redirectToRoute('instructor_lesson');
        }

        return $this->renderForm('instructor/insertLesson.html.twig', [
            'insertForm' => $form,
            'users' => $userId
        ]);
    }

    // Update for lessons
    #[Route('/instructor/update/lesson/{id}', name: 'instructor_update_lesson')]
    public function updateLesson(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $userId = $this->getUser()->getId();
        $lesson = $em->getRepository(Lesson::class)->find($id);
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $lesson = $form->getData();
            $em->persist($lesson);
            $em->flush();
            $this->addFlash('success', 'Lesson updated');
            return $this->redirectToRoute('instructor_lesson');
        }
        return $this->renderForm('instructor/updateLesson.html.twig', [
            'userForm' => $form,
            'users' => $userId
        ]);
    }

    // Delete for lessons
    #[Route('/instructor/delete/lesson/{id}', name: 'instructor_delete_lesson')]
    public function deleteLesson(EntityManagerInterface $entityManager, int $id): Response
    {
        $lesson = $entityManager->getRepository(Lesson::class)->find($id);
        $entityManager->remove($lesson);
        $entityManager->flush();
        $this->addFlash('success', 'Succesfully deleted a lesson');

        return $this->redirectToRoute('instructor_lesson');
    }

    // -----------------PROFILE------------------

    // Read for profile
    #[Route('/instructor/profile/{id}', name: 'instructor_profile')]
    public function profile(EntityManagerInterface $em, int $id): Response
    {
        $userInfo = $em->getRepository(User::class)->find($id);
        $userId = $this->getUser()->getId();
        return $this->render('instructor/showInstructor.html.twig', [
            'users' => $userId,
            'userInfo' => $userInfo
        ]);
    }

    // Update for profile
    #[Route('/instructor/updateinstructor/{id}', name: 'instructor_update_instructor')]
    public function updateInstructor(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $userId = $this->getUser()->getId();
        $user = $em->getRepository(User::class)->find($id);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->remove('hiringdate');
        $form->remove('salary');
        $form->remove('socialsecnumber');
        $form->remove('agreeTerms');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Profile updated');
            return $this->redirectToRoute('instructor_profile', array('id' => $userId));
        }
        return $this->renderForm('instructor/updateInstructor.html.twig', [
            'userForm' => $form,
            'users' => $userId
        ]);
    }


}
