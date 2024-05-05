<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Entity\Vote;
use App\Repository\VoteRepository;

#[Route('/blog')]
class BlogController extends AbstractController
{
    private $entityManager;


    private $voteRepository;

    public function __construct(EntityManagerInterface $entityManager, VoteRepository $voteRepository)
    {
        $this->entityManager = $entityManager;
        $this->voteRepository = $voteRepository;
    }

    #[Route('/search', name: 'app_blog_search', methods: ['GET'])]
    public function search(Request $request, BlogRepository $blogRepository): Response
    {
        $keyword = $request->query->get('keyword');
    
        // If a keyword is provided, perform the search
        if ($keyword) {
            $blogs = $blogRepository->findByKeyword($keyword);
        } else {
            // If no keyword is provided, redirect to the index page
            return $this->redirectToRoute('app_blog_index');
        }
    
        // Render the search results page
        return $this->render('blog/search.html.twig', [
            'blogs' => $blogs,
            'keyword' => $keyword,
        ]);
    }






    #[Route('/', name: 'app_blog_index', methods: ['GET'])]
    public function index(BlogRepository $blogRepository): Response
    {
        
     
            $blogs = $blogRepository->findSortedByUpvotes();

            // Initialize the array to store vote counts
            $voteCounts = [];

            // Calculate vote counts for each blog post
            foreach ($blogs as $blog) {
                $upvotes = $this->voteRepository->countVotesByType($blog, 'upvote');
                $downvotes = $this->voteRepository->countVotesByType($blog, 'downvote');
                $voteCounts[$blog->getId()] = ['upvotes' => $upvotes, 'downvotes' => $downvotes];
            }

            // Pass the vote counts data to the Twig template along with the blog posts
            return $this->render('blog/index.html.twig', [
                'blogs' => $blogs,
                'voteCounts' => $voteCounts,
            ]);
        }


    #[Route('/new', name: 'app_blog_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $doctor = $this->getUser()->getDoctor();

        if (!$doctor) {
            throw $this->createNotFoundException('You need to be associated with a doctor to create a blog.');
        }

        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $timestamp = time();
            $blog->setCreationDate($timestamp);
            $blog->setDoctor($doctor);
            $this->entityManager->persist($blog);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/{id}', name: 'app_blog_show', methods: ['GET'])]
    public function show(Blog $blog): Response
    {
        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Blog $blog): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_delete', methods: ['POST'])]
    public function delete(Request $request, Blog $blog): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($blog);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_blog_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/upvote', name: 'app_blog_upvote', methods: ['POST'])]
    public function upvote(Blog $blog): Response
    {
        return $this->vote($blog, 'upvote');
    }

    #[Route('/{id}/downvote', name: 'app_blog_downvote', methods: ['POST'])]
    public function downvote(Blog $blog): Response
    {
        return $this->vote($blog, 'downvote');
    }

    private function vote(Blog $blog, string $type): Response
    {
        $user = $this->getUser();
        $entityManager = $this->entityManager;
        
        if (!$user) {
            return new Response('You must be logged in to vote', Response::HTTP_UNAUTHORIZED);
        }

        $currentUser = $this->getUser();

        $existingVote = $entityManager->getRepository(Vote::class)->findOneBy([
            'user' => $currentUser,
            'blog' => $blog,
        ]);

        if ($existingVote ) {

            if ($existingVote->getVoteType() === $type) {
                $entityManager->remove($existingVote);
                $entityManager->flush();
    
                // Redirect back to the post
                return $this->redirectToRoute('app_blog_index', ['id' => $blog->getId()]);
            } else {
                // If the existing vote type is different from the current vote type,
                // update the existing vote type and flush the changes
                $existingVote->setVoteType($type);
                $entityManager->flush();
    
                // Redirect back to the post
                return $this->redirectToRoute('app_blog_index', ['id' => $blog->getId()]);
            }
           
        }else{

        

        $vote = new Vote();
        $vote->setUser($currentUser);
        $vote->setBlog($blog);
        $vote->setvoteType($type);

        $entityManager->persist($vote);
        $entityManager->flush();

        return $this->redirectToRoute('app_blog_index', ['id' => $blog->getId()]);
    }
}
}
