<?php

namespace App\Controller;

use App\Repository\AccountHistoryRepository;
use App\Repository\AccountRepository;
use App\Repository\CategoryRepository;
use App\Repository\TransactionSplittingRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GraphicController extends AbstractController
{
    /**
     * @Route("/graphic/{accountId<\d+>}", name="graphic")
     */
    public function index(AccountHistoryRepository $accountHistoryRepository, TransactionSplittingRepository $transactionSplittingRepository, CategoryRepository $categoryRepository, AccountRepository $accountRepository, int $accountId = 3): Response
    {
        $now = new DateTime();
        $startDate = $now->format('Y-01-01');
        $endDate = $now->format('Y-12-31');

        $accountHistoryRows = $accountHistoryRepository->findByAccountAndDates($accountId, $startDate, $endDate);
        $labels = [];
        $incomes = [];
        $spentAmounts = [];
        $balances = [];
        foreach ($accountHistoryRows as $row) {
            $labels[] = $row->getLastDayDate()->format('M');
            $incomes[] = $row->getIncomeAmount();
            $spentAmounts[] = $row->getSpentAmount();
            $balances[] = $row->getLastDayBalance();
        }

        $accounts = $accountRepository->findAll();

        $fullTypeLabels = [];
        $fullTypeMovements = [];
        $fullTypeColors = [];
        $categories = $categoryRepository->findAll();
        foreach ($categories as $category) {
            $fullTypeLabels[] = $category->getName();
            $fullTypeColors[] = $category->getColor();
            $transactionSplittings = $transactionSplittingRepository->findByCategory($category);
            $currentAmount = 0;
            foreach ($transactionSplittings as $transactionSplitting) {
                $currentAmount += $transactionSplitting->getAmount();
            }
            $fullTypeMovements[] = $currentAmount;
        }

        return $this->render('graphic/index.html.twig', [
            'firstDayOfCurrentMonth' => $startDate,
            'lastDayOfCurrentMonth' => $endDate,
            'labels' => json_encode($labels),
            'incomes' => json_encode($incomes),
            'spentAmounts' => json_encode($spentAmounts),
            'balances' => json_encode($balances),
            'accounts' => $accounts,
            'accountId' => $accountId,
            'fullTypeLabels' => json_encode($fullTypeLabels),
            'fullTypeMovements' => json_encode($fullTypeMovements),
            'fullTypeColors' => json_encode($fullTypeColors),
        ]);
    }
}
