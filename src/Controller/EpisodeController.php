<?php

namespace App\Controller;

use DateTime;
use App\Entity\Season;
use DateTimeInterface;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Form\CommentType;
use App\Form\EpisodeType;
use Symfony\Component\Mime\Email;
use App\Repository\CommentRepository;
use App\Repository\EpisodeRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/episode')]
class EpisodeController extends AbstractController
{
    #[Route('/{slug}/season-show/{season}/{episode}', requirements: ['id'=>'\d+'], name: 'episode_show')]
    public function showEpisode( 
        Program $program,
        Season $season,
        Episode $episode, 
        CategoryRepository $categoryRepository,
        CommentRepository $commentRepository,
        Request $request
        )
    {      
        $categories = $categoryRepository->findAll();
        $comments = $commentRepository->findby([ 'episode' => $episode->getId()]);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTime();
            $comment->setAuthor($this->getUser());
            $comment->setEpisode($episode);
            $comment->setCreationDate($date);
            $commentRepository->save($comment, true);

            $this->addFlash('succes', 'Votre commentaire est désormais en ligne');
            return $this->redirectToRoute('episode_show', [
                'episode' => $episode->getId(),
                'season' => $season->getId(),
                'slug' => $program->getSlug()
            ]);
        }

        return $this->renderForm('episode/visitorShow.html.twig', [
            'comments' => $comments,
            'episode' => $episode,
            'categories' => $categories,
            'season' => $season,
            'program' => $program,
            'form' => $form
        ]);
    }
    #[Route('/', name: 'app_episode_index', methods: ['GET'])]
    public function index(EpisodeRepository $episodeRepository): Response
    {
        return $this->render('episode/index.html.twig', [
            'episodes' => $episodeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_episode_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EpisodeRepository $episodeRepository, 
        SluggerInterface $slugger,
        MailerInterface $mailer
        ): Response
    {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($episode->getTitle());
            $episode->setSlug($slug);

            $episodeRepository->save($episode, true);
            
            $mail = (new Email());
            $mail
                ->from($this->getParameter('mailer_from'))
                ->to($this->getParameter('mailer_to'))
                ->subject('Un nouvel épisode vient d\'être publiée')
                ->html($this->renderView('Episode/newEpisodeEmail.html.twig', ['episode' => $episode]));
            $mailer->send($mail);

            $this->addFlash('succes', 'L\'épisode à bien été crée');

            return $this->redirectToRoute('app_episode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/new.html.twig', [
            'episode' => $episode,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_episode_show', methods: ['GET'])]
    public function show(Episode $episode): Response
    {
        return $this->render('episode/show.html.twig', [
            'episode' => $episode,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_episode_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Episode $episode, EpisodeRepository $episodeRepository): Response
    {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $episodeRepository->save($episode, true);

            $this->addFlash('succes', 'L\'épisode à bien été modifié');

            return $this->redirectToRoute('app_episode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/edit.html.twig', [
            'episode' => $episode,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_episode_delete', methods: ['POST'])]
    public function delete(Request $request, Episode $episode, EpisodeRepository $episodeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$episode->getSlug(), $request->request->get('_token'))) {
            $episodeRepository->remove($episode, true);

            $this->addFlash('danger', 'L\'épisode à bien été supprimé');
        }
        return $this->redirectToRoute('app_episode_index', [], Response::HTTP_SEE_OTHER);
    }
}
