<?php


namespace App\Controller;


use App\Entity\Post;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
    // Para passar parâmetros dinâmicos nas 'routes', basta usar chaves {}

    /**
     * @Route("/")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        // obtém o número da página da cessão, ou inicia com 1 se não houver número
        $page = $request->query->getInt('page', 1);

        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

        // sobrescreve $post para add paginação
        $posts = $paginator->paginate($posts, $page, 2);

        // Abaixo, uma render com array enviando para a index o conjunto 'chave' => 'valor'
        return $this->render('index.html.twig', [
            'title' => 'Postagem Teste', // 'chave' => 'valor'
            'posts' => $posts

        ]);
    }


    // passa valor dinâmico via url (slug)
    /**
     * @Route("/post/{slug}", name="single_post")
     */
    public function single($slug) // Passagem pela url
    {
        // busca no banco de dados baseado na coluna slug
        $post = $this->getDoctrine()->getRepository(Post::class)->findOneBySlug($slug);

        return $this->render('single.html.twig',
            [
                'post' => $post

            ]);
    }
}