<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/categories", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $category
        ]);

    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request) {

        $category = new Category(); // Esse objeto vai ser a minha entity.

        $form = $this->createForm(CategoryType::class, $category); // Aqui, criamos o formulário par ser exibido na view
        $form->handleRequest($request); // Para reconhecer se o formulário foi submetido

        if ($form->isSubmitted() && $form->isValid()){

            $category = $form->getData(); // Recebe os dados do formulário via post.
            //$category->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Recife')));
            //$category->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Recife')));

            // GRAVANDO NO BANCO DE DADOS
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();

            $this->addFlash('success', 'Categoria criada com sucesso!'); // Imprime mensagem para o cliente
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/create.html.twig', [
            'form' => $form->createView() // Manda as informações necessárias para a view ser montada com base nesse objeto
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, $id) {
        $category = $this->getDoctrine()
                        ->getRepository(Category::class)
                        ->find($id);

        $form = $this->createForm(CategoryType::class, $category); // Aqui, criamos o formulário par ser exibido na view
        $form->handleRequest($request); // Para reconhecer se o formulário foi submetido

        if ($form->isSubmitted() && $form->isValid()){

            $category = $form->getData(); // Recebe os dados do formulário via post.
            //$category->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Recife')));
            //$category->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Recife')));

            // GRAVANDO NO BANCO DE DADOS
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash('success', 'Categoria atualizada com sucesso!'); // Imprime mensagem para o cliente
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView() // Manda as informações necessárias para a view ser montada com base nesse objeto
        ]);
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove($id) {
        $category = $this->getDoctrine()
                        ->getRepository(Category::class)
                        ->find($id);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($category);
        $manager->flush();

        // Chama a seção flash. Ela só aparecerá durante o primeiro reload. Depois será destruía
        $this->addFlash('succes', 'Categoria Removido com Sucesso!');

        return $this->redirectToRoute('category_index');
    }
}
