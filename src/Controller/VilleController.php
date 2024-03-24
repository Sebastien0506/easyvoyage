<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VilleController extends AbstractController
{
    // #[Route('/ville', name: 'app_ville')]
    // public function index(): Response
    // {
    //     return $this->render('ville/index.html.twig', [
    //         'controller_name' => 'VilleController',
    //     ]);
    // }
    #[Route('add_city', name:'ajoutez_ville')]
    public function addCity(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
           $ville = new Ville();

           $form = $this->createForm(VilleType::class, $ville);

           $form->handleRequest($request);

           if($form->isSubmitted() && $form->isValid())
           {
            $nom = $form->get('name')->getData();
            // dd($ville);
            $description = $form->get('description')->getData();
            $pay = $form->get('pays')->getData();

            $imageVilleFile = $form->get('imageVille')->getData();

            if($imageVilleFile){//Si imageVilleFile existe
                try{//On créer un nouveau fichier
                    $fichier = md5(uniqid()) . '.' . $imageVilleFile->guessExtension();

                    $imageVilleFile->move(//On déplace le fichier dans le repertoire
                        $this->getParameter('image_directory'),
                        $fichier
                    );
// dd($imageVilleFile, $fichier);
                } catch(Exception $e){//On gère les éventuelles problème
                    $this->addFlash('error', "Impossible d'enregistrer l'image");
                }
                
            }
            //On donne les informations a la variable $ville
            $ville->setName($nom);
            $ville->setDescription($description);
            $ville->setPays($pay);
            $ville->setImageVille($fichier);
// dd($ville);
            $em->persist($ville); //On persist
            $em->flush();//On flush
            // dd($pay);
            return $this->redirectToRoute('ajoutez_ville', [], Response::HTTP_SEE_OTHER);//On gère la redirection une fois que l'enregistrement c'est effectuer
           }

           return $this->render('ville/ajoutez_ville.html.twig', [
            'ville' => $form->createView(),
            
           ]);
    }

    #[Route('info_city/{id}', name:'info_ville', methods:['GET'])]
    public function infoVille(int $id, VilleRepository $villeRepository)
    {
        // $ville = $villeRepository->find($id);
        // // dd($ville);
        // if($ville){

        // }

        return $this->render('ville/info_ville.html.twig',[]);
    }
}
