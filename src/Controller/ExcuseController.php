<?php

namespace App\Controller;

use App\Repository\ExcuseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExcuseController extends AbstractController
{
    #[Route('/', name: 'app_excuse_index')]
    public function index(ExcuseRepository $excuseRepository): Response
    {
        $excuseRepository->findAll();

        return $this->render('excuse/index.html.twig', [

        ]);
    }

    #[Route('/lost', name: 'app_excuse_lost' )]
    public function lost(): Response
    {
        header("Refresh: 5; URL=http://127.0.0.1:8000/");

        return  $this->render('excuse/lost.html.twig', []);
    }

    #[Route('/$http_code', name: 'app_excuse')]
    public function http_code(): Response
    {

        return $this->render('excuse/http_code.html.twig', [
            'controller_name' => 'ExcuseController',
        ]);
    }

}
