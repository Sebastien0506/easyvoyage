<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function information(UserRepository $userRepository):Response
    {
        $user = $this->getUser();

        return $this->render('user/information.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/modifier/{id}', name:'modifier_user')]
    public function modifier_utilisateur(User $user, Request $request, EntityManagerInterface $em):Response
    {
      
        
        $form = $this->createForm(UserEditType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $nom = $form->get('nom')->getData();
            $prenom = $form->get('prenom')->getData();
            $email = $form->get('email')->getData();

            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setPrenom($prenom);

            $em->persist($user);
            $em->flush();
        }
        return $this->render("user/modifier_user.html.twig", [
            "user" => $form->createView()
        ]);

    }
}
