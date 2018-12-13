<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Services\UserService;
use App\Form\StudentRegisterType;
use App\Form\TeacherRegisterType;
use App\Entity\User;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register/teacher", name="register_teacher")
     */
    public function createTeacher(UserPasswordEncoderInterface $encoder, UserService $UserService, Request $request)
    {
        $user = new User();
        $user->setRoles(['ROLE_TEACHER']);
        $user->setUsername('Professeur(e)');
        $form = $this->createForm(TeacherRegisterType::class, $user);
            $form->handleRequest($request);
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
            if ($form->isSubmitted() && $form->isValid()){
                $UserService->add($user);
                $id = $user->getId();
                $this->addFlash(
                    'notice',
                    'Le professeur a bien été créé!'
                );

            return $this->redirectToRoute('teacher_home', array(
                'id' => $id));
        }
        return $this->render('register/registerTeacher.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/register/student", name="register_student")
     */
    public function createStudent(UserPasswordEncoderInterface $encoder, UserService $UserService, Request $request)
    {
        $user = new User();
        $user->setRoles(['ROLE_STUDENT']);
        $form = $this->createForm(StudentRegisterType::class, $user);
            $form->handleRequest($request);
             $encoded = $encoder->encodePassword($user, $user->getPassword());
             $user->setPassword($encoded);
            if ($form->isSubmitted() && $form->isValid()){
                $UserService->add($user);
                $id = $user->getId();
                $this->addFlash(
                    'notice',
                    'L\'élève a bien été créé!'
                );

            return $this->redirectToRoute('student_home', array(
                'id' => $id));
        }

        return $this->render ('register/registerStudent.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
