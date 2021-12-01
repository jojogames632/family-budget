<?php

namespace App\Controller;

use App\Form\UpdateTransactionSplittingType;
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
}
