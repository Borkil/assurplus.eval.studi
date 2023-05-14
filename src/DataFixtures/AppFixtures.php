<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\User;
use App\Entity\Sinister;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $faker;
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher) 
    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $hasher;
    }

    //'/^[a-zA_Z]{3}[-][0-9]{3}[-][a-zA_Z]{3}/'
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) { 
            $sinister = new Sinister();
            $sinister->setAdressOfSinister($this->faker->Address())
            ->setDescription($this->faker->Text())
            ->setNumberRegistration('xx-123-xx');
            
            $manager->persist($sinister);

            $user = (new User())
                ->setNumberUser($this->faker->randomNumber(6, true))
                ->setFirstname($this->faker->firstName())
                ->setLastname($this->faker->lastName())
                ->setEmail($this->faker->email())
                ->setPhoneNumber('0'.$this->faker->randomNumber(9,true))
                ->setRoles(['ROLE_USER'])
                ->addSinister($sinister);

            $plainPassword = 'password';

            $hashedPassword = $this->hasher->hashPassword(
                $user,
                $plainPassword
            );
            $user->setPassword($hashedPassword);


            $manager->persist($user);
            
        }

        $manager->flush();
    }
}
