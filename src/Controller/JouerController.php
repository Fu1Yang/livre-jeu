<?php
namespace App\Entity;
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PersonnageRepository;
use App\Repository\AventureRepository;
use App\Repository\EtapeRepository;
use App\Form\PersonnageType;
use App\Entity\Personnage;
use App\Entity\Aventure;
use App\Entity\Etape;
use App\Entity\Partie;
use App\Repository\PartieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class JouerController extends AbstractController
{
    #[Route('/jouer', name: 'app_jouer')]
    public function index(PersonnageRepository $personnageRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $personnages = $personnageRepository->findBy(["user" => $user]);

        return $this->render('jouer/index.html.twig', [
            'personnages' => $personnages,
        ]);
    }

    #[Route('/jouer/new', name: 'app_jouer_new')]
    public function newPersonnage(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $personnage = new Personnage();
        $form = $this->createForm(PersonnageType::class, $personnage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $personnage->setUser($this->getUser());
            $entityManager->persist($personnage);
            $entityManager->flush();
            return $this->redirectToRoute('app_jouer', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('jouer/new_personnage.html.twig', ['form' => $form, 'personnage' => $personnage]);
    }

    #[Route('/jouer/aventures/{idPersonnage}', name: 'app_choix_aventure', methods: ['GET'])]
    public function afficherAventures(AventureRepository $aventureRepository, PersonnageRepository $personnageRepository, $idPersonnage): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $personnage = $personnageRepository->find($idPersonnage);
        if (!$personnage) {
            throw $this->createNotFoundException('Le personnage n\'existe pas.');
        }
        $aventures = $aventureRepository->findAll();
        return $this->render('jouer/aventures.html.twig', ['aventures' => $aventures, 'personnage' => $personnage]);
    }

    #[Route('/jouer/aventure/{idPersonnage}/{idAventure}', name: 'app_start_aventure', methods: ['GET'])]
    public function demarrerAventure(PersonnageRepository $personnageRepository, AventureRepository $aventureRepository, PartieRepository $partieRepository, $idPersonnage, $idAventure, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $personnage = $personnageRepository->find($idPersonnage);
        $aventure = $aventureRepository->find($idAventure);

        if (!$personnage || !$aventure) {
            throw $this->createNotFoundException('Le personnage ou l\'aventure n\'existe pas.');
        }

        $partie = $partieRepository->findOneBy(['aventurier' => $personnage, 'aventure' => $aventure]);
        $isNewPartie = !isset($partie);

        if ($isNewPartie) {
            $partie = new Partie();
            $partie->setAventurier($personnage);
            $partie->setAventure($aventure);
            $partie->setEtape($aventure->getPremiereEtape());
            $partie->setDatePartie(new \DateTime('now'));
            $entityManager->persist($partie);
            $entityManager->flush();
        }

        return $this->render('jouer/aventure-start.html.twig', [
            'personnage' => $personnage,
            'aventure' => $aventure,
            'isNewPartie' => $isNewPartie,
            'partie' => $partie,
        ]);
    }

    #[Route('/jouer/etape/{idPartie}/{idEtape}', name: 'app_play_etape', methods: ['GET'])]
    public function jouerEtape(PartieRepository $partieRepository, EtapeRepository $etapeRepository, EntityManagerInterface $entityManager, $idPartie, $idEtape): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $partie = $partieRepository->find($idPartie);
        if (!$partie) {
            throw $this->createNotFoundException('La partie n\'existe pas.');
        }

        $etape = $etapeRepository->find($idEtape);
        if (!$etape) {
            throw $this->createNotFoundException('L\'étape n\'existe pas.');
        }

        $partie->setEtape($etape);
        $entityManager->persist($partie);
        $entityManager->flush();

        return $this->render('jouer/aventure-etape.html.twig', [
            'partie' => $partie,
            'etape' => $etape,
        ]);
    }

    #[Route('/jouer/alternative/{idPartie}/{idEtape}/{idAlternative}', name: 'app_play_alternative', methods: ['GET'])]
    public function jouerAlternative(PartieRepository $partieRepository, EtapeRepository $etapeRepository, EntityManagerInterface $entityManager, $idPartie, $idEtape, $idAlternative): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $partie = $partieRepository->find($idPartie);
        if (!$partie) {
            throw $this->createNotFoundException('La partie n\'existe pas.');
        }

        $etape = $etapeRepository->find($idEtape);
        if (!$etape) {
            throw $this->createNotFoundException('L\'étape n\'existe pas.');
        }

        // Logique pour récupérer et appliquer l'alternative choisie.
        $alternative = $etape->getAlternatives()->filter(function ($alt) use ($idAlternative) {
            return $alt->getId() == $idAlternative;
        })->first();

        if (!$alternative) {
            throw $this->createNotFoundException('L\'alternative n\'existe pas.');
        }

        $nextEtape = $alternative->getNextEtape();
        $partie->setEtape($nextEtape);
        $entityManager->persist($partie);
        $entityManager->flush();

        return $this->redirectToRoute('app_play_etape', [
            'idPartie' => $idPartie,
            'idEtape' => $nextEtape->getId()
        ]);
    }
}
