<?php
namespace App\Entity;
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PersonnageRepository;
use App\Repository\AventureRepository;
use App\Repository\EtapeRepository;
use App\Form\PersonnageType;
use App\Entity\Personnage;
use App\Entity\Aventure;
use App\Entity\Etape;
use App\Entity\Partie;
use App\Repository\PartieRepository;
use Symfony\Component\HttpFoundation\Request;

class JouerController extends AbstractController
{
    #[Route('/jouer', name: 'app_jouer')]
    public function index(PersonnageRepository $personnageRepository): Response
    {
        $personnages = $personnageRepository->findAll();
        return $this->render('jouer/index.html.twig', [
            'personnages' => $personnages ,
        ]);        
    }

    #[Route('/jouer/new', name: 'app_jouer_new')]
    public function newPersonnage(PersonnageRepository $personnageRepository ,Request $request): Response
    {
        $personnage = new Personnage();
        $form = $this->createForm(PersonnageType::class, $personnage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $personnageRepository->save($personnage, true);
            return $this->redirectToRoute('app_jouer', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('jouer/new_personnage.html.twig', ['form'=>$form,'personnage'=>$personnage]);  

    }

    
    #[Route('/jouer/aventures/{idPersonnage}', name: 'app_choix_aventure', methods:[('GET')])]
    public function afficherAventures(AventureRepository $aventureRepository,PersonnageRepository $personnageRepository,$idPersonnage): Response
    {  
        $personnage = $personnageRepository->find($idPersonnage);
        $aventures = $aventureRepository->findAll();
        return $this->render('jouer/aventures.html.twig', ['aventures'=>$aventures,'personnage'=>$personnage]);  

    }
    #[Route('/jouer/aventure/{idPersonnage}/{idAventure}', name: 'app_start_aventure', methods: ['GET'])]
    public function demarrerAventure(PersonnageRepository $personnageRepository, AventureRepository $aventureRepository, PartieRepository $partieRepository, $idPersonnage, $idAventure): Response
    {
        // Récupérer le personnage et l'aventure
        $personnage = $personnageRepository->find($idPersonnage);
        $aventure = $aventureRepository->find($idAventure);
    
        if (!$personnage || !$aventure) {
            throw $this->createNotFoundException('Le personnage ou l\'aventure n\'existe pas.');
        }
    
        // Récupérer la partie existante
        $partie = $partieRepository->findOneBy(['aventurier' => $personnage, 'aventure' => $aventure]);
        $isNewPartie = !isset($partie);
    
        // Créer une nouvelle partie si elle n'existe pas
        if ($isNewPartie) {
            $partie = new Partie();
            $partie->setAventurier($personnage);
            $partie->setAventure($aventure);
            $partie->setEtape($aventure->getPremiereEtape());
            $partie->setDatePartie(new \DateTime('now'));
            $partieRepository->save($partie, true);
        }
    
        // Passer les variables à la vue
        return $this->render('jouer/aventure-start.html.twig', [
            'personnage' => $personnage,
            'aventure' => $aventure,
            'isNewPartie' => $isNewPartie,
            'partie' => $partie, // Assurez-vous d'inclure la variable partie ici
        ]);
    }
    

    #[Route('/jouer/etape/{idPartie}/{idEtape}', name: 'app_play_aventure', methods: ['GET'])]
    public function jouerEtape(PartieRepository $partieRepository, EtapeRepository $etapeRepository, $idPartie, $idEtape): Response
    {
        // Charger la partie
        $partie = $partieRepository->find($idPartie);
        if (!$partie) {
            throw $this->createNotFoundException('La partie n\'existe pas.');
        }

        // Charger l'étape
        $etape = $etapeRepository->find($idEtape);
        if (!$etape) {
            throw $this->createNotFoundException('L\'étape n\'existe pas.');
        }

        // Mettre à jour la partie avec l'étape en cours
        $partie->setEtape($etape);
        $partieRepository->save($partie, true);

        // Vérifier si c'est l'étape de fin
        if ($etape->getFinAventure() != null) {
            return $this->render('jouer/aventure-end.html.twig', [
                'partie' => $partie,
                'etape' => $etape,
            ]);
        }

        return $this->render('jouer/aventure-play.html.twig', [
            'partie' => $partie,
            'etape' => $etape,
        ]);
    }


    #[Route('/jouer/etape/{idPartie}/{idEtape}', name: 'app_play_aventure', methods: ['GET'])] 
        public function finAventure(Etape $etape)
    {
        if ($etape->getFinAventure()!=null){
            return $this->render('jouer/aventure-end.html.twig');
        }else {
            return $this->render('jouer/aventure-play.html.twig');
            }
    }


  



    
  
}


