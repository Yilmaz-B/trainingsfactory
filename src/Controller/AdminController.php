<?php

namespace App\Controller;

use App\Entity\Training;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\TrainingType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    // -----------------GENERAL------------------

    // Admin home page
    #[Route('/admin', name: 'app_admin')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $trainings = $doctrine->getRepository(Training::class)->findAll();

        return $this->render('home/index.html.twig', [
            'trainings' => $trainings,
        ]);
    }

    // Admin dashboard
    #[Route('/admin/dasboard', name: 'admin_dashboard')]
    public function dashboard(ManagerRegistry $doctrine): Response
    {
        $userId = $this->getUser()->getId();
        return $this->render('dashboard/admin.html.twig', [
            'users' => $userId
        ]);
    }

    // -----------------MEMBER------------------

    // Read page for members
    #[Route('/admin/members', name: 'admin_members')]
    public function showMember(EntityManagerInterface $em, UserRepository $user): Response
    {
        $userId = $this->getUser()->getId();
        $response = $user->findUsersByRole('ROLE_CUSTOMER');
        return $this->render('admin/showmember.html.twig', [
            'members' => $response,
            'users' => $userId
        ]);
    }

    // Update function for members
    #[Route('/admin/updatemember/{id}', name: 'admin_update_member')]
    public function updateMember(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $userId = $this->getUser()->getId();
        $user = $em->getRepository(User::class)->find($id);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->remove('hiring_date');
        $form->remove('salary');
        $form->remove('social_sec_number');
        $form->remove('plainPassword');
        $form->remove('agreeTerms');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Member updated');
            return $this->redirectToRoute('admin_members');
        }
        return $this->renderForm('admin/updatemember.html.twig', [
            'userForm' => $form,
            'users' => $userId
        ]);
    }

    // Delete function for members
    #[Route('/admin/deletemember/{id}', name: 'admin_delete_member')]
    public function deleteMember(EntityManagerInterface $entityManager, int $id): Response
    {
        $members = $entityManager->getRepository(User::class)->find($id);
        $entityManager->remove($members);
        $entityManager->flush();
        $this->addFlash('success', 'Succesfully deleted a member');

        return $this->redirectToRoute('admin_members');
    }

    // Insert function for members
    #[Route('/admin/insert/member', name: 'admin_insert_member')]
    public function insertMember(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $userId = $this->getUser()->getId();
        $members = new User();
        $form = $this->createForm(RegistrationFormType::class, $members);
        $form->remove('hiring_date');
        $form->remove('salary');
        $form->remove('social_sec_number');
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid())
        {
            $members->setPassword(
                $userPasswordHasher->hashPassword(
                    $members,
                    $form->get('plainPassword')->getData()
                )
            );
            $members->setRoles(array('ROLE_CUSTOMER'));
            $entityManager->persist($members);
            $entityManager->flush();
            $this->addFlash('success', 'Succesfully added a customer!');
            return $this->redirectToRoute('admin_members');
        }

        return $this->renderForm('admin/insertMember.html.twig', [
            'insertForm' => $form,
            'users' => $userId
        ]);
    }

    // -----------------TRAINING------------------

    // Read for training
    #[Route('/admin/training', name: 'admin_training')]
    public function training(ManagerRegistry $doctrine): Response
    {
        $userId = $this->getUser()->getId();
        $training=$doctrine->getRepository(Training::class)->findAll();
//        dd($training);
        return $this->render('admin/showTraining.html.twig', [
            'training' => $training,
            'users' => $userId
        ]);
    }

    // Insert for training
    #[Route('/admin/insert/training', name: 'admin_insert_training')]
    public function insertTraining(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $userId = $this->getUser()->getId();
        $training = new Training();
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid())
        {
            $entityManager->persist($training);
            $entityManager->flush();
            $this->addFlash('success', 'Succesfully added a training form!');
            return $this->redirectToRoute('admin_training');
        }

        return $this->renderForm('admin/insertTraining.html.twig', [
            'insertForm' => $form,
            'users' => $userId
        ]);
    }

    // Update for training
    #[Route('/admin/updatetraining/{id}', name: 'admin_update_training')]
    public function updateTraining(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $userId = $this->getUser()->getId();
        $training = $em->getRepository(Training::class)->find($id);
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $training = $form->getData();
            $em->persist($training);
            $em->flush();
            $this->addFlash('success', 'Training updated');
            return $this->redirectToRoute('admin_training');
        }
        return $this->renderForm('admin/updateTraining.html.twig', [
            'updateForm' => $form,
            'users' => $userId
        ]);
    }

    // Delete function for training
    #[Route('/admin/deletetraining/{id}', name: 'admin_delete_training')]
    public function deleteTraining(EntityManagerInterface $entityManager, int $id): Response
    {
        $training = $entityManager->getRepository(Training::class)->find($id);
        $entityManager->remove($training);
        $entityManager->flush();
        $this->addFlash('success', 'Succesfully deleted a training');

        return $this->redirectToRoute('admin_training');
    }

    // -----------------PROFILE------------------

    // Read for profile
    #[Route('/admin/profile/{id}', name: 'admin_profile')]
    public function profile(EntityManagerInterface $em, int $id): Response
    {
        $userInfo = $em->getRepository(User::class)->find($id);
        $userId = $this->getUser()->getId();
        return $this->render('admin/showAdmin.html.twig', [
            'users' => $userId,
            'userInfo' => $userInfo
        ]);
    }

    // Update for profile
    #[Route('/admin/updateadmin/{id}', name: 'admin_update_admin')]
    public function updateAdmin(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $userId = $this->getUser()->getId();
        $user = $em->getRepository(User::class)->find($id);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->remove('agreeTerms');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Profile updated');
            return $this->redirectToRoute('admin_profile', array('id' => $userId));
        }
        return $this->renderForm('admin/updateAdmin.html.twig', [
            'userForm' => $form,
            'users' => $userId
        ]);
    }

    // -----------------INSTRUCTORS------------------

    // Read for instructors
    #[Route('/admin/instructor', name: 'admin_instructor')]
        public function showIntructor(ManagerRegistry $doctrine, UserRepository $user): Response
        {
            $userId = $this->getUser()->getId();
            $response = $user->findUsersByRole('ROLE_INSTRUCTOR');
            return $this->render('admin/showInstructor.html.twig', [
                'instructors' => $response,
                'users' => $userId
            ]);
        }

    // Insert for instructors
    #[Route('/admin/insert/instructor', name: 'admin_insert_instructor')]
    public function insertInstructors(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $userId = $this->getUser()->getId();
        $instructor = new User();
        $form = $this->createForm(RegistrationFormType::class, $instructor);
        $form->remove('street');
        $form->remove('place');
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid())
        {
            $instructor->setPassword(
                $userPasswordHasher->hashPassword(
                    $instructor,
                    $form->get('plainPassword')->getData()
                )
            );
            $instructor->setRoles(array('ROLE_INSTRUCTOR'));
            $entityManager->persist($instructor);
            $entityManager->flush();
            $this->addFlash('success', 'Succesfully added a instructor!');
            return $this->redirectToRoute('admin_instructor');
        }

        return $this->renderForm('admin/insertInstructor.html.twig', [
            'insertForm' => $form,
            'users' => $userId
        ]);
    }

    // Update for instructors
    #[Route('/admin/updateinstructor/{id}', name: 'admin_update_instructor')]
    public function updateInstructor(EntityManagerInterface $em, Request $request, int $id): Response
    {
        $userId = $this->getUser()->getId();
        $user = $em->getRepository(User::class)->find($id);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->remove('street');
        $form->remove('place');
        $form->remove('plainPassword');
        $form->remove('agreeTerms');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Instructor updated');
            return $this->redirectToRoute('admin_instructor');
        }
        return $this->renderForm('admin/updateInstructor.html.twig', [
            'userForm' => $form,
            'users' => $userId
        ]);
    }

    // Delete for instructors
    #[Route('/admin/deleteinstructor/{id}', name: 'admin_delete_instructor')]
    public function deleteInstructor(EntityManagerInterface $entityManager, int $id): Response
    {
        $members = $entityManager->getRepository(User::class)->find($id);
        $entityManager->remove($members);
        $entityManager->flush();
        $this->addFlash('success', 'Succesfully deleted a instructor');

        return $this->redirectToRoute('admin_instructor');
    }
}
