<?php

namespace App\Controller;

use App\Entity\Chantier;
use App\Form\ChantierType;
use App\Repository\ChantierRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ChantierController extends AbstractController
{

    /**
     * @Route("/chantiers", name="app_chantier_list")
     */
    public function index(ChantierRepository $chantierRepository, Request $request): Response
    {
        $page = (int)$request->query->get("page", 1);
        $pagination = $chantierRepository->getPagination($page);
        return $this->render('chantier/index.html.twig', [
            'chantiers' => $pagination[0],
            'totalPages' => $pagination[1],
            'currentPage' => $page,
            'searchBy' => false
        ]);
    }

    /**
     * @Route("/chantier/create", name="app_chantier_create")
     */
    public function create(Request $request): Response
    {
        $chantier = new Chantier();
        $form = $this->createForm(ChantierType::class, $chantier);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($chantier);
            $entityManager->flush();
            $this->addFlash('success', "Le chantier {$chantier->getNom()} à bien été créer !");
            return $this->redirectToRoute("app_chantier_list");
        }
        return $this->render('forms/form_chantier.html.twig', [
            "title"=>"Créer un chantier",
            "form"=>$form->createView(),
            "button"=>"Créer"]);
    }

    /**
     * @Route("/chantier/{id}/edit", name="app_chantier_edit")
     */
    public function edit(Chantier $chantier, Request $request): Response
    {
        try {
            $form = $this->createForm(ChantierType::class, $chantier);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid())
            {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', "Le chantier {$chantier->getNom()} à bien été éditer !");
                return $this->redirectToRoute('app_chantier_list');
            }
            return $this->render('chantier/edit.html.twig', [
                'chantier' => $chantier,
                'form' => $form->createView()
            ]);
        } catch (EntityNotFoundException $e) {
            return $this->redirectToRoute('app_chantier_list');
        }
    }

    /**
     * @Route("/chantier/{id}/detail", name="app_chantier_detail")
     */
    public function detail(Chantier $chantier): Response
    {
        return $this->render("chantier/detail.html.twig",
            [
                "chantier"=>$chantier,
                "users_count"=>count($chantier->getUsersPointages()),
                "hours_accumulate"=>$chantier->getHeuresCumulees()
            ]);
    }

    /**
     * @Route("/chantier/{id}/delete", name="app_chantier_delete")
     */
    public function delete(Chantier $chantier, ChantierRepository $repository): Response
    {
        $repository->remove($chantier);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $this->addFlash('success', "Le chantier {$chantier->getNom()} à bien été supprimer !");
        return $this->redirectToRoute("app_chantier_list");
    }
}
