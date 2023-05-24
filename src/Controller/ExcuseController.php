<?php

namespace App\Controller;

use App\Repository\ExcuseRepository;
use App\Entity\Excuse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExcuseController extends AbstractController
{

    #[Route('/', name: 'app_excuse_index', methods: ['GET'])]
    public function index(ExcuseRepository $excuseRepository): Response
    {
        $excuse = $excuseRepository->findAll();

        return $this->render('excuse/index.html.twig', [
            "excuse"=>$excuse,
        ]);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/random', name: 'app_excuse_rand', methods: ['GET'])]
    public function random(ManagerRegistry $doctrine){
        $excuseRepository = $doctrine->getRepository(Excuse::class);
        $excuseCount = $excuseRepository->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $randomId = rand(1, $excuseCount);
        $excuse = $excuseRepository->find($randomId);
        return new JsonResponse([
            'message' => $excuse->getMessage()
        ]);
    }

    #[Route('/lost', name: 'app_excuse_lost' )]
    public function lost(): Response
    {
        header("Refresh: 5; URL=http://127.0.0.1:8000/");

        return  $this->render('excuse/lost.html.twig', []);
    }

    #[Route('/excuse/{http_code}', name: 'app_excuse' , methods: ['GET'])]
    public function show(int $http_code,ManagerRegistry $doctrine): Response
    {
        $excuseRepository = $doctrine->getRepository(Excuse::class);
        $excuse = $excuseRepository->findOneBy(['http_code' => $http_code]);
        if (!$excuse){
            throw $this->createNotFoundException("$http_code no excuse for this code");
        }
        $data = [
            'excuse'=>$excuse,
        ];
        return $this->render('excuse/http_code.html.twig',$data);
    }

    #[Route('/list', name: 'app_excuse_list' , methods: ['GET'])]
    public function showAll(ExcuseRepository $excuseRepository): Response
    {
        $excuse = $excuseRepository->findAll();

        return $this->render('excuse/list.html.twig', [
           'excuse' => $excuse,
       ]);
    }

}
