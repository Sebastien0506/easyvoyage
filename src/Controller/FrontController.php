<?php

namespace App\Controller;

use App\Service\PaysService;
use App\Repository\PaysRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(PaysRepository $paysRepository): Response
    {   
       $paysFavoris = $paysRepository->findFavoris();
    //    $imagesPaysFavoris = $paysRepository-> findPaysImages();
    //    dd($imagesPaysFavoris);
    //    dd($paysFavoris);
    $favorisPays = [];
    // dd($paysFavoris);
    foreach($paysFavoris as $pays){
        $images = $pays->getPaysImages();

        $imagesURLs = [];
        // dd($images);
        foreach($images as $paysImages){
               $imagesURLs[] = $paysImages->getName();
            //    dd($imagesPays);
        }

        $favorisPays[] = [
            'nom' => $pays->getNom(),
            'description' => $pays->getDescription(),
            'images' => $imagesURLs,
        ];
        // dump($favorisPays);
    }
    // dd($favorisPays);
        return $this->render('front/index.html.twig', [
            'favorisPays' => $favorisPays,
        ]);
    }

    
}
