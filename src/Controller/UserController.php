<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route("/utilisateurs", name="app_user_list")
     */
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $page = (int)$request->query->get("page", 1);
        $pagination = $userRepository->getPagination($page);
        return $this->render('user/index.html.twig', [
            'users' => $pagination[0],
            'totalPages' => $pagination[1],
            'currentPage' => $page,
            'searchBy' => false
        ]);
    }

    /**
     * @Route("/utilisateur/creer", name="app_user_create")
     */
    public function create(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', "L'utilisateur avec la matricule: {$user->getMatricule()} à bien été créer !");
            return $this->redirectToRoute("app_user_list");
        }
        return $this->render('user/create.html.twig', ["form"=>$form->createView(),"user"=>$user]);

    }

    /**
     * @Route("/utilisateur/{id}/edit", name="app_user_edit")
     */
    public function edit(User $user, Request $request): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "L'utilisateur avec la matricule: {$user->getMatricule()} à bien été éditer !");
            return $this->redirectToRoute("app_user_list");
        }
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/utilisateur/{id}/delete", name="app_user_delete")
     */
    public function delete(User $user, UserRepository $userRepository): Response
    {
        $userRepository->remove($user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $this->addFlash('success', "L'utilisateur avec la matricule: {$user->getMatricule()} à bien été supprimer !");
        return $this->redirectToRoute("app_user_list");
    }
}
