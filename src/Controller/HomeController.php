<?php

namespace App\Controller;

use App\Repository\MediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(MediaRepository $mediaRepository): Response
    {
        $media=$mediaRepository->findAll();

        return $this->render('home/index.html.twig', [
            'medias' => $media,
        ]);
    }
}
