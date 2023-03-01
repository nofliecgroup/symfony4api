<?php

namespace App\Controller;

use App\Entity\Trajets;
use App\Repository\TrajetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class TrajetsController extends AbstractController
{
    #[Route('/trajects', name: 'get_all_traject', methods: ['GET'])]
    public function getAlltraject(TrajetsRepository $trajetsRepository,  SerializerInterface $serializer): JsonResponse
    {
        // Retrieve all traject objects from the repository
        try {
            $traject = $trajetsRepository->findAll();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while retrieving the traject objects from the database"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Serialize the traject objects into a JSON string
        $jsontraject = $serializer->serialize($traject, 'json', ['groups' => ['conducteurs', 'reservations', 'villes', 'personnes', 'voitures', 'marques']]);
        // Return a JSON response
        return new JsonResponse($jsontraject, Response::HTTP_OK, [], true);
    }// end method getAlltraject

    #[Route('/trajects/{id}', name: 'getOne_trajet', methods: ['GET'])]
    public function getOnetrajects(TrajetsRepository $trajectsRepository, SerializerInterface $serializer, int $id): JsonResponse
    {
        try {
            $traject = $trajectsRepository->find($id);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while retrieving the traject object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Check if the traject object was found
        if (!$traject) {
            return new JsonResponse(["error" => "The traject object with the specified id was not found"], Response::HTTP_NOT_FOUND);
        }
        // Serialize the traject object into a JSON string
        $jsontraject = $serializer->serialize($traject, 'json', ['groups' => ['conducteurs', 'reservations', 'villes', 'personnes', 'voitures', 'marques']]);
    
        return new JsonResponse($jsontraject, Response::HTTP_OK, [], true);
    }// end method getOnetrajects



    //     #[Route('/updatetrajects/{id}', name: 'update_traject', methods: ['PUT'])]
    // public function updatetraject(Request $request, TrajetsRepository $trajectRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id): JsonResponse
    // {
    //     // Retrieve the JSON string from the request
    //     $jsonContent = $request->getContent();
    //     // Deserialize the JSON string into a traject object
    //     try {
    //         $traject = $serializer->deserialize($jsonContent, Trajets::class, 'json', ['groups' => ['trajects', 'conducteurs', 'reservations', 'villes', 'personnes', 'voitures', 'marques']]);
    //     } catch (\Exception $e) {
    //         return new JsonResponse(["error" => "An error occurred while deserializing the traject object"], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    //     // Check if the traject object was found
    //     if (!$traject) {
    //         return new JsonResponse(["error" => "The traject object with the specified id was not found"], Response::HTTP_NOT_FOUND);
    //     }
    //     // Update the traject object in the database
    //     try {
    //         $entityManager->persist($traject);
    //         $entityManager->flush();
    //     } catch (\Exception $e) {
    //         return new JsonResponse(["error" => "An error occurred while updating the traject object"], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    //     // Return a success response
    //     return new JsonResponse(["success" => "The traject object was successfully updated"], Response::HTTP_OK);
    // }// end method updatetraject
    
    #[Route('/createtrajects', name: 'create_traject', methods: ['POST'])]
    public function createtraject(Request $request, TrajetsRepository $trajectRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        // Retrieve the JSON string from the request
        $jsonContent = $request->getContent();
        // Deserialize the JSON string into a traject object
        try {
            $traject = $serializer->deserialize($jsonContent, Trajets::class, 'json', ['groups' => ['trajects', 'conducteurs', 'reservations', 'villes', 'personnes', 'voitures', 'marques']]);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while deserializing the traject object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Check if the traject object was found
        if (!$traject) {
            return new JsonResponse(["error" => "The traject object with the specified id was not found"], Response::HTTP_NOT_FOUND);
        }
        // Update the traject object in the database
        try {
            $entityManager->persist($traject);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while updating the traject object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Return a success response
        return new JsonResponse(["success" => "The traject object was successfully updated"], Response::HTTP_OK);
    }// end method createtraject
    

    #[Route('/deletetrajects/{id}', name: 'delete_traject', methods: ['DELETE'])]
    public function deletetraject(TrajetsRepository $trajectRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        // Retrieve the traject object from the database
        try {
            $traject = $trajectRepository->find($id);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while retrieving the traject object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Check if the traject object was found
        if (!$traject) {
            return new JsonResponse(["error" => "The traject object with the specified id was not found"], Response::HTTP_NOT_FOUND);
        }
        // Delete the traject object from the database
        try {
            $entityManager->remove($traject);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while deleting the traject object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Return a success response
        return new JsonResponse(["success" => "The traject object was successfully deleted"], Response::HTTP_OK);
    }// end method deletetraject



  

    // #[Route('/trajects/{villedepart}/{villearrive}', name: 'trajets_villedepart_villearrive', methods: ['GET'])]
    // public function getTrajetsByVilles(TrajetsRepository $trajetsRepository, string $villedepart, string $villearrive, SerializerInterface $serializer): JsonResponse
    // {
       

    //     if ($villedepart == "null" && $villearrive == "null") {
    //         $trajets = $trajetsRepository->findAll();
    //     } else if ($villedepart == "null") {
    //         $trajets = $trajetsRepository->findByVilleArrive($villearrive);
    //     } else if ($villearrive == "null") {
    //         $trajets = $trajetsRepository->findByVilleDepart($villedepart);
    //     } else {
    //         $trajets = $trajetsRepository->getAllTrajetsByVilles($villedepart, $villearrive);
    //     }
    //     $trajects = $trajetsRepository->getAllTrajetsByVilles($villedepart, $villearrive);
    //     $jsonTrajets = $serializer->serialize($trajects, 'json', ['groups' => ['trajects', 'conducteurs', 'reservations', 'villes', 'personnes', 'voitures', 'marques']]);
    //     return new JsonResponse($jsonTrajets, Response::HTTP_OK, [], true);

    // }
    
    

    

    #[Route('/departarrive', name: 'trajets_villedepart_villearrive_date', methods: ['GET'])]
    public function getTrajetsByVillesDate(TrajetsRepository $trajetsRepository, SerializerInterface $serializer): Response
    {
        $trajets = $trajetsRepository->findAllByVille();
        $json = $serializer->serialize($trajets, 'json', ['groups' => ['trajects', 'conducteurs', 'reservations', 'villes', 'personnes', 'voitures', 'marques']]);
    
        return new Response($json, Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
    }
    

    // #[Route('trajects/{depart}/{arrive}', name: 'villes_trajets_villedepart_villearrive_date', methods: ['GET'])]
    // public function getTrajetsWithVilles(TrajetsRepository $trajetsRepository, string $villedepart, string $villearrive, SerializerInterface $serializer): Response
    // {
    //     $trajets = $trajetsRepository->getAllTrajetsWithVilles($villedepart, $villearrive);
    //     $json = $serializer->serialize($trajets, 'json', ['groups' => ['trajects', 'conducteurs', 'reservations', 'villes', 'personnes', 'voitures', 'marques']]);
    
    //     return new Response($json, Response::HTTP_OK, [
    //         'Content-Type' => 'application/json'
    //     ]);
    // }
    

    #[Route('/trajets/recherche/{villeD},{villeA},{dateT}', name: 'rechercheTrajet', methods: ['GET'])]
public function rechercheTrajet(TrajetsRepository $trajetsRepository, string $villeD, string $villeA, string $dateT, SerializerInterface $serializer): Response
{
    var_dump($villeD, $villeA, $dateT);
    // Convert the date string to a DateTime object
    $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $dateT.' 00:00:00');


    // Call the repository method to search for Trajets based on the given parameters
    $trajets = $trajetsRepository->findByVillesAndDate($villeD, $villeA, $dateTime);
    var_dump($trajets);

    // Serialize the results to JSON
    $json = $serializer->serialize($trajets, 'json', ['groups' => [ 'trajects']]);
   //var_dump($serializer);
    var_dump($json);

    // Deserialize the JSON to an array of Trajet objects
    $trajetsArray = $serializer->deserialize($json, Trajets::class.'[]', 'json', ['groups' => ['conducteurs', 'reservations', 'villes', 'voitures', 'marques', 'trajects', 'personnes']]);
    
    var_dump($trajetsArray);

    // Loop through the Trajets and access their properties
    foreach ($trajetsArray as $trajet) {
        echo $trajet->getVilleDepart(); 
        var_dump($trajet->getVilleDepart());
    }

    // Return the JSON response
    return new Response($json, Response::HTTP_OK, [
        'Content-Type' => 'application/json'
    ]);
}


    

}
