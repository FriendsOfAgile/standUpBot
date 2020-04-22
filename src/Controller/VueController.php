<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VueController extends AbstractController
{
    /**
     * @Route("/{vueRouting}", requirements={"vueRouting"="^(?!api|_(profiler|wdt)|admin).*"}, name="vue")
     */
    public function index()
    {
        return $this->render('vue/index.html.twig', [
            'controller_name' => 'VueController',
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        if (!$this->getUser())
            return $this->redirect('/login');
        return $this->redirect('/dashboard');
    }
}
