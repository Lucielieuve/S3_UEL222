<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/', name: 'blog')]
    public function index(Request $request): Response
    {
        // Accueil correspond à la liste des articles via la page /article
        $page = max(1, (int) $request->query->get('page', 1));
        return $this->redirectToRoute('article_index', ['page' => $page]);
    }
}
