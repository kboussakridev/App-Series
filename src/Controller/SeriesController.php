<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/series', name: 'series_')]
class SeriesController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    #[Route('', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {
        //todo: aller chercher les séries en BDD
       // $series = $serieRepository->findBy([], ['popularity' => 'DESC', 'vote' => 'DESC'],30);
       $series = $serieRepository->findBestSeries();


        return $this->render('series/list.html.twig',[
            "series" => $series
            ]);

    }

    #[Route('/details/{id}', name: 'details')]
    public function details(int $id, SerieRepository $serieRepository): Response
    {
        //todo: aller chercher la séries en BDD
        $serie = $serieRepository->find($id);

        if (!$serie){
            throw $this->createNotFoundException('oh no!!!');
        }

        return $this->render('series/details.html.twig',[
            'serie' => $serie
        ]);
    }



    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {

        $serie = new Serie();
        $serie->setDateCreated(new \DateTime());

        $serieForm = $this->createForm(SerieType::class, $serie);
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()){


            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success','Serie added! Good job.');
            return $this->redirectToRoute('series_details', ['id' => $serie->getId()]);
        }

        return $this->render('series/create.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);

    }

    #[Route('/demo', name: 'demo')]
    public function demo(EntityManagerInterface $entityManager): Response
    {
        //crée une insrtance de mon entity
        $serie = new Serie();

        //hydrate toutes les propriétés
        $serie->setName('pif');
        $serie->setBackdrop('pif-4420535_640.jpg');
        $serie->setPoster('dafsd');
        $serie->setDateCreated(new \DateTime());
        $serie->setFirstAirDate( new \DateTime('-1 year'));
        $serie->setLastAirDate(new \DateTime('-6 month'));
        $serie->setGenres('drama');
        $serie->setOverview('bla bla bla');
        $serie->setPopularity(123.00);
        $serie->setVote(9.0);
        $serie->setStatus("Canceled");
        $serie->setTmdbId(329432);

        dump($serie);

        $entityManager->persist($serie);
        $entityManager->flush();

        //$entityManager = $this->getDoctrine()->getManager();

        return $this->render('series/create.html.twig');
    }




    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Serie $serie, EntityManagerInterface $entityManager, int $id)
    {
        $serie = $entityManager->getRepository(Serie::class)->find($id);

        $entityManager->remove($serie);
        $entityManager->flush();

        //Ajout du message flash pour validation de la suppression de la serie
        $this->addFlash('success', 'Série supprimée');

        return $this->redirectToRoute('main_home');

    }


}
