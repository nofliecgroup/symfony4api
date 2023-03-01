<?php

namespace App\Controller;

use App\Entity\Marques;
use App\Entity\Voitures;
use App\Repository\VoituresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class VoituresController extends AbstractController
{
    // #[Route('/voitures', name: 'liste_voitures', methods: ['GET'])]
    // public function listevoitures(VoituresRepository $voituresRepository, SerializerInterface $serializer): Response
    // {
    //     $voitures = $voituresRepository->findAll();
    //     $jsonContent = $serializer->serialize($voitures, 'json', ['groups' => ['personnes', 'voitures', 'conducteurs', 'reservations', 'trajects', 'ville', 'marques']]);
    //     return new Response($jsonContent, 200, ['Content-Type' => 'application/json']);
    // }
    #[Route('/getallvoitures', name: 'get_all_voiture', methods: ['GET'])]
    public function getallvoiture(VoituresRepository $voituresRepository, SerializerInterface $serializer): JsonResponse
    {
        // Retrieve the voiture objects from the repository
        $voitures = $voituresRepository->findAll();
        // Serialize the voiture objects into a JSON string
        $jsonVoitures = $serializer->serialize($voitures, 'json', ['groups' => ['personnes', 'voitures', 'conducteurs', 'reservations', 'trajects', 'ville', 'marques']]);
        // Return a success response
        return new JsonResponse($jsonVoitures, Response::HTTP_OK, [], true);
    }// end method getallvoiture


    // get voiture by id

    #[Route('/voitures/{id}', name: 'getOne_voiture', methods: ['GET'])]
    public function getOneVoiture(VoituresRepository $voituresRepository, SerializerInterface $serializer, int $id): JsonResponse
    {
        try {
            $voiture = $voituresRepository->find($id);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while retrieving the Voiture object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Check if the Voiture object was found
        if (!$voiture) {
            return new JsonResponse(["error" => "The Voiture object with the specified id was not found"], Response::HTTP_NOT_FOUND);
        }
        // Serialize the Voiture object into a JSON string
        $jsonVoiture = $serializer->serialize($voiture, 'json', ['groups' => ['personnes', 'voitures', 'conducteurs', 'reservations', 'trajects', 'ville', 'marques']]);

        return new JsonResponse($jsonVoiture, Response::HTTP_OK, [], true);
    }// end method getOneVoiture

   /* #[Route('/insertvoitures', name: 'insert_voiture', methods: ['POST'])]
    public function insertvoiture(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Retrieve the JSON string from the request
        $jsonContent = $request->getContent();
        
        // Create a new instance of the Voitures object and set its properties
        $voiture = new Voitures();
        $voitureData = json_decode($jsonContent, true);
        $idmarque = $voitureData['idmarque'];
        $marque = $entityManager->getRepository(Marques::class)->find($idmarque);

        $voiture->setImmatriculation($voitureData['immatriculation']);
        $marque = new Marques();
        $marque->setBrandnom($voitureData['Renault']);
        $voiture->setModele($voitureData['modele']);
        $voiture->setNbseats($voitureData['nbseats']);
        $voiture->setIdmarque($marque);

        $voiture->setIdmarque($voitureData['nbseats']);
       if (!isset($voitureData['idmarque'])) {
            $voiture->setIdmarque($voitureData['11111']);
        } elseif (!isset($voitureData['modele'])) {
            $voiture->setModele($voitureData['RENAULT']);
        } elseif (!isset($voitureData['immatriculation'])) {
            $voiture->setImmatriculation($voitureData['FR-225-AB']);
        } elseif (!isset($voitureData['nbseats'])) {
            $voiture->setNbseats($voitureData['3']);
        }
    
        // Insert the voiture object into the database
        try {
            $entityManager->persist($voiture);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while inserting the voiture object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    
        // Return a success response
        return new JsonResponse(["success" => "The voiture object was successfully inserted"], Response::HTTP_OK);
    }
    */

    #[Route('/insertvoitures', name: 'insert_voiture', methods: ['POST'])]
    public function insertvoiture(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $jsonContent = $request->getContent();
        // Create a new instance of the Voitures object and set its properties
        $voiture = new Voitures();
        $voitureData = json_decode($jsonContent, true);
        $idmarque = $voitureData['idmarque'];
        $marque = $entityManager->getRepository(Marques::class)->find($idmarque);

        $voiture->setImmatriculation($voitureData['immatriculation']);
        $voiture->setModele($voitureData['modele']);
        $voiture->setNbseats($voitureData['nbseats']);
        $voiture->setIdmarque($marque);

        //should incase the values of fields are missing
        if (!isset($voitureData['idmarque'])) {
            $voiture->setIdmarque(00000);
        }
        if (!isset($voitureData['modele'])) {
            $voiture->setModele("");
        }
        if (!isset($voitureData['immatriculation'])) {
            $voiture->setImmatriculation("FR-000-AB");
        }
        if (!isset($voitureData['nbseats'])) {
            $voiture->setNbseats(0);
        }

        // Insert the voiture object into the database
        try {
            $entityManager->persist($voiture);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while inserting the voiture object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse(["success" => "The voiture object was successfully inserted"], Response::HTTP_OK);



    }
 

    #[Route('/updatevoitures/{id}', name: 'update_voiture', methods: ['PUT'])]
    public function updatevoiture(Request $request, VoituresRepository $voituresRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        // Retrieve the voiture object from the repository
        $voiture = $voituresRepository->find($id);
        // Check if the voiture object was found
        if (!$voiture) {
            return new JsonResponse(["error" => "The voiture object with the specified id was not found"], Response::HTTP_NOT_FOUND);
        }
        // Retrieve the JSON string from the request
        $jsonContent = $request->getContent();
        // Decode the JSON string into an associative array
        $data = json_decode($jsonContent, true);
        // Update the voiture object with the new data
        $voiture->setIdmarque($data['marque'] ?? $voiture->getIdmarque());
        $voiture->setModele($data['modele'] ?? $voiture->getModele());
        $voiture->setImmatriculation($data['annee'] ?? $voiture->getImmatriculation());
        $voiture->setNbseats($data['nbseats'] ?? $voiture->getNbseats());
        // Update the voiture object in the database
        try {
            $entityManager->persist($voiture);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while updating the voiture object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Return a success response
        return new JsonResponse(["success" => "The voiture object was successfully updated"], Response::HTTP_OK);
    }// end method updatevoiture
    

    #[Route('/deletevoitures/{id}', name: 'delete_voiture', methods: ['DELETE'])]
    public function deletevoiture(VoituresRepository $voituresRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        // Retrieve the voiture object from the repository
        $voiture = $voituresRepository->find($id);
        // Check if the voiture object was found
        if (!$voiture) {
            return new JsonResponse(["error" => "The voiture object with the specified id was not found"], Response::HTTP_NOT_FOUND);
        }
        // Delete the voiture object from the database
        try {
            $entityManager->remove($voiture);
            $entityManager->flush();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => "An error occurred while deleting the voiture object"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Return a success response
        return new JsonResponse(["success" => "The voiture object was successfully deleted"], Response::HTTP_OK);
    }// end method deletevoiture


    // Create a new voiture object

  

}
