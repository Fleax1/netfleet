<?php

namespace App\Controller;

use App\Entity\Media;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * @Route("/create", name="media", methods={"POST"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager, MediaRepository $mediaRepository): JsonResponse
    {
        $data=$request->getContent();
        $data=json_decode($data,true);

        $name=htmlspecialchars(trim($data["name"]));
        $synopsis=htmlspecialchars(trim($data["synopsis"]));
        $type=htmlspecialchars(trim($data["type"]));

        if (!empty($name) && !empty($synopsis) && !empty($type)) {

            if ($type === "film" || $type === "serie") {

                $mediaExist=$mediaRepository->findBy(['name'=>$name]);

                if (!empty($mediaExist)) {
                    $code=400;
                    $message="le media existe déjà";
                    $http=Response::HTTP_BAD_REQUEST;
                    return new JsonResponse(["message"=>$message,"code"=>$code],$http);

                } else {
                    if(!empty($data)){
                        $media = new Media();
                        $media->setName($name);
                        $media->setSynopsis($synopsis);
                        $media->setType($type);
                        $media->setCreatedAt(new \DateTimeImmutable());
                        $entityManager->persist($media);
                        $entityManager->flush();
                        $message="le media a été ajouté";
                        $code=201;
                        $http=Response::HTTP_CREATED;

                    } else{
                        $code=400;
                        $message="Vous n'avez pas envoyé de donnée !";
                        $http=Response::HTTP_BAD_REQUEST;
                    }
                }

            } else {
                $code=400;
                $message="le type est invalide (serie ou film)";
                $http=Response::HTTP_BAD_REQUEST;
                return new JsonResponse(["message"=>$message,"code"=>$code],$http);
            }



        } else {
            $code=400;
            $message="L'un des champs est vide";
            $http=Response::HTTP_BAD_REQUEST;
        }

        return new JsonResponse(["code"=>$code,"message"=>$message, "data"=>$data],$http);
    }
}
