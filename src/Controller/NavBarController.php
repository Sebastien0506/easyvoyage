<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NavBarController extends AbstractController
{
    private $paysService;

   public function nav(PaysService $paysService):Response
   {
    // $this->paysService = $paysService;

    // $payList = $this->paysService->menuPays();

    // return $this->render('nav.html.twig', [
    //     'payList' => $payList,
    // ]);
   }

}
