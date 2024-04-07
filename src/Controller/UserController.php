<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use App\Service\DecryptionService;
use App\Service\EncryptionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
class UserController extends AbstractController
{
    
    #[Route('/mon_compte', name: 'mon_compte')]
    public function compte_user()
    {
        return $this->render('user/mon_compte.html.twig');
    }
    
    #[Route('/connexion_securite', name: 'connexion_securite')]
    public function information(UserRepository $userRepository, Security $security, DecryptionService $decryptionService):Response
    {
        $user = $security->getUser();
// dd($user);
        $dataDecrypted = $decryptionService->DecryptionUser($user);
// dd($dataDecrypted);

        $user->setNom($dataDecrypted['nom']);
        $user->setPrenom($dataDecrypted['prenom']);

        return $this->render('user/information.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/modifier/{id}', name:'modifier_user')]
    public function modifier_utilisateur(User $user, Request $request, EntityManagerInterface $em, EncryptionService $encryptionService, DecryptionService $decryptionService, Security $security):Response
    {
        //On récupère l'utilisateur connecter
        $user = $security->getUser();
        // dd($user);
        
        //On décrypte le nom et prénom de l'utilisateur connecter
        $dataDecrypter = $decryptionService->DecryptionUser($user);
     
        //On affiche le nom et prénom  de l'utilisateur connecter décrypter
        $user->setNom($dataDecrypter['nom']);
        $user->setPrenom($dataDecrypter['prenom']);
// dd($user);
        //On créer le formulaire
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {//Si le formulaire est soumis et si il est valide
        try {
            //On récupère le nom, prénom et l'adresse email de l'utilisateur 
            $nom = $form->get('nom')->getData();
            $prenom = $form->get('prenom')->getData();
            // Obtient l'email depuis le formulaire
            $emailForm = $form->get('email')->getData();

            // Crypte le nom et le prénom avec le service de cryptage
            $nomCrypter = $encryptionService->EncryptionNom($nom);
            $prenomCrypter = $encryptionService->EncryptionNom($prenom);

            // Met à jour le nom et le prénom de l'utilisateur
            $user->setNom($nomCrypter);
            $user->setPrenom($prenomCrypter);

            
// dd($user);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Mise à jour des données réussie');
            return $this->redirectToRoute('home');
        } catch (Exception $e) {
            $this->addFlash('error', "Impossible de mettre à jour les données de l'utilisateur.");
        }
    }

    // Rendre votre vue avec le formulaire (cette partie dépend de votre mise en œuvre spécifique)
    return $this->render('user/modifier_user.html.twig', [
        'user' => $form->createView(),
    ]);

    }
}
