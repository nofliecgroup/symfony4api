<?php

namespace App\DataFixtures;

use App\Entity\Conducteurs;
use App\Entity\Marques;
use App\Entity\Personnes;
use App\Entity\Reservations;
use App\Entity\Trajets;
use App\Entity\Users;
use App\Entity\Villes;
use App\Entity\Voitures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        // Create Marques
        for ($i = 0; $i < 10; $i++) {
            $carMarques =[ 'Abarth', 'Alfa Romeo', 'Aston Martin', 'Audi', 'Bentley', 'BMW', 'Bugatti', 'Cadillac', 'Chevrolet', 'Chrysler', 'Citroën', 'Dacia', 'Daewoo', 'Daihatsu', 'Dodge', 'Ferrari', 'Fiat', 'Ford', 'Honda', 'Hummer', 'Hyundai', 'Infiniti', 'Isuzu', 'Jaguar', 'Jeep', 'Kia', 'Lada', 'Lamborghini', 'Lancia', 'Land Rover', 'Lexus', 'Lotus', 'Maserati', 'Maybach', 'Mazda', 'McLaren', 'Mercedes-Benz', 'MG', 'Mini', 'Mitsubishi', 'Morgan', 'Nissan', 'Opel', 'Peugeot', 'Porsche', 'Renault', 'Rolls-Royce', 'Rover', 'Saab', 'Seat', 'Skoda', 'Smart', 'SsangYong', 'Subaru', 'Suzuki', 'Talbot', 'Tata', 'Tesla', 'Toyota', 'Volkswagen', 'Volvo'];
            $marque = new Marques();
            $marque->setBrandNom($faker->randomElement($carMarques));


            $manager->persist($marque);
        }

