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

    /**
     * @Route("/getall", name="get_all")
     */
    public function get_all(MediaRepository $mediaRepository): JsonResponse
    {
        $medias=$mediaRepository->findAll();
        $data=array();

        if(empty($medias)){
            $code=400;
            $message="Aucun Media trouvé";
            $http=Response::HTTP_BAD_REQUEST;
        }else{
            foreach ($medias as $media){
                array_push($data,["id"=>$media->getId(),"Nom"=>$media->getName(),"Synopsis"=>$media->getSynopsis(),"Type"=>$media->getType(),"created_at"=>$media->getCreatedAt()]);
            }
            $code=200;
            $message = "Liste de tout nos medias";
            $http=Response::HTTP_OK;
        }


        return new JsonResponse(["code"=>$code, "message"=>$message,"data"=>$data],$http);
    }

    /**
     * @Route("/get/{id}", name="get_by_id")
     */
    public function get_by_id(MediaRepository $mediaRepository,$id=null): JsonResponse
    {
            $media = $mediaRepository->find(['id' => $id]);
            if (empty($media)) {
                $code = 400;
                $message = "Aucun Résultat";
                $http = Response::HTTP_BAD_REQUEST;
                return new JsonResponse(["message"=>$message,"code"=>$code],$http);
            } else {
                $data=["id" => $media->getId(), "Nom" => $media->getName(), "Synopsis" => $media->getSynopsis(), "Type" => $media->getType(), "created_at" => $media->getCreatedAt()];
                $code = 200;
                $message = "ok";
                $http = Response::HTTP_OK;
                return new JsonResponse(["message"=>$message,"code"=>$code,"data"=>$data],$http);
            }
    }
}
