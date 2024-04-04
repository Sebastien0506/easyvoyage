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
    
    #[Route('add_city', name:'ajoutez_ville')]
    public function addCity(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
           $ville = new Ville();

           $form = $this->createForm(VilleType::class, $ville);

           $form->handleRequest($request);

           if($form->isSubmitted() && $form->isValid())
           {
            $nom = $form->get('name')->getData();
            
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

                } catch(Exception $e){//On gère les éventuelles problème
                    $this->addFlash('error', "Impossible d'enregistrer l'image");
                }
                
            }
            //On donne les informations a la variable $ville
            $ville->setName($nom);
            $ville->setDescription($description);
            $ville->setPays($pay);
            $ville->setImageVille($fichier);

            $em->persist($ville); //On persist
            $em->flush();//On flush
            
            return $this->redirectToRoute('ajoutez_ville', [], Response::HTTP_SEE_OTHER);//On gère la redirection une fois que l'enregistrement c'est effectuer
           }

           return $this->render('ville/ajoutez_ville.html.twig', [
            'ville' => $form->createView(),
            
           ]);
    }

    #[Route('info_city/{id}', name:'info_ville', methods:['GET'])]
    public function infoVille(int $id, VilleRepository $villeRepository)
    {
        $ville = $villeRepository->find($id);//On récupère la ville par sont id 
       
        $hotelData = []; //On initialise la variable $hotelData a un tableau vide 
        
        // $temperature = "Non Disponible";
        // $temps = "Non Disponible";
        
        if($ville){//Si il y'a une ville 
            $nomVille = urlencode($ville->getName());//On récupère sont nom
           
            $hotel = $ville->getHotels();//On récupère les hotels associer à la ville
            
            $url = 'http://localhost:8001/api/meteo?ville=' . $nomVille;//On créer une url
            // dd($url);
            $ch = curl_init($url);//On initialise une requête curl avec l'url
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/ld+json']);

            //On exécute la requête
            $response = curl_exec($ch);
            if(curl_error($ch)){//Si il y a des erreurs
                throw new Exception(curl_error($ch));
            }
            curl_close($ch);//On ferme la requête

            $data = json_decode($response, true);//On décode la reponse envoyer par l'api
            // dd($data);

            if(array_key_exists("error", $data)){
                // echo "Erreur: " . $data["error"];
                // // dd($data);
                $temperature = "Non Disponible";
                $temps = "Non Disponible";
            } else {
                 // var_dump("coucou");
                 //On récupère les données
                $temperature = $data['temperature'];
                $temps = $data['temps'];
                // dd($temperature, $temps);
            }
                
            foreach($hotel as $ville){//On boucle sur chaque hotel
                   $hotelName = $ville->getNom();//On récupère leurs nom
                   $hotelAdresse = $ville->getAdresse();//On récupère leurs adresse
                   $hotelDescription = $ville->getDescription();//On récupère leurs description
                   $hotelImage = $ville->getImageHotel();//On récupère leurs image
                //    dd($hotelImage);
                $hotelData[] = [//On stock tous la variable $hotelData
                    'hotelName' => $hotelName,
                    'hotelAdresse' => $hotelAdresse,
                    'hotelDescription' => $hotelDescription,
                    'hotelImage' => $hotelImage,
                ];
            
            }
            
            
        }
        //On affiche la vue 
        return $this->render('ville/info_ville.html.twig',[
            'hotelData' => $hotelData,
            'nomVille' => $nomVille,
            'temperature' => $temperature,
            'temps' => $temps,
        ]);
    }
}
