<?php

namespace App\Controller;

use App\Entity\Villes;
use App\Entity\Personnes;
use App\Repository\PersonnesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\validator;

#[Route('/api')]
class PersonnesController extends AbstractController
{
    #[Route('/personnes', name: 'get_all_personnes', methods: ['GET'])]
    public function getAllpersonnes(PersonnesRepository $personnesRepository, SerializerInterface $serializer): JsonResponse
    {
        // Retrieve all personnes objects from the repository
        try {
            $personnes = $personnesRepository->findAll();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while retrieving the personnes objects from the database"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Serialize the personnes objects into a JSON string
        $jsonpersonnes = $serializer->serialize($personnes, 'json', ['groups' => ['conducteurs', 'reservations', 'personnes', 'trajets', 'villes', 'reservations', 'voitures', 'personnes']]);
        // Return a JSON response
        return new JsonResponse($jsonpersonnes, Response::HTTP_OK, [], true);
    }// end method getAllpersonnes


// get Personnes by ID
    #[Route('/personnes/{id}', name: 'getOne_personnes', methods: ['GET'])]
    public function SelectPersonnes(PersonnesRepository $personnesRepository, SerializerInterface $serializer, int $id): JsonResponse
    {
        try {
            $personnes = $personnesRepository->find($id);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while retrieving the personnes object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Check if the personnes object was found
        if (!$personnes) {
            return new JsonResponse(["error" => "The personnes object with the specified id was not found"], Response::HTTP_NOT_FOUND);
        }
        // Serialize the personnes object into a JSON string
        $jsonpersonnes = $serializer->serialize($personnes, 'json', ['groups' => ['conducteurs', 'reservations', 'personnes', 'trajets', 'villes', 'reservations', 'voitures', 'personnes']]);
        // Return a JSON response
        return new JsonResponse($jsonpersonnes, Response::HTTP_OK, [], true);
    }// end method getOnePersonnes


    // Update a personnes object in the database
    #[Route('/updatepersonnes/{id}', name: 'update_personnes', methods: ['PUT'])]
    public function updatepersonnes(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator, int $id): JsonResponse
    {
        // Retrieve the JSON string from the request
        $jsonContent = $request->getContent();
        // Deserialize the JSON string into a personnes object
        try {
            $personnes = $serializer->deserialize($jsonContent, personnes::class, 'json');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return new JsonResponse(["error" => "An error occurred while deserializing the JSON string into a personnes object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Check if the personnes object is valid
        $errors = $validator->validate($personnes);
        if ($errors->count() > 0) {
            return new JsonResponse(["error" => (string) $errors], Response::HTTP_BAD_REQUEST);
        }
        // Retrieve the personnes object from the database
        try {
            $personnesFromDb = $entityManager->getRepository(personnes::class)->find($id);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while retrieving the personnes object from the database"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Check if the personnes object was found
        if (!$personnesFromDb) {
            return new JsonResponse(["error" => "The personnes object with the specified id was not found"], Response::HTTP_NOT_FOUND);
        }
        // Update the personnes object in the database
        try {
            $personnesFromDb->setPrenom($personnes->getPrenom());
            $personnesFromDb->setNom($personnes->getNom());
            $personnesFromDb->setTelephone($personnes->getTelephone());
            $personnesFromDb->setVille($personnes->getVille());
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while updating the personnes object in the database: " . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);

            //return new JsonResponse(["error" => "An error occurred while updating the personnes object in the database"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Serialize the personnes object into a JSON string
        $jsonpersonnes = $serializer->serialize($personnesFromDb, 'json', ['groups' => ['conducteurs', 'reservations', 'personnes', 'trajets', 'villes', 'reservations', 'voitures', 'personnes']]);
        // Return a JSON response
        return new JsonResponse($jsonpersonnes, Response::HTTP_OK, ["Location" => "/api/updatepersonnes/" . $personnes->getId()], true);
    }// end method updatepersonnes

    
 //Delete Personnes
 #[Route('/personnes/{id}', name: 'delete_personnes', methods: ['DELETE'])]
 public function deletepersonnes(PersonnesRepository $personnesRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
 {
     // Retrieve the personnes object from the database
     try {
         $personnes = $personnesRepository->find($id);
     } catch (\Exception $e) {
         return new JsonResponse(["error" => "An error occurred while retrieving the personnes object"], Response::HTTP_INTERNAL_SERVER_ERROR);
     }
     // Check if the personnes object was found
     if (!$personnes) {
         return new JsonResponse(["error" => "The personnes object with the specified id was not found"], Response::HTTP_NOT_FOUND);
     }
     // Delete the personnes object from the database
     try {
         $entityManager->remove($personnes);
         $entityManager->flush();
     } catch (\Exception $e) {
         return new JsonResponse(["error" => "An error occurred while deleting the personnes object"], Response::HTTP_INTERNAL_SERVER_ERROR);
     }
     // Return a success response
     return new JsonResponse(["success" => "The personnes object was successfully deleted"], Response::HTTP_OK);
 }// end method deletepersonnes


// // Create a new personnes object in the database
// #[Route('/insertpersonnes', name: 'insert_personnes', methods: ['POST'])]
// public function insertpersonnes(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
// {
//     // Retrieve the JSON string from the request
//     $jsonContent = $request->getContent();

//     // Decode the JSON string into an associative array
//     $data = json_decode($jsonContent, true);

//     // Create a new personnes object
//     $personnes = new Personnes();

//     // Set the properties of the personnes object
//     $personnes->setPrenom(isset($data['prenom']) ? $data['prenom'] : "Daniel");
//     $personnes->setNom(isset($data['nom']) ? $data['nom'] : "King");
//     $personnes->setTelephone(isset($data['telephone']) ? $data['telephone'] : "0606060606");

//     // Find the corresponding ville object
//     $villeId = $data['ville']['id'] ?? null;
//     if ($villeId !== null) {
//         $ville = $entityManager->getRepository(Villes::class)->find($villeId);
//         if ($ville !== null) {
//             // Set the ville property of the personnes object
//             $personnes->setVille($ville);
//         } else {
//             return new JsonResponse(["error" => "The ville with id $villeId does not exist"], Response::HTTP_NOT_FOUND);
//         }
//     } else {
//         return new JsonResponse(["error" => "The ville id is missing"], Response::HTTP_BAD_REQUEST);
//     }

//     // Check if the personnes object is valid
//     $errors = $validator->validate($personnes);
//     if ($errors->count() > 0) {
//         return new JsonResponse(["error" => (string) $errors], Response::HTTP_BAD_REQUEST);
//     }

//     // Create the personnes object in the database
//     try {
//         $entityManager->persist($personnes);
//         $entityManager->flush();
//     } catch (\Exception $e) {
//         return new JsonResponse(["error" => "An error occurred while creating the personnes object in the database"], Response::HTTP_INTERNAL_SERVER_ERROR);
//     }

//     // Serialize the personnes object into a JSON string
//     $jsonpersonnes = $serializer->serialize($personnes, 'json', ['groups' => [ 'personnes']]);

//     // Return a JSON response
//     return new JsonResponse($jsonpersonnes, Response::HTTP_CREATED, ["Location" => "/api/personnes/" . $personnes->getId()], true);
// }// end method createpersonnes


#[Route('/insertpersonnes', name: 'insert_personnes', methods: ['POST'])]
public function insertpersonnes(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
{
    // Retrieve the JSON string from the request
    $jsonContent = $request->getContent();

    // Decode the JSON string into an associative array
    $data = json_decode($jsonContent, true);

    // Create a new personnes object
    $personnes = new Personnes();

    // Set the properties of the personnes object
    $personnes->setPrenom(isset($data['prenom']) ? $data['prenom'] : "Daniel");
    $personnes->setNom(isset($data['nom']) ? $data['nom'] : "King");
    $personnes->setTelephone(isset($data['telephone']) ? $data['telephone'] : "0606060606");

    // Find the corresponding ville object
    $villeId = $data['ville']['id'] ?? null;
    if ($villeId !== null) {
        $ville = $entityManager->getRepository(Villes::class)->find($villeId);
        if ($ville !== null) {
            // Set the ville property of the personnes object
            $personnes->setVille($ville);
        } else {
            return new JsonResponse(["error" => "The ville with id $villeId does not exist"], Response::HTTP_NOT_FOUND);
        }
    } else {
        return new JsonResponse(["error" => "The ville id is missing"], Response::HTTP_BAD_REQUEST);
    }

    // Check if the personnes object is valid
    $errors = $validator->validate($personnes);
    if ($errors->count() > 0) {
        return new JsonResponse(["error" => (string) $errors], Response::HTTP_BAD_REQUEST);
    }

    // Create the personnes object in the database
    try {
        $entityManager->persist($personnes);
        $entityManager->flush();
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while creating the personnes object in the database"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    // Serialize the personnes object into a JSON string
    $jsonpersonnes = $serializer->serialize($personnes, 'json', ['groups' => [ 'personnes']]);

    // Return a JSON response
    return new JsonResponse($jsonpersonnes, Response::HTTP_CREATED, ["Location" => "/api/personnes  
    " . $personnes->getId()], true);
}// end method createpersonnes

  


}
