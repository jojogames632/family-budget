<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(Request $request, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setIsActive(true);
            $category->setUnactiveDate(null);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category');
        }

        return $this->render('category/index.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/update-category/{id<\d+>}", name="update_category")
     */
    public function updateCategory(int $id, Request $request, CategoryRepository $categoryRepository)
    {
        if (!$category = $categoryRepository->find($id)) {
            throw $this->createNotFoundException(sprintf('La catégorie avec l\'id %s n\'a pas été trouvée'));
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category');
        }
        
        return $this->render('category/updateCategory.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/delete-category/{id<\d+>}", name="delete_category")
     */
    public function deleteCategory(int $id, CategoryRepository $categoryRepository)
    {
        if (!$category = $categoryRepository->find($id)) {
            throw $this->createNotFoundException(sprintf('La catégorie avec l\'id %s n\'a pas été trouvée'));
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        
        return $this->redirectToRoute('category');
    }
}
