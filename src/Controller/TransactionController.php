<?php

namespace App\Controller;

use App\Entity\TransactionSplitting;
use App\Form\UpdateTransactionSplittingType;
use App\Repository\CategoryRepository;
use App\Repository\TransactionRepository;
use App\Repository\TransactionSplittingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transaction", name="transaction")
     */
    public function index(TransactionSplittingRepository $transactionSplittingRepository, Request $request): Response
    {
        if ($request->get('ajax')) {
            $month = $request->get('month');
            $transactionsSplitting = $transactionSplittingRepository->getTransactionsSplittingWithMonth($month);

            return new JsonResponse([
                'content' => $this->renderView('transaction/_transactions.html.twig', [
                    'transactionsSplitting' => $transactionsSplitting
                ])
            ]);
        }

        $transactionsSplitting = $transactionSplittingRepository->findAll();

        return $this->render('transaction/index.html.twig', [
            'transactionsSplitting' => $transactionsSplitting
        ]);
    }

    /**
     * @Route("/update-transaction/{id<\d+>}", name="update_transaction")
     */
    public function updateTransaction(int $id, TransactionSplittingRepository $transactionSplittingRepository, Request $request)
    {
        if (!$transactionSplitting = $transactionSplittingRepository->find($id)) {
            throw $this->createNotFoundException('La transaction n\'a pas été trouvée');
        }

        $form = $this->createForm(UpdateTransactionSplittingType::class, $transactionSplitting);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transactionSplitting);
            $entityManager->flush();

            return $this->redirectToRoute('transaction');
        }

        return $this->render('transaction/updateTransaction.html.twig', [
            'form' => $form->createView(),
            'transactionSplitting' => $transactionSplitting
        ]);
    }

    /**
     * @Route("/split-transaction/{id<\d+>}", name="split_transaction")
     */
    public function splitTransaction(int $id, CategoryRepository $categoryRepository, TransactionSplittingRepository $transactionSplittingRepository, Request $request)
    {
        if (!$transactionSplitting = $transactionSplittingRepository->find($id)) {
            throw $this->createNotFoundException('La transaction n\'a pas été trouvée');
        }

        if (isset($_POST['category1']) && isset($_POST['category2']) && isset($_POST['amount1']) && isset($_POST['amount2'])) {
            $category1 = $categoryRepository->findOneByName(htmlspecialchars($_POST['category1']));
            $category2 = $categoryRepository->findOneByName(htmlspecialchars($_POST['category2']));
            $amount1 = htmlspecialchars($_POST['amount1']);
            $amount2 = htmlspecialchars($_POST['amount2']);

            $transactionSplitting->setCategory($category1);
            $transactionSplitting->setAmount($amount1);

            $newTransactionSplitting = new TransactionSplitting();
            $newTransactionSplitting->setCategory($category2);
            $newTransactionSplitting->setAmount($amount2);
            $newTransactionSplitting->setTransaction($transactionSplitting->getTransaction());
            $newTransactionSplitting->setBankDate($transactionSplitting->getBankDate());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transactionSplitting);
            $entityManager->persist($newTransactionSplitting);
            $entityManager->flush();

            return $this->redirectToRoute('transaction');
        }

        $categories = $categoryRepository->findAll();

        return $this->render('transaction/split.html.twig', [
            'transactionSplitting' => $transactionSplitting,
            'categories' => $categories
        ]);
    }
}