// Create Voitures
        $carMarques =[ 'Abarth', 'Alfa Romeo', 'Aston Martin', 'Audi', 'Bentley', 'BMW', 'Bugatti', 'Cadillac', 'Chevrolet', 'Chrysler', 'Citroën', 'Dacia', 'Daewoo', 'Daihatsu', 'Dodge', 'Ferrari', 'Fiat', 'Ford', 'Honda', 'Hummer', 'Hyundai', 'Infiniti', 'Isuzu', 'Jaguar', 'Jeep', 'Kia', 'Lada', 'Lamborghini', 'Lancia', 'Land Rover', 'Lexus', 'Lotus', 'Maserati', 'Maybach', 'Mazda', 'McLaren', 'Mercedes-Benz', 'MG', 'Mini', 'Mitsubishi', 'Morgan', 'Nissan', 'Opel', 'Peugeot', 'Porsche', 'Renault', 'Rolls-Royce', 'Rover', 'Saab', 'Seat', 'Skoda', 'Smart', 'SsangYong', 'Subaru', 'Suzuki', 'Talbot', 'Tata', 'Tesla', 'Toyota', 'Volkswagen', 'Volvo'];
        for ($i = 0; $i < 10; $i++) {
            $voiture = new Voitures();
            $modeles = ['sedan', 'hatchback', 'suv', 'crossover', 'coupe', 'cabriolet', 'berline', 'break', 'monospace', 'pickup', '4x4', 'citadine', 'compacte', 'sportive', 'utilitaire', 'camionnette', 'camion', 'bus', 'minibus', 'autocar', 'remorque', 'tracteur', 'remorque'];
            $voiture->setModele($faker->randomElement($modeles));
            $voiture->setNbseats($faker->numberBetween(1, 5));
            $voiture->setImmatriculation($faker->unique()->regexify('[A-Z]{2}-[0-9]{3}-[A-Z]{2}'));

            $marques = $manager->getRepository(Marques::class)->findAll();
            if (!empty($marques)) {
                $randomMarque = $faker->randomElement($marques);
                $voiture->setIdmarque($randomMarque);
            } else {
                $marque = new Marques();
                $marque->setBrandNom($faker->randomElement($carMarques));
                $manager->persist($marque);
                $voiture->setIdmarque($marque);
            }

            $manager->persist($voiture);
        }

        $manager->flush();



        // Create Villes
        for ($i = 0; $i < 10; $i++) {
            $faker = Factory::create('fr_FR');
            $ville = new Villes();
            $ville->setVilleNom($faker->city());
            $ville->setCodePostal($faker->unique()->numberBetween(10000, 99999));
            $manager->persist($ville);
            $manager->flush();
        }

        // Create Personnes and Authentifications
        for ($i = 0; $i < 10; $i++) {
            $faker = Factory::create('fr_FR');
            $personne = new Personnes();
            $personne->setPrenom($faker->firstName());
            $personne->setNom($faker->lastName());
            $personne->setTelephone($faker->numerify('06########'));
            $personne->setVille($faker->randomElement($manager->getRepository(Villes::class)->findAll()));
            $manager->persist($personne);


        $personneObject = $manager->getRepository(Personnes::class)->findOneBy(['id' => $personne->getId()]);

            for ($j = 0; $j < 10; $j++) {
                //Creation of regular users
                $auth = new Users();
                $auth->setEmail($faker->unique()->email());
                $auth->setIdpers($personneObject);
                $auth->setPassword($faker->password());
                $auth->setRoles(['ROLE_USER']);
                $manager->persist($auth);

                // Create Admins
                $admin = new Users();
                $admin->setEmail($faker->unique()->email());
                $admin->setIdpers($personneObject);
                $admin->setPassword($faker->password());
                $admin->setRoles(['ROLE_ADMIN']);
                $manager->persist($admin);

            }
        }

        // Create Trajets
        for ($i = 0; $i < 20; $i++) {
            $trajet = new Trajets();
            $trajet->setNbkilometers($faker->numberBetween(50, 1000));
            $trajet->setDatetotravel($faker->dateTimeBetween('now', '+1 week'));
            $trajet->setVilleDepart($faker->randomElement($manager->getRepository(Villes::class)->findAll()));
            $trajet->setVillearrive($faker->randomElement($manager->getRepository(Villes::class)->findAll()));
            $trajet->setIdvoiture($faker->randomElement($manager->getRepository(Voitures::class)->findAll()));
            $manager->persist($trajet);
        }


        // Reservations
        for ($i = 0; $i < 10; $i++) {
            $reservation = new Reservations();
            $personnes = $manager->getRepository(Personnes::class)->findAll();
            $trajets = $manager->getRepository(Trajets::class)->findAll();

            $reservation->setIdtrajet($faker->randomElement($trajets));
            //$reservation->setIdpers(null); // change this line

            $personne = $faker->randomElement($personnes);
            $reservation->setIdpers($personne);
            $reservation->setNseatsreserved($faker->numberBetween(1, 5));

            foreach ($personnes as $personne) {
                $randomTrajet = $faker->randomElement($trajets);
                $reservation = new Reservations();
                $reservation->setIdpers($personne);
                $reservation->setIdTrajet($randomTrajet);
                $reservation->setNseatsreserved($faker->numberBetween(1, $randomTrajet->getIdvoiture()->getNbseats()));

            }
            $manager->persist($reservation);
            $manager->flush();
        }





        // Create Conducteurs
     /*   for ($i = 0; $i < 10; $i++) {
            $conducteur = new Conducteurs();
            // Get random Personnes object and set it as idpersc
            $personnes = $manager->getRepository(Personnes::class)->findAll();
            $randomPersonne = $faker->randomElement($personnes);
            $conducteur->setIdpersc($randomPersonne);
            $trajets = $manager->getRepository(Trajets::class)->findAll();
            $randomTrajet = $faker->randomElement($trajets);
            $conducteur->setIdtrajetc($randomTrajet);
            // Access idtrajetc_id using $randomTrajet->getId()
            //$idtrajetcId = $randomTrajet->getId();

            $manager->persist($conducteur);
        }*/


        // Create Conducteurs
        $personnes = $this->entityManager->getRepository(Personnes::class)->findAll();
        $trajets = $this->entityManager->getRepository(Trajets::class)->findAll();

        for ($i = 0; $i < 10; $i++) {
            $conducteur = new Conducteurs();

            if (!empty($personnes) && !empty($trajets)) {
                // Get a random index for the Personnes and Trajets arrays
                $randomPersonne = array_rand($personnes);
                $randomTrajet = array_rand($trajets);

                // Set the Conducteurs entity's Personnes and Trajets properties based on the random indices
                $conducteur->setIdpersc($personnes[$randomPersonne]);
                $conducteur->setIdtrajetc($trajets[$randomTrajet]);
            } else {
                // Create a new Personnes and Trajets object
                $newPersonne = new Personnes();
                $newPersonne->setPrenom($faker->firstName());
                $newPersonne->setNom($faker->lastName());
                $newPersonne->setTelephone($faker->numerify('06########'));
                $newPersonne->setVille($faker->randomElement($manager->getRepository(Villes::class)->findAll()));
                $manager->persist($newPersonne);
                $personnes[] = $newPersonne;

                $newTrajet = new Trajets();
                $newTrajet->setNbkilometers($faker->numberBetween(50, 1000));
                $newTrajet->setDatetotravel($faker->dateTimeBetween('now', '+1 week'));
                $newTrajet->setVilleDepart($faker->randomElement($manager->getRepository(Villes::class)->findAll()));
                $newTrajet->setVillearrive($faker->randomElement($manager->getRepository(Villes::class)->findAll()));
                $newTrajet->setIdvoiture($faker->randomElement($manager->getRepository(Voitures::class)->findAll()));
                $manager->persist($newTrajet);
                $trajets[] = $newTrajet;

                // Set the Conducteurs entity's Personnes and Trajets properties based on the new objects
                $conducteur->setIdpersc($newPersonne);
                $conducteur->setIdtrajetc($newTrajet);
            }
            // Get a random index for the Personnes and Trajets arrays
            $randomPersonne = array_rand($personnes);
            $randomTrajet = array_rand($trajets);

            // Set the Conducteurs entity's Personnes and Trajets properties based on the random indices
            $conducteur->setIdpersc($personnes[$randomPersonne]);
            $conducteur->setIdtrajetc($trajets[$randomTrajet]);

            $manager->persist($conducteur);
        }





        $manager->flush();

    }
}
