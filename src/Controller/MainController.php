<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Component\HttpFoundation\Response;
class MainController extends AbstractController
{

    #[Route('/', name: 'main_home')]
    public function home(): Response
    {
        return $this->render('main/home.html.twig', [
            'controller_name' => 'MainControllerController',
        ]);
    }

    #[Route('/test', name: 'main_test')]
    public function test(): Response
    {
        return $this->render('main/test.html.twig');
    }
}