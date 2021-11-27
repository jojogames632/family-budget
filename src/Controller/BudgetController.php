<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BudgetController extends AbstractController
{
    /**
     * @Route("/budget", name="budget")
     */
    public function index(): Response
    {
        return $this->render('budget/index.html.twig', [

        ]);
    }
}
