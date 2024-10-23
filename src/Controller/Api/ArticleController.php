<?php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleRepository;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticleController extends AbstractController {
    #[Route('/api/articles', name: 'api_articles')]
    public function getArtcles(ArticleRepository $repository, SerializerInterface $serialiser){
        $articles = $repository->findAll();
        $jsonArticles = $serialiser->serialize($articles, 'json');
        return new JsonResponse($jsonArticles, 200, [], true);
    }

    #[Route('/api/post_article', methods: ['POST'])]
    public function createarticle(Request $request, SerializerInterface $serialiser, EntityManagerInterface $em){
        $article = $request->getContent();
        $deserialisedArticle = $serialiser->deserialize($article, Article::class, 'json');
        $em->persist($deserialisedArticle);
        $em->flush();
        return new JsonResponse(['message' => 'Article ajouté avec succès'], 201);
    }

    #[Route('/api/update/{id}', methods: ['PUT'])]
    public function updateArtice(EntityManagerInterface $em, ArticleRepository $repository, int $id, SerializerInterface $serialiser, Request $request){
        $article = $repository->find($id);

        if(!$article){
            return new JsonResponse(['message' => 'cet article existe pas'], 404);
        }

        $data = $request->getContent();
        $serialiser->deserialize($data, Article::class, 'json', ['object_to_populate' => $article]);
        $em->flush();

        return new JsonResponse(['message' => 'Article  modifié avec succès'], 201);
    }

    #[Route('/api/delete/{id}', methods: ['DELETE'])]
    public function deletArticle(int $id, EntityManagerInterface $em, ArticleRepository $repository){
        $article = $repository->find($id);
        if(!$article){
            return new JsonResponse(['message' => 'cet article existe pas'], 404);
        }

        $em->remove($article);
        $em->flush();
        return new JsonResponse(['message' => 'Article Supprimé'], 201);
    }
}