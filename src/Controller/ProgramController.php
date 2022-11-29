<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgramController extends AbstractController
{
    #[Route('/program/', name: 'program_index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
            'programs' => $programs,
        ]);
    }
    #[Route('/program/{id}/', requirements: ['id'=>'\d+'], name: 'program_list')]
    public function showProgram(int $id, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);
        return $this->render('program/list.html.twig', [
            'id' => $id,
            'program' => $program,
        ]);
    }
}
