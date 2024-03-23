<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
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

            if($imageVilleFile){
                 $orignalFilename = pathinfo($imageVilleFile->getClientOriginalName(), PATHINFO_FILENAME);

                 $safeFilename = $slugger->slug($orignalFilename);

                 $newFilename = $safeFilename. '-' . uniqid() . '.' .$imageVilleFile->guessExtension();

                 try{
                     $imageVilleFile->move(
                        $this->getParameter('imageVille_directory'),
                        $newFilename
                     );
                 }catch(FileException $e){
                        $this->addFlash('error', "Impossible d'enregistrez l'image.");

                        return $this->redirectToroute('ajoutez_ville');
                 }
            }

            $ville->setName($nom);
            $ville->setDescription($description);
            $ville->setPays($pay);
            $ville->setImageVille($imageVilleFile);
// dd($ville);
            $em->persist($ville);
            $em->flush();
            // dd($pay);
            return $this->redirectToRoute('ajoutez_ville', [], Response::HTTP_SEE_OTHER);
           }

           return $this->render('ville/ajoutez_ville.html.twig', [
            'ville' => $form->createView(),
            
           ]);

    }
}
