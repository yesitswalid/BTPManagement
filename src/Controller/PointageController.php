<?php

namespace App\Controller;

use App\Entity\Pointage;
use App\Form\PointageType;
use App\Repository\PointageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PointageController extends AbstractController
{

    /**
     * @Route("/pointages", name="app_pointage_list")
     */
    public function index(PointageRepository $pointageRepository, Request $request): Response
    {
        $page = (int)$request->query->get("page", 1);
        $pagination = $pointageRepository->getPagination($page);
        return $this->render('pointage/index.html.twig', [
            'pointages' => $pagination[0],
            'totalPages' => $pagination[1],
            'currentPage' => $page,
            'searchBy' => false
        ]);

        //ToDo : Pagination des pointages
    }

    /**
     * @Route("/pointage/creer", name="app_pointage_create")
     */
    public function create(Request $request): Response
    {
        $pointage = new Pointage();
        $form = $this->createForm(PointageType::class, $pointage);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pointage);
            $entityManager->flush();
            $this->addFlash('success', "Le pointage du chantier : {$pointage->getChantier()->getNom()} à bien été créer !");
            return $this->redirectToRoute("app_pointage_list");
        }
        return $this->render('pointage/create.html.twig', ["form"=>$form->createView(),"pointage"=>$pointage]);

    }

    /**
     * @Route("/pointage/{id}/edit", name="app_pointage_edit")
     */
    public function edit(Pointage $pointage, Request $request): Response
    {
        $form = $this->createForm(PointageType::class, $pointage);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Le pointage du chantier : {$pointage->getChantier()->getNom()} à bien été éditer !");
            return $this->redirectToRoute("app_pointage_list");
        }
        return $this->render('pointage/edit.html.twig', [
            'pointage' => $pointage,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/pointage/{id}/delete", name="app_pointage_delete")
     */
    public function delete(Pointage $pointage, PointageRepository $pointageRepository): Response
    {
        $pointageRepository->remove($pointage);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $this->addFlash('success', "Le pointage du chantier : {$pointage->getChantier()->getNom()} à bien supprimer !");
        return $this->redirectToRoute("app_pointage_list");
    }
}
