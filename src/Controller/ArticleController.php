<?php

namespace App\Controller;

use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ArticleRepository;
use App\Entity\Article;

class ArticleController extends AbstractController {
    #[Route('/article', name: 'app_article')]
    // #[IsGranted('ADMIN')]
    public function index(ArticleRepository $repository): Response {
        $articles = $repository->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/create_article', name: 'app_create_article')]
    public function create(EntityManagerInterface $em, Request $request){
        $form = $this->createForm(ArticleType::class);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $file = $form->get('img')->getData();

            if($file){
                $fileName = uniqid().'.'.$file->guessExtension();

                $file->move($this->getParameter('upload_directory'), $fileName);
            }

            $data = $form->getData();
            $em->persist($data);
            $em->flush();
        }

        return $this->render('article/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('update_article/{id}', 'app_update_article')]
    public function update(int $id, EntityManagerInterface $em, ArticleRepository $repository, Request $request){
        
        $form = $this->createForm(ArticleType::class);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $data = $form->getData();
            $article = $repository->find($data->getId());
            $article->setTitre($data->getTitre())
                    ->setDescription($data->getDescription());
            $em->flush();
        }

        $article = $repository->find($id);

        return $this->render('article/updat.html.twig', [
            'form' => $form,
            'article' => $article
        ]);
    }

    #[Route('delete_article/{id}', name: 'app_delete_article')]
    public function delete(int $id, EntityManagerInterface $em, ArticleRepository $repository){
        $article = $repository->find($id);
        $em->remove($article);
        $em->flush();


        $articles = $repository->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
