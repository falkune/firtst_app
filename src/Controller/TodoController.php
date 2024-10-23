<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Todo;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;

class TodoController extends AbstractController {
    #[Route('/todo', name: 'app_todo')]
    public function index(): Response {
        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
        ]);
    }

    #[Route('/addTask', name: 'app_add_task')]
        public function addTask(EntityManagerInterface $em) {
            $todo = new Todo();
            $todo->setTitle('Task thre')->setCompleted(false);

            $em->persist($todo);
            $em->flush();

            return new Response("Todo crée avec succes");
        }

    #[Route('/getList', name: 'app_get_list')]
    public function getList(TodoRepository $repository){
        $todos = $repository->findAll();
        return $this->render('todo/index.html.twig', [
            'todos' => $todos
        ]);
    }

    #[Route('/update/{id}', name: 'app_update')]
    public function update(EntityManagerInterface $em, TodoRepository $repository, int $id){
        $todo = $repository->find($id);
        $todo->setTitle('updated Todo');
        $todo->setCompleted(true);

        $em->flush();

        return new Response("Todo mis a jour!");
    }

    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete(EntityManagerInterface $em, TodoRepository $repository, int $id){
        $todo = $repository->find($id);
        if(!$todo){
            return new Response("Cette todo n'existe pas");
        }
        $em->remove($todo);
        $em->flush();

        $todos = $repository->findAll();
        return $this->render('todo/index.html.twig', [
            'todos' => $todos
        ]);
    }
}
