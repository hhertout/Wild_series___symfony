<?php
// src/Controller/ProgramController.php
namespace App\Controller;


use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category/', name: 'category_index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    #[Route('/category/{categoryName}/', requirements: ['category'=>'{categoryName}'], name: 'category_show')]
    public function showCategory(string $categoryName, ProgramRepository $programRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        $category = $categoryRepository->findBy(['name' => $categoryName]);

        if (!$category) {
            throw $this->createNotFoundException('The Category ' . $categoryName . ' does not exist');
        } else {

            $programs = $programRepository->findBy(
                ['category' => $category], 
                ['title' => 'DESC'], 
                3
            );
        }

        return $this->render('category/show.html.twig', [
            'programs' => $programs,
            'categories' => $categories,
        ]);
    }
}