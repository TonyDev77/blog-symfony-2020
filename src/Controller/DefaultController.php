<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    // Para passar parâmetros dinâmicos nas 'routes', basta usar chaves {}

    /**
     * @Route("/")
     */
    public function index()
    {
        // Lista de array que será impresso via for em 'index.html.twig'
        $posts = [
            [
                'id' => 1,
                'title' => 'Post 1',
                'created_at' => '2019-1-28 19:51:02'
            ],
            [
                'id' => 2,
                'title' => 'Post 2',
                'created_at' => '2019-1-28 19:51:02'
            ],
            [
                'id' => 3,
                'title' => 'Post 3',
                'created_at' => '2019-1-28 19:51:02'
            ]

        ];

        // Abaixo, uma render com array enviando para a index o conjunto 'chave' => 'valor'
        return $this->render('index.html.twig', [
            'title' => 'Postagem Teste', // 'chave' => 'valor'
//            'nome' => 'Tony Silva',
            'posts' => $posts

        ]);
    }


    // passa valor dinâmico via url (slug)
    /**
     * @Route("/post-exemplo/{param}")
     */
    public function single($param) // Passagem pela url
    {
        return $this->render('single.html.twig',
            [
                'chave' => $param

            ]);
    }
}