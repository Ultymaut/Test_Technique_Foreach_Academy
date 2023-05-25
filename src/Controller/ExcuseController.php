<?php

namespace App\Controller;

use App\Form\ExcuseNewType;
use App\Repository\ExcuseRepository;
use App\Entity\Excuse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExcuseController extends AbstractController
{

    /**
     * @param ExcuseRepository $excuseRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * Controller pour la page index
     */
    #[Route('/', name: 'app_excuse_index', methods: ['GET'])]
    public function index(ExcuseRepository $excuseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $excuse=$excuseRepository->findAll();


        return $this->render('excuse/index.html.twig', [
            "excuse"=>$excuse,
        ]);
    }

    /**
     * @param ManagerRegistry $doctrine
     * @return JsonResponse
     * @throws NoResultException
     * @throws NonUniqueResultException
     * Controller qui est relié a la page index est qui permet de chercher de maniere random les données au sein de la table
     */
    #[Route('/excuse/random', name: 'app_excuse_rand', methods: ['GET'])]
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

    /**
     * @return Response
     * Simple controller de la page lost avec un refresh qui renvoie au bout de 5 secondes vers la page index
     */
    #[Route('/lost', name: 'app_excuse_lost' )]
    public function lost(): Response
    {
        header("Refresh: 5; URL=http://127.0.0.1:8000/");

        return  $this->render('excuse/lost.html.twig', []);
    }

    /**
     * @param int $http_code
     * @param ManagerRegistry $doctrine
     * @return Response
     * Controller qui permet la recherche d'une excuse par son code http_code depuis l'url avec un message d'erreur personnalisé
     */
    #[Route('/excuse/code/{http_code}', name: 'app_excuse' , methods: ['GET'])]
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


    /**
     * @param ExcuseRepository $excuseRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * Controller pour la page list qui montre toutes les excuses au sein d'un tableau on y retrouve aussi la création du formulaire pour le modal de création d'excuse et un total des excuses
        Avec message de réussite de la création
     */
    #[Route('/excuse/list', name: 'app_excuse_list' , methods: ['GET','POST'])]
    public function showAll(ExcuseRepository $excuseRepository,PaginatorInterface $paginator, Request $request,EntityManagerInterface $manager,ManagerRegistry $doctrine): Response
    {
        $new = new Excuse();
        $form = $this->createForm(ExcuseNewType::class, $new);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $new = $form->getData();
            $manager->persist($new);
            $manager->flush();
            // do some sort of processing
            $this->addFlash(
                'success',
                'l\'excuse à été créé avec succès !'
            );

            return $this->redirectToRoute('app_excuse_list');
        }

        $excuse = $paginator->paginate(
            $excuseRepository->findAll(),
            $request->query->getInt('page', 1),
            10,
        );

       $query= $doctrine->getRepository(Excuse::class);
       $total = $query->createQueryBuilder('e')
           ->select('count(e)')
           ->getQuery()
           ->getSingleScalarResult();
        return $this->render('excuse/list.html.twig', [
            'excuse' => $excuse,
            'form'=>$form->createView(),
            'total' => $total,
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * Controller pour une page de créarion d'excuse en plus du modal
     */
    #[Route('/excuse/new', name: 'app_excuse_new' , methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {

        $new = new Excuse();
        $form = $this->createForm(ExcuseNewType::class, $new);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $new = $form->getData();
            $manager->persist($new);
            $manager->flush();
            $this->addFlash(
                'success',
                'l\'excuse à été créé avec succès !'
            );

            return $this->redirectToRoute('app_excuse_list');
        }

        return $this->render('excuse/new.html.twig',
            [
                'excuse' => $new,
                'form' => $form->createView()
            ]);

    }

    /**
     * @param Excuse $excuse
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * Controller pour la page avec le form de modification plus message de réussite
     */
    #[Route ('/excuse/modif/{id}', name : 'excuse_modif' , methods: ['GET', 'POST'])]
    public function edit(Excuse $excuse, Request $request, EntityManagerInterface $manager) : Response
    {


        $form = $this->createForm(ExcuseNewType::class, $excuse);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $excuse = $form->getData();
            $manager->persist($excuse);
            $manager->flush();
            $this->addFlash(
                'success',
                'L\'excuse à été modifier avec succès !'
            );

            return $this->redirectToRoute('app_excuse_list');
        }

        return $this->render('excuse/modif.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * @param EntityManagerInterface $manager
     * @param Excuse $excuse
     * @return Response
     * Controller qui permet la supression d'une excuse par sont id avec message d'erreur ou de réussite
     */
    #[Route('/excuse/delete/{id}' , 'excuse_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager , Excuse $excuse) :Response
    {

        if (!$excuse){
            $this->addFlash(
                'warning',
                "L'user n'a pas été trouvé!"
            );
            return $this->redirectToRoute('user.app_excuse_index');
        }

        $manager->remove($excuse);
        $manager->flush();
        $this->addFlash(
            'success',
            'L\'excuse à été supprimé avec succès !'
        );
        return $this->redirectToRoute('app_excuse_index');
    }
}
