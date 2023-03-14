<?php

namespace App\DataFixtures;


use Faker\Factory;
use App\Entity\Customer;
use App\Entity\Sinister;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private $faker;

    public function __construct() 
    {
        $this->faker = Factory::create('fr_FR');
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

            $customer = new Customer();
            $customer->setNumberCustomer($this->faker->randomNumber(6, true))
            ->setFirstname($this->faker->firstName())
            ->setLastname($this->faker->lastName())
            ->setMail($this->faker->email())
            ->setPhoneNumber('0'.$this->faker->randomNumber(9,true))
            ->addSinister($sinister);

            $manager->persist($customer);
            
        }

        $manager->flush();
    }
}
