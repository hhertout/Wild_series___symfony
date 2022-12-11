<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/actor')]
class ActorController extends AbstractController
{
    #[Route('/', name: 'actor_showAll', methods: ['GET'])]
    public function index(ActorRepository $actorRepository, CategoryRepository $categoryRepository): Response
    {     
        return $this->render('actor/index.html.twig', [
            'actors' => $actorRepository->findAll(),
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/{slug}/', name: 'actor_show', methods: ['GET'])]
    public function show(Actor $actor, CategoryRepository $categoryRepository): Response
    {
        $catergories = $categoryRepository->findAll();
        
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
            'categories' => $catergories
        ]);
    }
}