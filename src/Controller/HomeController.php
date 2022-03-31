<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Category;
use App\Entity\Transaction;
use App\Entity\TransactionSplitting;
use App\Form\AccountType;
use App\Form\FileFormType;
use App\Repository\AccountRepository;
use App\Repository\CategoryRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/{id<\d+>}", name="home")
     */
    public function index(int $id = 1, AccountRepository $accountRepository, Request $request, SluggerInterface $slugger, CategoryRepository $categoryRepository)
    {   
        $accounts = $accountRepository->findAll();
        $account = $accountRepository->find($id);

        $form = $this->createForm(FileFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $transactionFile = $form->get('transactionFilename')->getData();

            $originalFilename = pathinfo($transactionFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $transactionFile->guessExtension();

            try {
                $transactionFile->move(
                    $this->getParameter('transaction_directory'),
                    $newFilename
                );                
                
                $this->dataExtract($newFilename, $account, $categoryRepository);
                
            } catch (FileException $e) {
                throw $this->createNotFoundException('Erreur lors du téléchargement de votre fichier');
            }
        }

        return $this->render('home/index.html.twig', [
           'accounts' => $accounts,
           'account' => $account,
           'form' => $form->createView()
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

    public function dataExtract(string $newFilename, Account $account, CategoryRepository $categoryRepository) {
        $fileContent = utf8_decode(file_get_contents('data/' . $newFilename));
        $fileContentArray = explode(PHP_EOL, $fileContent);

        // search end date and update account
        $fullEndDate = $fileContentArray[37];
        $cleanEndDate = substr($fullEndDate, 7); // delete tag
        $year = substr($cleanEndDate, 0, 4);
        $month = substr($cleanEndDate, 4, 2);
        $day = substr($cleanEndDate, 6, 2);
        $dateFormat = DateTime::createFromFormat('Y-m-d', $year . '-' . $month . '-' . $day);
        $account->setUpdateDate($dateFormat);

        // clean file content header and footer
        $headerEndIndex = array_search('<STMTTRN>', $fileContentArray);
        for ($i = 0; $i < $headerEndIndex; $i++) {
            array_shift($fileContentArray);
        }
        for ($i = 0; $i <= 13; $i++) { // 13 = footer elements
            array_pop($fileContentArray);
        }

        while(count($fileContentArray) > 0) {
            $firstOpenTagIndex = array_search('<STMTTRN>', $fileContentArray);
            $firstCloseTagIndex = array_search('</STMTTRN>', $fileContentArray);
            $transaction = [];
            for ($i = $firstOpenTagIndex; $i < $firstCloseTagIndex; $i++) {
                $transaction[] = htmlspecialchars($fileContentArray[$i]);
            }
            
            // format data
            $fullPostedDate = $transaction[2];
            $postedDate = substr($fullPostedDate, 16);
            $year = substr($postedDate, 0, 4);
            $month = substr($postedDate, 4, 2);
            $day = substr($postedDate, 6, 2);
            $dateFormat = DateTime::createFromFormat('Y-m-d', $year . '-' . $month . '-' . $day);
            
            $fullName = $transaction[5];
            $nameWithDate = substr($fullName, 12);
            $slashIndex = strpos($nameWithDate, '/');
            if ($slashIndex) {
                $name = trim(substr($nameWithDate, 0, $slashIndex - 2));
            } else {
                // no date so trim isnt needed
                $name = $nameWithDate;
            }
            
            $fullAmount = $transaction[3];
            $amount = floatval(substr($fullAmount, 14));
            
            $balance = $account->getBalance();

            // create transactions entities
            $newTransaction = new Transaction();
            $newTransaction->setAccount($account);
            $newTransaction->setBankDate($dateFormat);
            $newTransaction->setName($name);
            $newTransaction->setAmount($amount);
            $newTransaction->setBalance($balance + $amount);

            // create transaction splitting entities
            $newTransactionSplitting = new TransactionSplitting();
            $newTransactionSplitting->setTransaction($newTransaction);
            $noCategoryEntity = $categoryRepository->findOneByName('À catégoriser');  // temp
            $newTransactionSplitting->setCategory($noCategoryEntity);
            $newTransactionSplitting->setRecurringCategory(null);
            $newTransactionSplitting->setAmount($amount);
            $newTransactionSplitting->setBankDate($dateFormat);

            // update account
            $account->setBalance($newTransaction->getBalance());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($account);
            $entityManager->persist($newTransaction);
            $entityManager->persist($newTransactionSplitting);
            $entityManager->flush();

            for ($i = 0; $i <= $firstCloseTagIndex; $i++) {
                array_shift($fileContentArray);
            }
        }
    }
}
