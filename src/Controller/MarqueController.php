<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Marque;

class MarqueController extends AbstractController
{
    /**     
     * @Route("/insertMarque/{nom}", name="insertMarque",requirements={"nom"="[a-z]{4,30}"})    
     */
    public function insert(Request $request, $nom)     
    { 
        $marq=new Marque(); 
        $marq->setNom($nom); 
        if($request->isMethod('get')){ 
            //récupération de l'entityManager pour insérer les données en bdd
            $em=$this->getDoctrine()->getManager(); 
            $em->persist($marq); 
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
     *  @Route("/deleteMarque/{id}", name="deleteMarque",requirements={"id"="[0-9]{1,5}"})    
     */
    public function delete(Request $request, $id)     
    { 
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $marqueRepository=$em->getRepository(Marque::class); 
        //requete de selection
        $marq=$marqueRepository->find($id); 
        //suppression de l'entity
        $em->remove($marq); 
        $em->flush(); 
        $resultat=["ok"]; 
        $reponse=new JsonResponse($resultat); 
        return $reponse;     
    } 
    /**
     *  @Route("/listeMarque", name="listeMarque")
     */
    public function liste(Request $request)
    {
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $marqueRepository=$em->getRepository(Marque::class); 
        $listeMarques = $marqueRepository->findAll();
        $resultat=[];
        foreach($listeMarques as $marq){
            array_push($resultat,[$marq->getId()=>$marq->getNom()]);
        }    
        $reponse = new JsonResponse($resultat);

        return $reponse;
    }
}
