<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Voiture;
use App\Entity\Marque;

class VoitureController extends AbstractController
{
    /**     
     * @Route("/insertVoiture/{idMarque}/{nbP}/{modele}", name="insertvoiture",requirements={"voiture"="[a-z]{4,30}"})    
     */
    public function insert(Request $request, $idMarque, $nbP, $modele)     
    { 
        $voiture=new Voiture(); 
        $em = $this->getDoctrine()->getManager();
        $marqueRepository = $em->getRepository(Marque::class);
        $marq = $marqueRepository->find($idMarque);
        $voiture->setMarque($marq);
        $voiture->setNbPlaces($nbP);
        $voiture->setModele($modele); 
        if($request->isMethod('get')){ 
            //récupération de l'entityManager pour insérer les données en bdd
            $em=$this->getDoctrine()->getManager(); 
            $em->persist($voiture); 
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
     *  @Route("/deletevoiture/{id}", name="deletevoiture",requirements={"id"="[0-9]{1,5}"})    
     */
    public function delete(Request $request, $id)     
    { 
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $voitureRepository=$em->getRepository(voiture::class); 
        //requete de selection
        $voiture=$voitureRepository->find($id); 
        //suppression de l'entity
        $em->remove($voiture); 
        $em->flush(); 
        $resultat=["ok"]; 
        $reponse=new JsonResponse($resultat); 
        return $reponse;     
    } 
    /**
     *  @Route("/listevoiture", name="listevoiture")
     */
    public function liste(Request $request)
    {
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $voitureRepository=$em->getRepository(voiture::class); 
        $listevoitures = $voitureRepository->findAll();
        $resultat=[];
        foreach($listevoitures as $voiture){
            array_push($resultat,[$voiture->getId()=>[$voiture->getMarque(),$voiture->getModele(),$voiture->getNbPlaces()]]);
        }    
        $reponse = new JsonResponse($resultat);

        return $reponse;
    }
}
