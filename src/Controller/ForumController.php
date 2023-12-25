<?php

namespace App\Controller;

use App\Entity\Discussions;
use App\Entity\Images;
use App\Services\PicturesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\CreateDiscussionFormType;
use App\Repository\DiscussionsRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/forum', name: 'app_forum_')]
class ForumController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(DiscussionsRepository $discussionsRepository, CategoriesRepository $categoriesRepository): Response
    {
        // Get all discussions from the database ordered by the date they were created (DESC)
        $discussions = $discussionsRepository->findBy([], ['created_at' => 'DESC']);

        $categories = $categoriesRepository->findBy([], ['name' => 'ASC']);
        $categoriesTags = $categoriesRepository->findBy([], ['nb_views' => 'DESC'], 4);

        return $this->render('forum/index.html.twig', compact('discussions', 'categories', 'categoriesTags'));
    }

    #[Route('/create', name: 'create')]
    public function add(Request $request, EntityManagerInterface $em, PicturesService $picturesService): Response
    {
        $discussion = new Discussions();
        $form = $this->createForm(CreateDiscussionFormType::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Get the current date & time (in current timezone immutable)
            $currentDateTime = new \DateTimeImmutable();
            // Set the discussion's registration date & time
            $discussion->setCreatedAt($currentDateTime);
            $discussion->setUpdatedAt($currentDateTime);
        
            // Set the title from the form data
            $discussion->setTitle($form->get('title')->getData());
        
            // Set the users info (id)
            $discussion->setUser($this->getUser());
        
            // Set the category info (id)
            $discussion->setCategory($form->get('category')->getData());
        
            $em->persist($discussion);
            $em->flush();
        
            // We get the images
            $images = $form->get('images')->getData();
                
            foreach ($images as $image) {
        
                // We define the destination folder
                $folder = 'discussionImages';
        
                // We call the image service
                $file = $picturesService->add($image, $folder);

                // We define & create the image
                $img = new Images();
                $img->setFilename($file);
                $img->setFilepath($folder);
                $img->setUser($this->getUser());
                $img->setDiscussion($discussion);
                $img->setUploadedAt($currentDateTime);
        
                $em->persist($img);
            }
        
            $em->flush();
        }
        

        return $this->render('forum/create.html.twig', [
            'createDiscussionForm' => $form->createView(),
        ]);
    }
}
