<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    public function __construct(private PostRepository $postRepository, private ManagerRegistry $doctrine)
    {
    }

    #[Route('/posts', name: 'app_posts')]
    public function index(): Response
    {
        $posts = $this->postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            ]);
    }

    #[Route('/posts/create', name: 'post_create')]
    public function addPost(Request $request, Slugify $slugify)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($slugify->slugify($post->getTitle()));
            $post->setCreatedAt(new \DateTimeImmutable());

            $em = $this->doctrine->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('app_posts');
        }

        return $this->render('post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{slug}/edit', name: 'post_edit')]
    public function edit(Post $post, Request $request, Slugify $slugify)
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($slugify->slugify($post->getTitle()));
            $em = $this->doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute('post_show', [
                'slug' => $post->getSlug(),
            ]);
        }

        return $this->render('post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/posts/{slug}/delete', name: 'blog_post_delete')]
    public function delete(Post $post)
    {
        $em = $this->doctrine->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('app_posts');
    }

    #[Route('posts/search', name: 'blog_search')]
    public function search(Request $request)
    {
        $query = $request->query->get('q');
        $posts = $this->postRepository->searchByQuery($query);

        return $this->render('post/query_post.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/post/{slug}', name: 'post_show')]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
