<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Event;
use App\Repository\EventRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\EventType;
use Symfony\Component\HttpFoundation\Request;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    #[Route('/new_event', name: 'app_new_event')]
    public function newEvent(EntityManagerInterface $em, EventRepository $repository){
        $event = new Event();
        $event->setTitre('Nouvel evenement')
            ->setDecription('Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem animi nemo nesciunt deleniti veniam. Quae cumque earum adipisci maiores doloribus maxime officiis? Asperiores fugiat quos corporis magni, sequi nulla debitis.')
            ->setDate(new DateTime("2024-10-22"))
            ->setLieu('paris la defence');
        // la methode persiste (symfony garde cet objet en memoire)
        $em->persist($event);
        // la methode flush permet d'executer la requete afin de sauvegarder les infos
        $em->flush();

        $events = $repository->findAll();
        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/getEvents', name: 'app_getEvent')]
    public function getEvent(EventRepository $repository){
        $events = $repository->findAll();
        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/update_event/{id}', name: 'app_update_event')]
    public function update(EventRepository $repository, EntityManagerInterface $em, int $id){
        $event = $repository->find($id);
        $event->setTitre('modifier le titre');
        $em->flush();


        $events = $repository->findAll();
        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/delete_event/{id}', name: 'app_delete_event')]
    public function delete(EventRepository $repository, EntityManagerInterface $em, int $id){
        $event = $repository->find($id);

        if(!$event){
            return new Response("Cet evenement n'existe pas!");
        }

        $em->remove($event);
        $em->flush();

        $events = $repository->findAll();
        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/create', name: 'app_create')]
    public function create(Request $request, EntityManagerInterface $em){
        // creer une instance du formulaire EventType
        $form = $this->createForm(EventType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
        }

        
        return $this->render('event/create.html.twig', [
            'form' => $form // transmetre le formulaire au template
        ]);
    }
}

