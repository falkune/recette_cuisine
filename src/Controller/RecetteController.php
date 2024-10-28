<?php

namespace App\Controller;

use App\Form\RecetteType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RecetteRepository;

class RecetteController extends AbstractController {
    #[Route('/add', name: 'app_add')]
    public function index(Request $request, EntityManagerInterface $em): Response {
        $form = $this->createForm(RecetteType::class);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $recetteInfo = $form->getData();
            $date = new DateTimeImmutable();

            $file = $form->get('url_img')->getData();

            $fileName = uniqid() . '.' . $file->guessExtension();
            $user = $this->getUser();

            $recetteInfo->setCreateAt($date);
            $recetteInfo->setUrlImg($fileName);
            $recetteInfo->setUser($user);

            $file->move(
                $this->getParameter('upload_directory'), 
                $fileName
            );

            $em->persist($recetteInfo);
            $em->flush();

            return $this->redirectToRoute('app_home');

        }

        return $this->render('recette/recetteform.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/recettes', name: 'app_recettes')]
    public function getAllRecettes(RecetteRepository $repository){
        $recettes = $repository->findAll();
        // dd($recettes);

        return $this->render('home/index.html.twig', [
            'recettes' => $recettes
        ]);
    }

    #[Route('like/{id}', name: 'app_like')]
    public function like(int $id, EntityManagerInterface $em, RecetteRepository $repository){
        $recette = $repository->find($id); // la recette a liker

        $user = $this->getUser(); // l'utilisateur qui veu liker

        $recette->addUser($user); // ajout de l'utilisateur dans les likeurs de recette

        $em->persist($recette);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }

    #[Route('/recette/{id}', name: 'app_recette')]
    public function recette(int $id, EntityManagerInterface $em, RecetteRepository $repository){
        $recette = $repository->find($id); 


        return $this->render('recette/recette.html.twig', [
            'recette' => $recette
        ]);
    }
}
