<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends AbstractController
{
    //Liste des articles avec pagination + modification de l'URL
    #[Route('/', name: 'article_index', methods: ['GET'])]
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        // On récupère la page dans l'URL
        $page = (int) $request->query->get('page', 1);

        // Limite du nombre d'articles par page
        $limit = 6;

        // Appelle la pagination dans le repo Article
        $data = $articleRepository->findPaginated($page, $limit);

        // Transmission des données à Twig
        return $this->render('article/index.html.twig', [
            'articles' => $data['items'],
            'page'     => $data['page'],
            'pages'    => $data['pages'],
            'total'    => $data['total'],
        ]);
    }

    // Création d'un nouvel article
    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();

        // On crée le formulaire à partir de ArticleType de Symfony
        $form = $this->createForm(ArticleType::class, $article);

        // On remplit l'objet $article avec les données envoyées par le formulaire
        $form->handleRequest($request);

        // Si le formulaire est envoyé et valide, on enregistre l'article dans la bdd
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($article); // prépare 
            $em->flush();           // enregistre 

            // A la fin, redirige vers la liste
            return $this->redirectToRoute('article_index');
        }

        // Sinon redirection vers le formulaire
        return $this->renderForm('article/new.html.twig', [
            'form' => $form,
        ]);
    }

    // Affichage d'un article
    #[Route('/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    // Modifier un article
    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        // Si valide, ça sauvegarde les changements
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->renderForm('article/edit.html.twig', [
            'form' => $form,
            'article' => $article,
        ]);
    }

    // Supprimer un article
    #[Route('/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Article $article, EntityManagerInterface $em): Response
    {
        $em->remove($article);
        $em->flush();

    // Revient à la liste
    return $this->redirectToRoute('article_index');
}

}
