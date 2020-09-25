<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Form\PostType;

/**
 * @Route("/post", name="post.")
 */

class PostController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository)
    {
    	$posts = $postRepository->findAll();
    	
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
    	// create new post with title
    	$post = new Post();
    	$form = $this->createForm(PostType::class, $post);
    	$form->handleRequest($request);
    	if ($form->isSubmitted()) {
    		// entitiy manager
    		$em = $this->getDoctrine()->getManager();
    		
    		/** @var UploadedFile $file */
    		$file = $request->files->get('post')['attachment'];
    		
    		if ($file) {
    			
    			$filename = md5(uniqid()).'.'.$file->guessClientExtension();
    			$file->move(
    				$this->getParameter('uploads_dir'),
    				$filename
    			);
    			
    			$post->setImage($filename);
    			$em->persist($post);
    			$em->flush();
    		}
    		
    		return $this->redirect($this->generateUrl('post.index'));
    	}
    	
    	return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    	
    }

    /**
     * @Route("/show/{id}", name="show")
     * @return Response
     */
    public function show(Post $post) 
    {
    	return $this->render('post/show.html.twig', [
    		'post' => $post
    	]);
    }

	/**
     * @Route("/delete/{id}", name="delete")
     * @return Response
     */
    public function remove(Post $post) 
    {
    	$em = $this->getDoctrine()->getManager();
    	$em->remove($post);
    	$em->flush();

    	$this->addFlash('success', 'Post Removed');

    	return $this->redirect($this->generateUrl('post.index'));
    }
}
