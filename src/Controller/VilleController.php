<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Ville;

class VilleController extends AbstractController
{
    /**     
     * @Route("/insertVille/{vill}/{cp}", name="insertVille",requirements={"ville"="[a-z]{4,30}"})    
     */
    public function insert(Request $request, $vill, $cp)     
    { 
        $ville=new Ville(); 
        $ville->setVille($vill);
        $ville->setCodepostal($cp); 
        if($request->isMethod('get')){ 
            //récupération de l'entityManager pour insérer les données en bdd
            $em=$this->getDoctrine()->getManager(); 
            $em->persist($ville); 
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
     *  @Route("/deleteVille/{id}", name="deleteVille",requirements={"id"="[0-9]{1,5}"})    
     */
    public function delete(Request $request, $id)     
    { 
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $VilleRepository=$em->getRepository(Ville::class); 
        //requete de selection
        $ville=$VilleRepository->find($id); 
        //suppression de l'entity
        $em->remove($ville); 
        $em->flush(); 
        $resultat=["ok"]; 
        $reponse=new JsonResponse($resultat); 
        return $reponse;     
    } 
    /**
     *  @Route("/listeVille", name="listeVille")
     */
    public function liste(Request $request)
    {
        //récupération du Manager  et du repository pour accéder à la bdd
        $em=$this->getDoctrine()->getManager(); 
        $VilleRepository=$em->getRepository(Ville::class); 
        $listeVilles = $VilleRepository->findAll();
        $resultat=[];
        foreach($listeVilles as $ville){
            array_push($resultat,[$ville->getId()=>[$ville->getVille(),$ville->getCodepostal()]]);
        }    
        $reponse = new JsonResponse($resultat);

        return $reponse;
    }
}
