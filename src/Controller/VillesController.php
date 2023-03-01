<?php

namespace App\Controller;

use App\Entity\Villes;
use App\Repository\VillesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class VillesController extends AbstractController
{
    #[Route('/villes', name: 'liste_villes', methods: ['GET'])]
    public function listevilles(VillesRepository $villesRepository, SerializerInterface $serializer): Response
    {
        $villes = $villesRepository->findAll();
        $jsonContent = $serializer->serialize($villes, 'json', ['groups' => ['personnes', 'conducteurs', 'voitures', 'reservations', 'trajects', 'ville', 'marques']]);
        return new Response($jsonContent, 200, ['Content-Type' => 'application/json']);
    }


    // #[Route('/villes/{id}', name: 'get_ville', methods: ['GET'])]
    // public function getVille(VillesRepository $villesRepository, SerializerInterface $serializer, int $id): JsonResponse
    // {
    //     try {
    //         $ville = $villesRepository->find($id);
    //     } catch (\Exception $e) {
    //         return new JsonResponse(["error" => "An error occurred while retrieving the ville object"], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    //     // Check if the ville object was found
    //     if (!$ville) {
    //         return new JsonResponse(["error" => "The ville object with the specified id was not found"], Response::HTTP_NOT_FOUND);
    //     }
    //     // Serialize the ville object into a JSON string
    //     $jsonVille = $serializer->serialize($ville, 'json', ['groups' => ['personnes', 'conducteurs', 'voitures', 'reservations', 'trajects', 'ville', 'marques']]);

    //     return new JsonResponse($jsonVille, Response::HTTP_OK, [], true);
    // }// end method getVille



    #[Route('/insertvilles', name: 'insert_villes', methods: ['POST'])]
    public function insertVilles(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        // Retrieve the JSON string from the request
        $jsonContent = $request->getContent();
        // Deserialize the JSON string into a ville object
        try {
            $ville = $serializer->deserialize($jsonContent, Villes::class, 'json');
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while deserializing the ville object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Insert the ville object into the database
        try {
            $entityManager->persist($ville);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while inserting the ville object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Return a success response
        return new JsonResponse(["success" => "The ville object was successfully inserted"], Response::HTTP_OK);
    }// end method insertville


    #[Route('/updatevilles/{id}', name: 'update_ville', methods: ['PUT'])]
    public function updateville(Request $request, VillesRepository $villesRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id): JsonResponse
    {
        // Retrieve the JSON string from the request
        $jsonContent = $request->getContent();
        // Deserialize the JSON string into a ville object
        try {
            $ville = $serializer->deserialize($jsonContent, Villes::class, 'json');
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while deserializing the ville object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Check if the ville object was found
        if (!$ville) {
            return new JsonResponse(["error" => "The ville object with the specified id was not found"], Response::HTTP_NOT_FOUND);
        }
        // Update the ville object in the database
        try {
            $entityManager->persist($ville);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while updating the ville object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Return a success response
        return new JsonResponse(["success" => "The ville object was successfully updated"], Response::HTTP_OK);
    }// end method updateville
       

    #[Route('/deletevilles/{id}', name: 'delete_ville', methods: ['DELETE'])]
public function deleteville(villesRepository $villesRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
{
    // Try to retrieve the ville object from the database
    try {
        $ville = $villesRepository->find($id);
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while retrieving the ville object"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    // Check if the ville object was found
    if (!$ville) {
        return new JsonResponse(["error" => "The ville object with the specified id was not found"], Response::HTTP_NOT_FOUND);
    }
    // Delete the "trajets" records that reference this ville object from the database
    $trajets = $ville->getTrajets();
    var_dump($trajets);
    foreach ($trajets as $trajet) {
        $entityManager->remove($trajet);
    }
    // Delete the ville object from the database
    try {
        $entityManager->remove($ville);
        $entityManager->flush();
    } catch (\Exception $e) {
        var_dump($e->getMessage());
        return new JsonResponse(["error" => "An error occurred while deleting the ville object"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    // Return a success response
    return new JsonResponse(["success" => "The ville object was successfully deleted"], Response::HTTP_OK);
}// end method deleteville


// Get All postalCode 

#[Route('/villes/codepostal', name: 'get_all_codepostal', methods: ['GET'])]
public function getAllCodePostaux(VillesRepository $villesRepository): JsonResponse
{
    $codePostaux = $villesRepository->getAllCodePostal();

    return new JsonResponse($codePostaux, Response::HTTP_OK);
}

    
    
    
}
