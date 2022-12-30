<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Season;
use App\Entity\Program;
use App\Form\ProgramType;
use App\Service\ProgramDuration;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/program')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'program_index')]
    public function index(
        ProgramRepository $programRepository,
        CategoryRepository $categoryRepository,
        RequestStack $requestStack,
        Request $request
    ): Response {
        $requestStack->getSession();
        $categories = $categoryRepository->findAll();

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $search = $form->getData();
            $programs = $programRepository->findLikeName($search);
        } else {
            $programs = $programRepository->findAll();
        }

        return $this->renderForm('program/index.html.twig', [
            'programs' => $programs,
            'form' => $form,
            'categories' => $categories
        ]);
    }
    #[Route('/{slug}/watchlist', name: 'program_watchlist', requirements: ['id' => '\d+'], methods: ["GET", "POST"])]
    public function addToWatchList(Program $program, UserRepository $userRepository): Response
    {
        /** @var \App\Entity\User */
        $user = $this->getUser();
        if ($user->isInWatchlist($program)) {
            $user->removeWatchlist($program);
        } else {
            $user->addWatchlist($program);
        }
        $userRepository->save($user, true);

        return $this->redirectToRoute('program_list', [
            'slug' => $program->getSlug()
        ]);
    }
    #[Route('/new', name: 'new')]
    public function new(
        CategoryRepository $categoryRepository,
        ProgramRepository $programRepository,
        Request $request,
        RequestStack $requestStack,
        SluggerInterface $slugger,
        MailerInterface $mailer
    ): Response {
        $requestStack->getSession();

        $categories = $categoryRepository->findAll();

        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $program->setOwner($this->getUser());
            $programRepository->save($program, true);

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to($this->getParameter('mailer_to'))
                ->subject('Une nouvelle série vient d\'être publiée')
                ->html($this->renderView('Program/newProgramEmail.html.twig', ['program' => $program]));

            $mailer->send($email);

            $this->addFlash('succes', 'Le nouveau programme à été crée');
            return $this->redirectToRoute('program_index');
        }

        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
            'categories' => $categories,
        ]);
    }
    #[Route('/{slug}/', requirements: ['id' => '\d+'], name: 'program_list')]
    public function showProgram(
        string $slug,
        Program $program,
        CategoryRepository $categoryRepository,
        ProgramDuration $programDuration
    ): Response {
        $categories = $categoryRepository->findAll();

        return $this->render('program/list.html.twig', [
            'id' => $slug,
            'program' => $program,
            'categories' => $categories,
            'programDuration' => $programDuration->calculate($program)
        ]);
    }
    #[Route('/{slug}/season-show/{season}/', requirements: ['id' => '\d+'], name: 'program_season_show')]
    public function showSeasons(
        Program $program,
        Season $season,
        CategoryRepository $categoryRepository
    ) {

        $categories = $categoryRepository->findAll();

        /* var_dump($season); exit(); */

        return $this->render('season/showSeason.html.twig', [
            'season' => $season,
            'categories' => $categories,
            'program' => $program
        ]);
    }
}
