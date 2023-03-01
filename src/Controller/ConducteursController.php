<?php

namespace App\Controller;

use App\Entity\Personnes; 
use App\Entity\Trajects;
use App\Entity\Conducteurs;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConducteursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class ConducteursController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/conducteurs', name: 'liste_conducteurs', methods: ['GET'])]
    public function listeConducteurs(ConducteursRepository $conducteursRepository, SerializerInterface $serializer): Response
    {
        $conducteurs = $conducteursRepository->findAll();
        $jsonContent = $serializer->serialize($conducteurs, 'json', ['groups' => ['personnes', 'conducteurs', 'voitures', 'reservations', 'trajects', 'ville', 'marques']]);
        return new Response($jsonContent, 200, ['Content-Type' => 'application/json']);
    }



#[Route('/conducteurs/{id}', name: 'getOne_conducteur', methods: ['GET'])]
public function getOneConducteur(ConducteursRepository $conducteursRepository, SerializerInterface $serializer, int $id): JsonResponse
{
    try {
        $conducteur = $conducteursRepository->find($id);
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while retrieving the Conducteur object"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    // Check if the Conducteur object was found
    if (!$conducteur) {
        return new JsonResponse(["error" => "The Conducteur object with the specified id was not found"], Response::HTTP_NOT_FOUND);
    }
    // Serialize the Conducteur object into a JSON string
    $jsonConducteur = $serializer->serialize($conducteur, 'json', ['groups' => ['personnes', 'conducteurs', 'voitures', 'reservations', 'trajects', 'ville', 'marques']]);

    return new JsonResponse($jsonConducteur, Response::HTTP_OK, [], true);
}// end method getOneConducteur



#[Route('/insertconducteurs', name: 'insert_conducteur', methods: ['POST'])]
public function insertConducteur(Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    // Retrieve the JSON string from the request
    $jsonContent = $request->getContent();

    // Create a new instance of the Conducteur object and set its properties
    $conducteur = new Conducteurs();
    $conducteurData = json_decode($jsonContent, true);
    if (!isset($conducteurData['idpersc'])) {
        var_dump($conducteurData['idpersc']);
        return new JsonResponse(["error" => "idpersc is missing"], Response::HTTP_BAD_REQUEST);
    }
    $conducteur->setIdpersc($conducteurData['idpersc']);
    $personnes = $this->entityManager->getRepository(Personnes::class)->find($conducteurData['idpersc']);

    if (!isset($conducteurData['idtrajetc'])) {
        var_dump($conducteurData['idtrajetc']);
        return new JsonResponse(["error" => "idtrajetc is missing"], Response::HTTP_BAD_REQUEST);
    }
    $conducteur->setIdpersc($personnes);
  
    

    // Insert the Conducteur object into the database
    try {
        $entityManager->persist($conducteur);
        $entityManager->flush();
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while inserting the Conducteur object"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    // Return a success response
    return new JsonResponse(["success" => "The Conducteur object was successfully inserted"], Response::HTTP_OK);
}// end method insertConducteur


// #[Route('/conducteurs/{id}', name: 'update_conducteur', methods: ['PUT'])]
// public function updateConducteur(Request $request, ConducteursRepository $conducteursRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id): JsonResponse
// {
//     // Retrieve the JSON string from the request
//     $jsonContent = $request->getContent();
//     // Deserialize the JSON string into a Conducteur object
//     try {
//         $conducteur = $serializer->deserialize($jsonContent, Conducteurs::class, 'json');
//     } catch (\Exception $e) {
//         return new JsonResponse(["error" => "An error occurred while deserializing the Conducteur object"], Response::HTTP_INTERNAL_SERVER_ERROR);
//     }
//     // Check if the Conducteur object was found
//     if (!$conducteur) {
//         return new JsonResponse(["error" => "The Conducteur object with the specified id was not found"], Response::HTTP_NOT_FOUND);
//     }
//     // Update the Conducteur object in the database
//     try {
//         $entityManager->persist($conducteur);
//         $entityManager->flush();
//     } catch (\Exception $e) {
//         return new JsonResponse(["error" => "An error occurred while updating the Conducteur object"], Response::HTTP_INTERNAL_SERVER_ERROR);
//     }
//     // Return a success response
//     return new JsonResponse(["success" => "The Conducteur object was successfully updated"], Response::HTTP_OK);
// }// end method updateConducteur


#[Route('/updateconducteurs/{id}', name: 'update_conducteur', methods: ['PUT'])]
public function updateConducteur(Request $request, ConducteursRepository $conducteursRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
{
    // Retrieve the JSON string from the request and decode it into an array
    $jsonData = json_decode($request->getContent(), true);
    
    // Check if the Conducteur object was found
    $conducteur = $conducteursRepository->find($id);
    if (null === $conducteur) {
        return new JsonResponse(["error" => "The Conducteur object with the specified id was not found"], Response::HTTP_NOT_FOUND);
    }

    $personnes = $this->entityManager->getRepository(Personnes::class)->find($jsonData['idpersc']);
    if (null === $personnes) {
    
        return new JsonResponse(["error" => "The Personnes object with the specified id was not found"], Response::HTTP_NOT_FOUND);
    
    }
    $personnes->setNom($jsonData['nom']);
    $personnes->setPrenom($jsonData['prenom']);
    $personnes->setTelephone($jsonData['telephone']);
    $conducteur->setIdpersc($personnes);
    
    $traject = $this->entityManager->getRepository(Trajects::class)->find($jsonData['idtrajetc']);
    if (null === $traject) {
        return new JsonResponse(["error" => "The Trajects object with the specified id was not found"], Response::HTTP_NOT_FOUND);
    }
    $conducteur->setIdtrajetc($traject);


    // Update the Conducteur object in the database
    try {
        $entityManager->persist($conducteur);
        $entityManager->flush();
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while updating the Conducteur object"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    
    // Return a success response
    return new JsonResponse(["success" => "The Conducteur object was successfully updated"], Response::HTTP_OK);
}



#[Route('/conducteurs/{id}', name: 'delete_conducteur', methods: ['DELETE'])]
public function deleteConducteur(ConducteursRepository $conducteursRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
{
    // Try to retrieve the Conducteur object from the database
    try {
        $conducteur = $conducteursRepository->find($id);
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while retrieving the Conducteur object"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    // Check if the Conducteur object was found
    if (!$conducteur) {
        return new JsonResponse(["error" => "The Conducteur object with the specified id was not found"], Response::HTTP_NOT_FOUND);
    }
    // Delete the Conducteur object from the database
    try {
        $entityManager->remove($conducteur);
        $entityManager->flush();
    } catch (\Exception $e) {
        return new JsonResponse(["error" => "An error occurred while deleting the Conducteur object"], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    // Return a success response
    return new JsonResponse(["success" => "The Conducteur object was successfully deleted"], Response::HTTP_OK);
}// end method deleteConducteur


   
    
}
