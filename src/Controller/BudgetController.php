<?php

namespace App\Controller;

use App\Form\DefUpdateBudgetType;
use App\Form\TempUpdateBudgetType;
use App\Repository\BudgetRepository;
use App\Repository\CategoryRepository;
use App\Repository\TransactionSplittingRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BudgetController extends AbstractController
{
    /**
     * @Route("/budget", name="budget")
     */
    public function index(CategoryRepository $categoryRepository, TransactionSplittingRepository $transactionSplittingRepository, Request $request): Response
    {
        $now = new DateTime();
        $firstDayOfCurrentMonth = $now->format('Y-m-01');
        $lastDayOfCurrentMonth = $now->format('Y-m-t');

        $fixedCategories = $categoryRepository->getSingleTypeCategory('Fixe');
        $semiVariableCategories = $categoryRepository->getSingleTypeCategory('Semi-variable');
        $variableCategories = $categoryRepository->getSingleTypeCategory('Variable');
        
        $realValues = $this->getRealValues($firstDayOfCurrentMonth, $lastDayOfCurrentMonth, $categoryRepository, $transactionSplittingRepository);

        if ($request->get('ajax')) {
            $startDate = $request->get('startDate');
            $endDate = $request->get('endDate');

            $realValues = $this->getRealValues($startDate, $endDate, $categoryRepository, $transactionSplittingRepository);

            return new JsonResponse([
                'content' => $this->renderView('budget/_budget.html.twig', [
                    'fixedCategories' => $fixedCategories,
                    'semiVariableCategories' => $semiVariableCategories,
                    'variableCategories' => $variableCategories,
                    'realValues' => $realValues,
                ])
            ]);
        }

        return $this->render('budget/index.html.twig', [
            'fixedCategories' => $fixedCategories,
            'semiVariableCategories' => $semiVariableCategories,
            'variableCategories' => $variableCategories,
            'realValues' => $realValues,
            'firstDayOfCurrentMonth' => $firstDayOfCurrentMonth,
            'lastDayOfCurrentMonth' => $lastDayOfCurrentMonth,
        ]);
    }

    public function getRealValues($firstDayOfCurrentMonth, $lastDayOfCurrentMonth, $categoryRepository, $transactionSplittingRepository)
    {
        // get associative array with real amount for each category
        $categories = $categoryRepository->findAll();
        $realValues = [];
        foreach ($categories as $category) {
            $categoryName = $category->getName();
            $tsRows = $transactionSplittingRepository->findByCategoryBetweenDates($category, $firstDayOfCurrentMonth, $lastDayOfCurrentMonth);
            $amount = 0;
            foreach ($tsRows as $row) {
                $amount += $row->getAmount();
            }
            $realValues[$categoryName] = $amount;
        }

        return $realValues;
    }

    /**
     * @Route("/update-budget/{id<\d+>}", name="update_budget")
     */
    public function updateBudget(int $id, CategoryRepository $categoryRepository, Request $request)
    {
        if (!$budget = $categoryRepository->find($id)) {
            throw $this->createNotFoundException('La catégorie n\'a pas été trouvée');
        }

        $form = $this->createForm(DefUpdateBudgetType::class, $budget);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($budget);
            $entityManager->flush();

            return $this->redirectToRoute('budget');
        }

        $form2 = $this->createForm(TempUpdateBudgetType::class, $budget);
        $form2->handleRequest($request);
        if ($form2->isSubmitted() && $form2->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($budget);
            $entityManager->flush();

            return $this->redirectToRoute('budget');
        }

        return $this->render('budget/updateBudget.html.twig', [
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'budget' => $budget
        ]);
    }
}
