<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Personne;
use App\Entity\Voiture;
use App\Entity\Ville;
use App\Entity\User;

class PersonneController extends AbstractController
{
    /**     
     * @Route("/insertPersonne/{nom}/{prenom}/{villeId}/{voitureId}/{tel}/{email}/{userId}", name="insertPersonne" )  
     */
    public function insert(Request $request, $nom, $prenom, $villeId, $voitureId, $tel, $email, $userId)     
    { 
        $pers=new Personne(); 
        $pers->setNom($nom);
        $pers->setPrenom($prenom);
        $date = new \DateTime('now');
        $pers->setDateNaiss($date);
        $pers->setTel($tel);
        $pers->setEmail($email);
        
        $em = $this->getDoctrine()->getManager();
        $villeRepository = $em->getRepository(Ville::class);
        $ville = $villeRepository->find($villeId);
        $pers->setVille($ville);

        $em = $this->getDoctrine()->getManager();
        $voitureRepository = $em->getRepository(Voiture::class);
        $voiture = $voitureRepository->find($voitureId);
        $pers->setVoiture($voiture);

        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->find($userId);
        $pers->setUser($user);

        if($request->isMethod('get')){ 
            //récupération de l'entityManager pour insérer les données en bdd
            $em=$this->getDoctrine()->getManager(); 
            $em->persist($pers); 
            //insertion en bdd
            $em->flush(); 
            $resultat=["ok"];         
        } else { 
            $resultat=["nok"];
        }  
        $reponse=new JsonResponse($resultat); 
        return $reponse;     
    } 
    /**    
     *  @Route("/deletePersonne/{id}", name="deletePersonne" ) 
     */
    public function delete(Request $request, $id)     
    { 
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $persRepository=$em->getRepository(Personne::class); 
        //requete de selection
        $pers=$persRepository->find($id); 
        //suppression de l'entity
        $em->remove($pers); 
        $em->flush(); 
        $resultat=["ok"]; 
        $reponse=new JsonResponse($resultat); 
        return $reponse;     
    } 
    /**
     *  @Route("/listePersonne", name="listePersonne")
     */
    public function liste(Request $request)
    {
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $persRepository=$em->getRepository(Personne::class); 
        $listePersonne = $persRepository->findAll();
        $resultat=[];
        foreach($listePersonne as $pers){
            array_push($resultat,[$pers->getId()=>[$pers->getNom(),$pers->getPrenom(),$pers->getDateNaiss(),$pers->getTel(),$pers->getEmail(),$pers->getVille(),$pers->getVoiture()]]);
        }    
        $reponse = new JsonResponse($resultat);

        return $reponse;
    }
}
