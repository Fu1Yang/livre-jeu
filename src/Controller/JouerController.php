<?php
namespace App\Entity;
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PersonnageRepository;
use App\Form\PersonnageType;
use App\Entity\Personnage; 
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
    public function newPersonnage(Personnage $personnage): Response
    {
        $personnage = new Personnage();
        $form = $this->createForm(PersonnageType::class, $personnage);
        return $this->render('jouer/new_personnage.html.twig', ['form'=>$form,'personnage'=>$personnage]);      
    }
  
}

