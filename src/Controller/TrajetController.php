<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Trajet;
use App\Entity\Ville;
use App\Entity\Personne;

class TrajetController extends AbstractController
{
    /**     
     * @Route("/insertTrajet/{ville_dep}/{ville_arr}/{persId}", name="insertTrajet" )  
     */
    public function insert(Request $request, $ville_dep, $ville_arr, $persId)     
    { 
        $traj=new Trajet(); 
        $date = new \DateTime('now');
        $traj->setDateTrajet($date);
        
        $em = $this->getDoctrine()->getManager();
        $villeRepository = $em->getRepository(Ville::class);
        $ville = $villeRepository->find($ville_dep);
        $traj->setVilleDep($ville);

        $em = $this->getDoctrine()->getManager();
        $villeRepository = $em->getRepository(Ville::class);
        $ville = $villeRepository->find($ville_arr);
        $traj->setVilleArr($ville);

        $em = $this->getDoctrine()->getManager();
        $persRepository = $em->getRepository(Personne::class);
        $pers = $persRepository->find($persId);
        $traj->setPers($pers);

        if($request->isMethod('get')){ 
            //récupération de l'entityManager pour insérer les données en bdd
            $em=$this->getDoctrine()->getManager(); 
            $em->persist($traj); 
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
     *  @Route("/deleteTrajet/{id}", name="deleteTrajet" ) 
     */
    public function delete(Request $request, $id)     
    { 
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $trajRepository=$em->getRepository(Trajet::class); 
        //requete de selection
        $traj=$trajRepository->find($id); 
        //suppression de l'entity
        $em->remove($traj); 
        $em->flush(); 
        $resultat=["ok"]; 
        $reponse=new JsonResponse($resultat); 
        return $reponse;     
    } 
    /**
     *  @Route("/listeTrajet", name="listeTrajet")
     */
    public function liste(Request $request)
    {
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $trajRepository=$em->getRepository(Trajet::class); 
        $listeTraj = $trajRepository->findAll();
        $resultat=[];
        foreach($listeTraj as $traj){
            array_push($resultat,[$traj->getId()=>[$traj->getVilleDep(),$traj->getVilleArr(),$traj->getDateTrajet(),$traj->getPers()]]);
        }    
        $reponse = new JsonResponse($resultat);

        return $reponse;
    }
}
