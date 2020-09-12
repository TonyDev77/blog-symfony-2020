<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
    // Para passar parâmetros dinâmicos nas 'routes', basta usar chaves {}

    /**
     * @Route("/", name="home")
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
            'posts' => $posts,
            'categories' => $this->getCategories()

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

        // Cria formulário associado com entity Comment
        $form = $this->createForm(CommentType::class, new Comment());

        return $this->render('single.html.twig',
            [
                'post' => $post,
                'categories' => $this->getCategories(),
                'form' => $form->createView()

            ]);
    }

    /**
     * @Route("/category/{slug}", name="single_category")
     */
    public function category($slug, PaginatorInterface $paginator, Request $request) // Passagem pela url
    {
        // Paginação
        $page = $request->query->getInt('page', 1);
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBySlug($slug);
        $posts = $paginator->paginate($category->getPosts(), $page, 2);

        return $this->render('category.html.twig',
            [
                'category' => $category,
                'posts' => $posts,
                'categories' => $this->getCategories()

            ]);
    }

    private function getCategories() {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $categories;
    }
}