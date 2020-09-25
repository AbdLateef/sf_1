<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/MainController.php',
        // ]);
        
        //return new Response('<h1>Tes My Symfony</h1>');

        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/custom/{name}", name="custom")
     */
    public function custom(Request $request)
    {
        $name = $request->get('name');
        // return new Response('Welcome ! '.$name);
        return $this->render('home/custom.html.twig', [ 'name' => $name]);
    }
}
