<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
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
}
