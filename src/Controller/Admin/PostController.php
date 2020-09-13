<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/posts", name="post_")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $user = $this->getUser(); // obtém usuário
        //$roles = $user->getRoles(); // obtém papel do usuário

        // SE É AUTHOR, CARREGA APENAS AS POSTAGENS DE AUTHOR
        if ($this->isGranted('ROLE_AUTHOR')) {
            $posts = $user->getPosts();
        }
        // outra forma de resolver, sera validando arrays:
        //if (in_array('ROLE_AUTHOR', $roles)) {
        //    $posts = $user->getPosts();
        //}

        // SE É ADMIN, CARREGA TODAS AS POSTAGENS
        if ($this->isGranted('ROLE_ADMIN')) {
            $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();
        }


        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);

    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {

        $post = new Post(); // Esse objeto vai ser a minha entity.

        $form = $this->createForm(PostType::class, $post); // Aqui, criamos o formulário par ser exibido na view
        $form->handleRequest($request); // Para reconhecer se o formulário foi submetido

        if ($form->isSubmitted()){

            // RECEBENDO DADOS DO FORM
            $post = $form->getData(); // Recebe os dados do formulário via post.
            $post->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Recife')));
            $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Recife')));

            //VINCULANDO TABELAS (author/posts)
            //$author = $this->getDoctrine()->getRepository(User::class)->find(1);
            //$post->setAuthor($author);

            // GRAVANDO NO BANCO DE DADOS
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($post);
            $manager->flush();

            $this->addFlash('success', 'Postagem criada com sucesso!'); // Imprime mensagem para o cliente
            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView() // Manda as informações necessárias para a view ser montada com base nesse objeto
        ]);


//        return $this->render('post/create.html.twig');
    }

    /**
     * @Route("/save", name="save")
     * @param Request $request
     */
    public function save(Request $request) // Importa classe Request para envio do formulário
    {
//        $data = $request->request->all(); // Busca todos os dados do html
//
//        $post = new Post(); // Cria o objeto 'Post' para receber os dados do twig.tml
//
//        // Populando os banco de dados
//        $post->setTitle($data['title']);
//        $post->setDescription($data['description']);
//        $post->setContent($data['content']);
//        $post->setSlug($data['slug']);
//        $post->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Recife')));
//        $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Recife')));
//
//        $doctrine = $this->getDoctrine()->getManager(); // Chamando método doctrine
//        $doctrine->persist($post); // Grava na memória os dados do objeto '$post'
//        $doctrine->flush(); // Grava finalmente no BD
//
//        // Chama a seção flash. Ela só aparecerá durante o primeiro reload. Depois será destruía
//        $this->addFlash('succes', 'Post Criado com Sucesso!');
//
//        return $this->redirectToRoute('post_index');
    }


    /**
     * @Route("/edit/{id}", name="edit")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, $id)
    {
        // Variável que chama os recursos do doctrine para fazer operações tendo como referência o 'id'
        $post = $this->getDoctrine()
                     ->getRepository(Post::class)
                     ->find($id);

        $form = $this->createForm(PostType::class, $post)->handleRequest($request);

        if ($form->isSubmitted()){
            $post = $form->getData();
            $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Recife')));

            //VINCULANDO TABELAS (author/posts)
            //$author = $this->getDoctrine()->getRepository(User::class)->find(1);
            //$post->setAuthor($author);

            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash('success', 'Post atualizado com sucesso!');
            return $this->redirectToRoute('post_edit', ['id' => $id]);
        }


        return $this->render('post/edit.html.twig',[
            'form' => $form->createView() // retorna os dados armazenados em '$post' para 'edit.html.twig'
        ]);
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $request ,$id)
    {
//        $data = $request->request->all(); // Busca todos os dados do html
//
//        // Busca pelos dados no repositorio baseado no parâmetro informado ($id)
//        $post = $this->getDoctrine()
//                     ->getRepository(Post::class)
//                     ->find($id);
//
//        // Populando os banco de dados
//        $post->setTitle($data['title']);
//        $post->setDescription($data['description']);
//        $post->setContent($data['content']);
//        $post->setSlug($data['slug']);
//        $post->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Recife')));
//        $post->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Recife')));
//
//        $doctrine = $this->getDoctrine()->getManager(); // Chamando método doctrine
//        $doctrine->flush(); // Grava finalmente no BD
//
//        // Chama a seção flash. Ela só aparecerá durante o primeiro reload. Depois será destruía
//        $this->addFlash('succes', 'Post Atualizado com Sucesso!');
//
//        return $this->redirectToRoute('post_index');
    }


    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove($id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($post);
        $manager->flush();

        // Chama a seção flash. Ela só aparecerá durante o primeiro reload. Depois será destruía
        $this->addFlash('succes', 'Post Removido com Sucesso!');

        return $this->redirectToRoute('post_index');
    }


}


