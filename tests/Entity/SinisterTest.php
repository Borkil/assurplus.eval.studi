<?php

namespace App\Tests\Entity;

use App\Entity\Sinister;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SinisterTest extends KernelTestCase
{
    
    public function getEntity(): Sinister
    {
        return (new Sinister())
            ->setAdressOfSinister('13 rue du test 16520 Test Ville')
            ->setDescription('j\’ai heurté un autre véhicule')
            ->setNumberRegistration('xxx-123-xxx');
    }
        
    /**
     * assertHasErrors
     *boot le kernel et le container
     *charge le service Validator et valide une entité
     *fait un assertCount
     *
     * @param  Sinister $entity - une entité à tester
     * @param  int $errorNumber - nombre d'erreur attendu
     * @return void
     */
    public function assertHasErrors(Sinister $entity, int $errorNumber): void
    {
        self::bootKernel();
        $error = static::getContainer()->get('validator')->validate($entity);
        $this->assertCount($errorNumber, $error);
    }

    public function testEntityIsValid(): void
    {
        $this->assertHasErrors($this->getEntity(),0);
    }

    public function testInvalide_BlankAdress_SinisterEntity(): void
    {
        $this->assertHasErrors($this->getEntity()->setAdressOfSinister(''),1);
    }
    
    public function testInvalid_BlankDescription_SinisterEntity(): void
    {
        $this->assertHasErrors($this->getEntity()->setDescription(''),2);
    }

    public function testInvalid_MinDescription_SinisterEntity(): void
    {
        $this->assertHasErrors($this->getEntity()->setDescription('pas moin de 20'),1);
    }

    public function testInvalid_BlankNumbreRegistration_SinisterEntity(): void
    {
        $this->assertHasErrors($this->getEntity()->setNumberRegistration(''),1);
    }

    public function testInvalid_FormatNumbreRegistration_SinisterEntity(): void
    {
        $this->assertHasErrors($this->getEntity()->setNumberRegistration('11-123-xx'),1);
        $this->assertHasErrors($this->getEntity()->setNumberRegistration('xx-bbb-xx'),1);
        $this->assertHasErrors($this->getEntity()->setNumberRegistration('xx-123-11'),1);
        $this->assertHasErrors($this->getEntity()->setNumberRegistration('xx-123xx'),1);
        $this->assertHasErrors($this->getEntity()->setNumberRegistration('xx123-xx'),1);
        $this->assertHasErrors($this->getEntity()->setNumberRegistration('xx123xx'),1);
    }
}
