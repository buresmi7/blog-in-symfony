<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Article;

class AdminController extends Controller
{
    /**
     * @Route("/admin/login", name="loginForm")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
    
        $lastUsername = $authUtils->getLastUsername();
    
        return $this->render('admin/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/admin/", name="adminIndex")
     */
    public function indexAction(Request $request)
    {
        return $this->render('admin/index.html.twig', []);
    }

    /**
     * @Route("/admin/article/list", name="adminArticleList")
     */
    public function articleListAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articleList = $repository->findAll();
        return $this->render('admin/articleList.html.twig', ['articleList' => $articleList]);
    }

    /**
     * @Route("/admin/article/add", name="adminArticleAdd")
     */
    public function articleAddAction(Request $request)
    {
        $form = $this->createArticleForm(null);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $repository = $this->getDoctrine()->getRepository(Article::class);
            $repository->addDisabled($data['title'], $data['text'], $data['tags'], $data['url']);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            return $this->redirectToRoute('adminArticleList');
        }

        return $this->render('admin/articleAdd.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/article/{id}/edit", name="adminArticleEdit")
     */
    public function articleEditAction(Request $request, Article $article)
    {
        $form = $this->createArticleForm($article);
 
        $form->handleRequest($request);
         
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $article->setTitle($data['title']);
            $article->setText($data['text']);
            $article->setTags($data['tags']);
            $article->setUrl($data['url']);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
                          
            return $this->redirectToRoute('adminArticleList');
        }
 
        return $this->render('admin/articleAdd.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private function createArticleForm($article) {
        return $this->createFormBuilder()
            ->add('title', TextType::class, ['data' => $article ? $article->getTitle() : ''])
            ->add('url', TextType::class, ['data' => $article ? $article->getUrl() : ''])
            ->add('text', TextareaType::class, ['data' => $article ? $article->getText() : ''])
            ->add('tags', TextType::class, ['data' => $article ? $article->getTags() : ''])
            ->add('save', SubmitType::class, array('label' => 'Send'))
            ->getForm();
    }
}
