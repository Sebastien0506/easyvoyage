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
    //    dd($paysFavoris);
        return $this->render('front/index.html.twig', [
            
            
        ]);
    }

    
}
