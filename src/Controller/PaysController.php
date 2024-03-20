<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Form\PaysType;
use App\Entity\PaysImage;
use App\Repository\PaysRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/pays')]
class PaysController extends AbstractController
{
    #[Route('/', name: 'app_pays_index', methods: ['GET'])]
    public function index(PaysRepository $paysRepository): Response
    {
        return $this->render('pays/index.html.twig', [
            'pays' => $paysRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pays_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pay = new Pays();
        $form = $this->createForm(PaysType::class, $pay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les images transmises
            $images = $form->get('images')->getData();

            $description = $form->get('description')->getData();
         

            //On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier 
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                //On copie le fichier dans le dossier uploads
                $image->move(
                $this->getParameter('image_directory'),
                $fichier
                );
            
                //On stocke l'image dans la base de donnée (son nom)
                $img = new PaysImage();
                $img->setName($fichier);
                $pay->addPaysImage($img);
            }
            $pay->setDescription($description);
            // dd($pay);
            $entityManager->persist($pay);
            $entityManager->flush();

            return $this->redirectToRoute('app_pays_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pays/new.html.twig', [
            'pay' => $pay,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pays_show', methods: ['GET'])]
    public function show(Pays $pay): Response
    {
        return $this->render('pays/show.html.twig', [
            'pay' => $pay,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pays_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pays $pay, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaysType::class, $pay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère les images transmises
            $images = $form->get('images')->getData();

            $description = $form->get('description')->getData();
            //On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier 
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                //On cope le fichier dans le dossier uploads
                $image->move(
                $this->getParameter('image_directory'),
                $fichier
                );
            
                //On stocke l'image dans la base de donnée (son nom)
                $img = new PaysImage();
                $img->setName($fichier);
                $pay->addPaysImage($img);
            }

            $pay->setDescription($description);
            $entityManager->flush();

            return $this->redirectToRoute('app_pays_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pays/edit.html.twig', [
            'pay' => $pay,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pays_delete', methods: ['POST'])]
    public function delete(Request $request, Pays $pay, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pay->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pay);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pays_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/supprime/image/{id}', name:'pays_delete_images', methods: ['DELETE'])]
    public function deleteImage(PaysImage $paysImage, Request $request, EntityManagerInterface $em){
        $data = json_decode($request->getContent(), true);
        // dd($data);
        //On verifie si le token est valide 
        if($this->isCsrfTokenValid('delete'.$paysImage->getId(), $data['_token'])){

            
            $nom = $paysImage->getName();

            unlink($this->getParameter('image_directory') . '/' . $nom);

            //On supprime l'entré de la base
            
            $em->remove($paysImage);
            $em->flush();

            //On répond en json
            return new JsonResponse(['success' => 'Fichier supprimer avec succès'], 200);
        }else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

   #[Route('liste-pays', name:'liste_pays')]
   public function listePays(PaysRepository $paysRepository, EntityManagerInterface $entityManager): Response
   {
    // $pays = $paysRepository->findAll();

    $queryBuilder = $entityManager->createQueryBuilder();

    $pays = $queryBuilder
        ->select('p', 'pi')
        ->from(Pays::class, 'p')
        ->leftJoin('p.paysImages', 'pi')

        ->getQuery()
        ->getResult();
   
// dd($pays);
    return $this->render('pays/liste_pays.html.twig', [
        'pays' => $pays,
        
    ]);
   }
   
}
