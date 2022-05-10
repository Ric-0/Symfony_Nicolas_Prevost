<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Inscription;
use App\Entity\Trajet;
use App\Entity\Personne;

class InscriptionController extends AbstractController
{
    /**     
     * @Route("/insertInscription/{persId}/{trajetId}", name="insertInscription" )  
     */
    public function insert(Request $request, $persId, $trajetId)     
    { 
        $insc = new Inscription();
        
        $em = $this->getDoctrine()->getManager();
        $persRepository = $em->getRepository(Personne::class);
        $pers = $persRepository->find($persId);
        $insc->setPers($pers);

        $em = $this->getDoctrine()->getManager();
        $trajRepository = $em->getRepository(Trajet::class);
        $traj = $trajRepository->find($trajetId);
        $insc->setTrajet($traj);

        if($request->isMethod('get')){ 
            //récupération de l'entityManager pour insérer les données en bdd
            $em=$this->getDoctrine()->getManager(); 
            $em->persist($insc); 
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
     *  @Route("/deleteInscription/{id}", name="deleteInscription" ) 
     */
    public function delete(Request $request, $id)     
    { 
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $inscRepository=$em->getRepository(Inscription::class); 
        //requete de selection
        $insc=$inscRepository->find($id); 
        //suppression de l'entity
        $em->remove($insc); 
        $em->flush(); 
        $resultat=["ok"]; 
        $reponse=new JsonResponse($resultat); 
        return $reponse;     
    } 
    /**
     *  @Route("/listeInscription", name="listeInscription")
     */
    public function liste(Request $request)
    {
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $inscRepository=$em->getRepository(Inscription::class); 
        $listeInscription = $inscRepository->findAll();
        $resultat=[];
        foreach($listeInscription as $insc){
            array_push($resultat,[$insc->getId()=>[$insc->getPers(),$insc->getTrajet()]]);
        }    
        $reponse = new JsonResponse($resultat);

        return $reponse;
    }
}
