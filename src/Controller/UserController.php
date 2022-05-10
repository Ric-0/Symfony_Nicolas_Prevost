<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Security\TokenAuthenticator;

class UserController extends AbstractController
{
    /**     
     * @Route("/register/{login}/{mdp}", name="register")    
     */
    public function register(Request $request, $login, $mdp){
        $user=new User(); 
        $user->setUsername($login); 
        $password = password_hash($mdp, PASSWORD_DEFAULT);
        $user->setPassword($password); 
        $token = base_convert(hash('sha256', time() . mt_rand()), 16, 36);
        $user->setApiToken($token);
        $roles[] = 'ROLE_USER';
        $user->setRoles($roles);
        if($request->isMethod('get')){ 
            //récupération de l'entityManager pour insérer les données en bdd
            $em=$this->getDoctrine()->getManager(); 
            $em->persist($user); 
            //insertion en bdd
            $em->flush(); 
            //récupération du dernière id inséré
            $em = $this->getDoctrine()->getManager();
            $userRepository = $em->getRepository(User::class);
            $tokken = $user->getApiToken();
            $resultat=[$tokken];         
        } else { 
            $resultat=["nok"];
        }  
        $reponse=new JsonResponse($resultat); 
        return $reponse; 
    }
    /**     
     * @Route("/login/{login}/{mdp}", name="login")    
     */
    public function login(Request $request, $login, $mdp){
        //$passwordHash = password_hash($mdp, PASSWORD_DEFAULT);
        //récupération du dernière id inséré
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findBy(['username' => $login]);
        $password = $user[0]->getPassword();
        //Verification du mot de passe
        $passordVerify = password_verify($mdp, $password);
        if ($passordVerify) {
            $tokken = $user[0]->getApiToken();
            $resultat=[$tokken]; 
        }else{
            $resultat=["nok"];
        }
        $reponse=new JsonResponse($resultat); 
        return $reponse; 
    }
}
