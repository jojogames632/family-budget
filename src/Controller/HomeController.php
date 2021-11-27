<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("", name="home")
     */
    public function index(AccountRepository $accountRepository)
    {
        $accounts = $accountRepository->findAll();

        return $this->render('home/index.html.twig', [
           'accounts' => $accounts
        ]);
    }

    /**
     * @Route("/add-account", name="add_account")
     */
    public function addAccount(Request $request, AccountRepository $accountRepository)
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $account->setUpdateDate(new DateTime());
            $account->setName(ucfirst($account->getName()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($account);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('home/addAccount.html.twig', [
           'form' => $form->createView()
        ]);
    }
}
