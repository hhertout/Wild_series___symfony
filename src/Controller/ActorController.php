<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ActorController extends AbstractController
{
    #[Route('/actor/{id}/', name: 'actor_show', methods: ['GET'])]
    public function show(Actor $actor, CategoryRepository $categoryRepository): Response
    {
        $catergories = $categoryRepository->findAll();
        
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
            'categories' => $catergories
        ]);
    }
}