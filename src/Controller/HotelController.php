<?php

namespace App\Controller;

use App\Entity\Hotel;
use App\Entity\Ville;
use App\Form\HotelType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HotelController extends AbstractController
{
   

    #[Route('/add_hotel', name:'ajouter_hotel')]
    public function addHotel(Request $request, EntityManagerInterface $em): Response
    {
        $hotel = new Hotel();

        $form = $this->createForm(HotelType::class, $hotel);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $nom = $form->get('nom')->getData();
            $adresse = $form->get('adresse')->getData();
            $ville = $form->get('ville')->getData();
            $description = $form->get('description')->getData();
            $imageHotelFile = $form->get('imageHotel')->getData();

            if($imageHotelFile){
                try{//On créer un nouveau fichier
                    $fichier = md5(uniqid()) . '.' . $imageHotelFile->guessExtension();

                    $imageHotelFile->move(//On déplace le fichier dans le repertoire
                        $this->getParameter('image_directory'),
                        $fichier
                    );

                } catch(Exception $e){//On gère les éventuelles problème
                    $this->addFlash('error', "Impossible d'enregistrer l'image");
                }
            }

            $hotel->setNom($nom);
            $hotel->setAdresse($adresse);
            $hotel->setVille($ville);
            $hotel->setImageHotel($fichier);
            $hotel->setDescription($description);
            // dd($hotel);

            $em->persist($hotel);
            $em->flush();

            return $this->redirectToRoute('ajouter_hotel', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('hotel/ajoutez_hotel.html.twig', [
            "hotel" => $form->createView(),
        ]);
    }
}
