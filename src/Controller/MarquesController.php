<?php

namespace App\Controller;

use App\Entity\Marques;
use App\Repository\MarquesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/api')]
class MarquesController extends AbstractController
{
    #[Route('/marques', name: 'get_all_marques', methods: ['GET'])]
public function getAllMarques(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
{
    // Retrieve all Marques objects from the database
    try {
        $marques = $entityManager->getRepository(Marques::class)->findAll();
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while retrieving the Marques objects from the database"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    // Serialize the Marques objects into a JSON string
    $jsonMarques = $serializer->serialize($marques, 'json', ['groups' => ['conducteurs', 'reservations', 'personnes', 'trajets', 'villes', 'reservations', 'voitures', 'marques']]);
    // Return a JSON response
    return new JsonResponse($jsonMarques, Response::HTTP_OK, ['Location => /api/marques'], true);
}// end method getAllMarques



    #[Route('/marques/{id}', name: 'getOne_marques', methods: ['GET'])]
public function getOneMarques(MarquesRepository $marquesRepository, SerializerInterface $serializer, int $id): JsonResponse
{
    try {
        $marques = $marquesRepository->find($id);
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while retrieving the marques object"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    // Check if the Conducteur object was found
    if (!$marques) {
        return new JsonResponse(["error" => "The marques object with the specified id was not found"], Response::HTTP_NOT_FOUND);
    }
    // Serialize the Conducteur object into a JSON string
    $jsonConducteur = $serializer->serialize($marques, 'json', ['groups' => ['conducteurs', 'reservations', 'personnes', 'trajets', 'villes', 'reservations', 'voitures', 'marques']]);

    return new JsonResponse($jsonConducteur, Response::HTTP_OK, [], true);
}// end method get One Marques


    // Insert a new Marques object into the database
    #[Route('/insertmarques', name: 'insert_marques', methods: ['POST'])]
    public function insertMarques(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
    // Retrieve the JSON string from the request
    $jsonContent = $request->getContent();
    // Deserialize the JSON string into a Marques object
    try {
        $marques = $serializer->deserialize($jsonContent, Marques::class, 'json');
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while deserializing the JSON string into a Marques object"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    // Check if the Marques object is valid
    $errors = $validator->validate($marques);
    if ($errors->count() > 0) {
        return new JsonResponse(["error" => (string) $errors], Response::HTTP_BAD_REQUEST);
    }
    // Insert the Marques object into the database
    try {
        $entityManager->persist($marques);
        $entityManager->flush();
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while inserting the Marques object into the database"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    // Serialize the Marques object into a JSON string
    $jsonMarques = $serializer->serialize($marques, 'json', ['groups' => ['conducteurs', 'reservations', 'personnes', 'trajets', 'villes', 'reservations', 'voitures', 'marques']]);
    // Return a JSON response
    return new JsonResponse($jsonMarques, Response::HTTP_CREATED, ["Location" => "/api/marques/" . $marques->getId()], true);
}// end method insertMarques


// Update a Marques object in the database
#[Route('/updatemarques/{id}', name: 'update_marques', methods: ['PUT'])]
public function updateMarques(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator, int $id): JsonResponse
{
    // Retrieve the JSON string from the request
    $jsonContent = $request->getContent();
    // Deserialize the JSON string into a Marques object
    try {
        $marques = $serializer->deserialize($jsonContent, Marques::class, 'json');
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while deserializing the JSON string into a Marques object"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    // Check if the Marques object is valid
    $errors = $validator->validate($marques);
    if ($errors->count() > 0) {
        return new JsonResponse(["error" => (string) $errors], Response::HTTP_BAD_REQUEST);
    }
    // Retrieve the Marques object from the database
    try {
        $marquesFromDb = $entityManager->getRepository(Marques::class)->find($id);
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while retrieving the Marques object from the database"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    // Check if the Marques object was found
    if (!$marquesFromDb) {
        return new JsonResponse(["error" => "The Marques object with the specified id was not found"], Response::HTTP_NOT_FOUND);
    }
    // Update the Marques object in the database
    try {
        $marquesFromDb->setBrandnom($marques->getBrandnom());
        $entityManager->flush();
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while updating the Marques object in the database"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    // Serialize the Marques object into a JSON string
    $jsonMarques = $serializer->serialize($marquesFromDb, 'json', ['groups' => ['conducteurs', 'reservations', 'personnes', 'trajets', 'villes', 'reservations', 'voitures', 'marques']]);
    // Return a JSON response
    return new JsonResponse($jsonMarques, Response::HTTP_OK, ["Location" => "/api/updatemarques/" . $marques->getId()], true);
}// end method updateMarques


// Delete a Marques object from the database
#[Route('/deletemarques/{id}', name: 'delete_marques', methods: ['DELETE'])]
public function deleteMarques(EntityManagerInterface $entityManager, int $id): JsonResponse
{
    // Retrieve the Marques object from the database
    try {
        $marques = $entityManager->getRepository(Marques::class)->find($id);
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while retrieving the Marques object from the database"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    // Check if the Marques object was found
    if (!$marques) {
        return new JsonResponse(["error" => "The Marques object with the specified id was not found"], Response::HTTP_NOT_FOUND);
    }
    // Delete the Marques object from the database
    try {
        $entityManager->remove($marques);
        $entityManager->flush();
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while deleting the Marques object from the database"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    // Return a JSON response
    return new JsonResponse(null, Response::HTTP_NO_CONTENT);
}// end method deleteMarques

// Retrieve all Marques objects from the database




}
