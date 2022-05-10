<?php

namespace App\Controller;
use App\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListePersonneController extends AbstractController
{
    /**
     * @Route("/liste", name="liste")
     */
    public function liste(Request $request)
    {//recuperation du repository grace au manager
        $em=$this->getDoctrine()->getManager();
        $personneRepository=$em->getRepository(Personne::class);
    //personneRepository herite de servciceEntityRepository ayant les methodes pour recuperer les données de la bdd
        $listePersonnes=$personneRepository->findAll();
        $resultat=[];
		foreach($listePersonnes as $pers){
			array_push($resultat,$pers->getNom());
		}
		$reponse=new JsonResponse($resultat);
		
		
        return $reponse;
    }
}
