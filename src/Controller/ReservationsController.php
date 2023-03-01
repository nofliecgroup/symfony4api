<?php

namespace App\Controller;

use App\Entity\Reservations;
use App\Repository\PersonnesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api')]
class ReservationsController extends AbstractController
{
    #[Route('/reservations', name: 'All_reservation', methods: ['GET'])]
    public function getAllreservations(PersonnesRepository $personnesRepository, SerializerInterface $serializer): JsonResponse
    {
        // Retrieve all personnes objects from the repository
        try {
            $reservation = $personnesRepository->findAll();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while retrieving the reservation objects from the database"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Serialize the personnes objects into a JSON string
        $jsonreservation = $serializer->serialize($reservation, 'json', ['groups' => ['conducteurs', 'reservations', 'personnes', 'trajets', 'villes', 'reservations', 'voitures', 'personnes']]);
        // Return a JSON response
        return new JsonResponse($jsonreservation, Response::HTTP_OK, [], true);
    }// end method getAllpersonnes

    #[Route('/reservations/{id}', name: 'reservation', methods: ['GET'])]
    public function getreservation(int $id, PersonnesRepository $personnesRepository, SerializerInterface $serializer): JsonResponse
    {
        // Retrieve the personnes object from the repository
        try {
            $reservation = $personnesRepository->find($id);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while retrieving the reservation object from the database"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Serialize the personnes object into a JSON string
        $jsonreservation = $serializer->serialize($reservation, 'json', ['groups' => ['conducteurs', 'reservations', 'personnes', 'trajets', 'villes', 'reservations', 'voitures', 'personnes']]);
        // Return a JSON response
        return new JsonResponse($jsonreservation, Response::HTTP_OK, [], true);
    }// end method getpersonne



//     #[Route('/insertreservations', name: 'insert_reservation', methods: ['POST'])]
// public function addreservation(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
// {
//     // Retrieve the JSON string from the request
//     $jsonreservation = $request->getContent();
//     // Deserialize the JSON string into a Reservations object
//     try {
//         $reservation = $serializer->deserialize($jsonreservation, Reservations::class, 'json', ['groups' => ['reservations', 'personnes', 'trajets', 'villes', 'voitures']]);
//     } catch (\Exception $e) {
//         return new JsonResponse(["error" => "An error occurred while deserializing the reservation object from the JSON string"], Response::HTTP_INTERNAL_SERVER_ERROR);
//     }
//     // Validate the Reservations object
//     $errors = $validator->validate($reservation);
//     if ($errors->count() > 0) {
//         var_dump($errors);
//         return new JsonResponse(["error" => (string) $errors], Response::HTTP_BAD_REQUEST);
//     }
//     // Save the Reservations object into the database
//     try {
//         $entityManager->persist($reservation);
//         $entityManager->flush();
//     } catch (\Exception $e) {
//         return new JsonResponse(["error" => "An error occurred while saving the reservation object into the database"], Response::HTTP_INTERNAL_SERVER_ERROR);
//     }
//     // Return a JSON response
//     return new JsonResponse(["success" => "The reservation object has been successfully saved into the database"], Response::HTTP_CREATED);
// }


#[Route('/insertreservations', name: 'insert_reservation', methods: ['POST'])]
public function insertreservation(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
{
    // Retrieve the JSON string from the request
    $jsonreservation = $request->getContent();
    
    // Decode the JSON string into an array
    try {
        $reservationData = json_decode($jsonreservation, true);
    } catch (\Exception $e) {
        error_log($e->getMessage());
        error_log($e->getTraceAsString());
        return new JsonResponse(
            ["error" => "An error occurred while decoding the reservation object"],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
    
    // Create a new Reservation object from the decoded data
    $reservation = new Reservations();
    $reservation->setIdpers($reservationData['idpers'] ?? null);
    $reservation->setIdtrajet($reservationData['idtrajet'] ?? null);
    $reservation->setNseatsreserved($reservationData['nbseat'] ?? null);
    
   // $reservation->setVoiture($reservationData['voiture'] ?? null);
    

    // Validate the reservation object
    $errors = $validator->validate($reservation);
    if ($errors->count() > 0) {
        var_dump($errors);
        return new JsonResponse(["error" => (string) $errors], Response::HTTP_BAD_REQUEST);
    }
    
    // Insert the reservation object into the database
    try {
        $entityManager->persist($reservation);
        $entityManager->flush();
    } catch (\Exception $e) {
        error_log($e->getMessage());
        error_log($e->getTraceAsString());
        return new JsonResponse(
            ["error" => "An error occurred while inserting the reservation object"],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
    
    return new JsonResponse(
        ["success" => "The reservation object was successfully inserted"],
        Response::HTTP_OK
    );
}


//Updated reservation route
#[Route('/updatereservations/{id}', name: 'update_reservation', methods: ['PUT'])]
public function updatereservation(int $id, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
{
    // Retrieve the JSON string from the request
    $jsonreservation = $request->getContent();
    
    // Decode the JSON string into an array
    try {
        $reservationData = json_decode($jsonreservation, true);
    } catch (\Exception $e) {
        error_log($e->getMessage());
        error_log($e->getTraceAsString());
        return new JsonResponse(
            ["error" => "An error occurred while decoding the reservation object"],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
    
    // Retrieve the reservation object from the database
    $reservation = $entityManager->getRepository(Reservations::class)->find($id);
    if (!$reservation) {
        return new JsonResponse(
            ["error" => "The reservation object with id $id was not found in the database"],
            Response::HTTP_NOT_FOUND
        );
    }
    
    // Update the reservation object with the new data
    $reservation->setIdpers($reservationData['idpers'] ?? $reservation->getIdpers());
    $reservation->setIdtrajet($reservationData['idtrajet'] ?? $reservation->getIdtrajet());
    $reservation->setNseatsreserved($reservationData['nbseat'] ?? $reservation->getNseatsreserved());
    $voiture = $entityManager->getRepository(Voitures::class)->find($reservationData['voiture'] ?? $reservation->getNseatsreserved());
    //$reservation->setVoiture($reservationData['voiture'] ?? $reservation->getVoiture());
    
    // Validate the reservation object
    $errors = $validator->validate($reservation);
    if ($errors->count() > 0) {
        var_dump($errors);
        return new JsonResponse(["error" => (string) $errors], Response::HTTP_BAD_REQUEST);
    }
    
    // Update the reservation object in the database
    try {
        $entityManager->flush();
    } catch (\Exception $e) {
        error_log($e->getMessage());
        error_log($e->getTraceAsString());
        return new JsonResponse(
            ["error" => "An error occurred while updating the reservation object"],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
    
    return new JsonResponse(
        ["success" => "The reservation object was successfully updated"],
        Response::HTTP_OK
    );

}

//Delete reservation route

#[Route('/deletereservations/{id}', name: 'delete_reservation', methods: ['DELETE'])]

public function deletereservation(int $id, EntityManagerInterface $entityManager): JsonResponse
{
    // Retrieve the reservation object from the database
    $reservation = $entityManager->getRepository(Reservations::class)->find($id);
    if (!$reservation) {
        return new JsonResponse(
            ["error" => "The reservation object with id $id was not found in the database"],
            Response::HTTP_NOT_FOUND
        );
    }
    
    // Delete the reservation object from the database
    try {
        $entityManager->remove($reservation);
        $entityManager->flush();
    } catch (\Exception $e) {
        error_log($e->getMessage());
        error_log($e->getTraceAsString());
        return new JsonResponse(
            ["error" => "An error occurred while deleting the reservation object"],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
    
    return new JsonResponse(
        ["success" => "The reservation object was successfully deleted"],
        Response::HTTP_OK
    );

}





}
