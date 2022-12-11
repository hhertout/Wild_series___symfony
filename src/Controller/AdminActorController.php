<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Form\ActorType;
use App\Repository\ActorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/actor')]
class AdminActorController extends AbstractController
{
    #[Route('/', name: 'app_admin_actor_index', methods: ['GET'])]
    public function index(ActorRepository $actorRepository): Response
    {
        return $this->render('admin_actor/index.html.twig', [
            'actors' => $actorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_actor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ActorRepository $actorRepository): Response
    {
        $actor = new Actor();
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actorRepository->save($actor, true);

            return $this->redirectToRoute('app_admin_actor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_actor/new.html.twig', [
            'actor' => $actor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_actor_show', methods: ['GET'])]
    public function show(Actor $actor): Response
    {
        return $this->render('admin_actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_actor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Actor $actor, ActorRepository $actorRepository): Response
    {
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actorRepository->save($actor, true);

            return $this->redirectToRoute('app_admin_actor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_actor/edit.html.twig', [
            'actor' => $actor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_actor_delete', methods: ['POST'])]
    public function delete(Request $request, Actor $actor, ActorRepository $actorRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actor->getId(), $request->request->get('_token'))) {
            $actorRepository->remove($actor, true);
        }

        return $this->redirectToRoute('app_admin_actor_index', [], Response::HTTP_SEE_OTHER);
    }
}
