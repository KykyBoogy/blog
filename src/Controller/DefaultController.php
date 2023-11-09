<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormBuilder;

class DefaultController extends AbstractController
{
   // / qui va lister l'ensemble de nos articles
    /**
     * @Route("/", name="liste_articles", methods={"GET"})
     */
    public function listeArticles(ArticleRepository $articleRepository):Response {

        //$article = $articleRepository->findByTitre('Article n°1');

        //dump($article);die;

        $articles = $articleRepository->findAll();

        return $this->render( 'default/index.html.twig', [
                                    'articles' => $articles
        ]);

    }

    // /12 Afficher un article
    /**
     * @Route("/{id}", name="vue_article", requirements={"id"="\d+"}, methods={"GET", "POST"})
     */
    public function vueArticle(Article $article, Request $request, EntityManagerInterface $manager) {

        //$article = $articleRepository->findByDateCreation(new \DateTime());

        //dump($article);die;

        $comment = new Comment();

        $comment->setArticle($article);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        //$article = $articleRepository->find($id);

        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('vue_article', ['id' => $article->getId()]);

        }

        return $this->render('default/vue.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/article/ajouter", name="ajout_article")
     */
    public function ajouter(Request $request, EntityManagerInterface $manager) {

        /*$article = new Article();
        $article->setTitre("Titre de l'article");
        $article->setContenu("Ceci est le contenu de l'article");
        $article->setDateCreation(new \DateTime());

        $manager->persist($article);

        $manager->flush();

        die;*/

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($article);

            $manager->flush();

            return $this->redirectToRoute('liste_articles');

        }

        return $this->render("default/ajout.html.twig" , [
            'form' => $form->createView()
        ]);

    }

    public function ajoutCategory (Category $category, Request $request, EntityManagerInterface $manager) {

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($category);

            $manager->flush();

            return $this->redirectToRoute('liste_articles');

        }

    }
}