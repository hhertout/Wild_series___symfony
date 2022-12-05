<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Season;
use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

class ProgramController extends AbstractController
{
    #[Route('/program/', name: 'program_index')]
    public function index(
        ProgramRepository $programRepository, 
        CategoryRepository $categoryRepository,
        RequestStack $requestStack,
        ): Response
    {
        $requestStack->getSession();
        $categories = $categoryRepository->findAll();
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
            'categories' => $categories
        ]);
    }
    #[Route('/program/new', name: 'new')]
    public function new(
        CategoryRepository $categoryRepository, 
        ProgramRepository $programRepository, 
        Request $request,
        RequestStack $requestStack,
        ): Response
    {
        $requestStack->getSession();
        
        $categories = $categoryRepository->findAll();

        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $programRepository->save($program, true); 

            $this->addFlash('succes', 'Le nouveau programme à été crée');

            return $this->redirectToRoute('program_index');
        }

        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
            'categories' => $categories,
        ]);
    }
    #[Route('/program/{id}/', requirements: ['id'=>'\d+'], name: 'program_list')]
    public function showProgram(int $id, Program $program, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('program/list.html.twig', [
            'id' => $id,
            'program' => $program,
            'categories' => $categories,
        ]);
    }
    #[Route('/program/{program}/season-show/{season}/', requirements: ['id'=>'\d+'], name: 'program_season_show')]
    public function showSeasons( 
        Program $program, 
        Season $season, 
        CategoryRepository $categoryRepository
        )
    {
        
        $categories = $categoryRepository->findAll();

        /* var_dump($season); exit(); */

        return $this->render('season/showSeason.html.twig', [
            'season' => $season,
            'categories' => $categories,
            'program' => $program
        ]);
    }
}
