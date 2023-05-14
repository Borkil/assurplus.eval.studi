<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
  public function getEntity(): User
  {
    return (new User())
      ->setNumberUser('1x1x1x')
      ->setFirstname('francois')
      ->setLastname('test')
      ->setEmail('francois.test@test.com')
      ->setPhoneNumber('0672067206');
  }

/**
   * assertHasErrors
   *boot le kernel et le container
    *charge le service Validator et valide une entité
    *fait un assertCount
    *
    * @param  User $entity - une entité à tester
    * @param  int $errorNumber - nombre d'erreur attendu
    * @return void
    */
  public function assertHasErrors(User $entity, int $errorNumber): void
  {
      self::bootKernel();
      $error = static::getContainer()->get('validator')->validate($entity);
      $this->assertCount($errorNumber, $error);
  }

  public function testEntityIsValid(): void
  {
    $this->assertHasErrors($this->getEntity(), 0);
  }

// TEST NUMBERUser VALIDATION

  public function testShould_invalid_When_numberUserIsBlank(): void
  {
    $this->assertHasErrors($this->getEntity()->setNumberUser(''), 2);
  }

  public function testShould_invalid_When_numberUserHaveSpecialCharacter(): void
  {
    $this->assertHasErrors($this->getEntity()->setNumberUser('12_&fs'), 1);
  }

  public function testShould_invalid_When_numberUserHaveSpace(): void
  {
    $this->assertHasErrors($this->getEntity()->setNumberUser('12 fdq'), 1);
  }

  public function testShould_invalid_When_numberUserMoreThan6Characters(): void
  {
    $this->assertHasErrors($this->getEntity()->setNumberUser('123qsdfqsd'), 1);
  }

  public function testShould_invalid_When_numberUserDoLessThan6Characters(): void
  {
    $this->assertHasErrors($this->getEntity()->setNumberUser('1ab2'), 2);
  }

// TEST FIRSTNAME VALIDATION

  public function testShould_invalid_When_firstnameIsBlank():void
  {
    $this->assertHasErrors($this->getEntity()->setFirstname(''),2);
  }

  public function testShould_invalid_When_FirstnameMoreThan50Characters(): void
  {
    $this->assertHasErrors($this->getEntity()->setFirstname('jesuisuntrésgrandprénomquidépassesurmentlescinquantescaracteres'), 1);
  }

  public function testShould_invalid_When_FirstnameDoLessThan3Characters(): void
  {
    $this->assertHasErrors($this->getEntity()->setFirstname('al'), 1);
  }
    
// TEST FIRSTNAME VALIDATION

  public function testShould_invalid_When_lastnameIsBlank():void
  {
    $this->assertHasErrors($this->getEntity()->setLastname(''),2);
  }

  public function testShould_invalid_When_lastnameMoreThan50Characters(): void
  {
    $this->assertHasErrors($this->getEntity()->setLastname('jesuisuntrésgrandprénomquidépassesurmentlescinquantescaracteres'), 1);
  }

  public function testShould_invalid_When_lastnameDoLessThan3Characters(): void
  {
    $this->assertHasErrors($this->getEntity()->setLastname('al'), 1);
  }

// TEST PHONE NUMBER VALIDATION

  public function testShould_invalid_When_PhoneNumberIsBlank():void
  {
    $this->assertHasErrors($this->getEntity()->setPhoneNumber(''),2);
  }

  public function testShould_invalid_When_PhoneNumberHaveSpecialOrLetterCharacters():void
  {
    $this->assertHasErrors($this->getEntity()->setPhoneNumber('fe321sqfd1'),1);
    $this->assertHasErrors($this->getEntity()->setPhoneNumber('_4é(§è§(è4'),1);
  }

  public function testShould_invalid_When_PhoneNumberDontStartWith0():void
  {
    $this->assertHasErrors($this->getEntity()->setPhoneNumber('1234567891'),1);
  }

  public function testShould_isValid_When_PhoneNumberStartWith0():void
  {
    $this->assertHasErrors($this->getEntity()->setPhoneNumber('0123456789'),0);
  }

  public function testShould_invalid_When_PhoneNumberNotEqual10Numbers(): void
  {
    $this->assertHasErrors($this->getEntity()->setPhoneNumber('01234567891231654'),1);
  }

// TEST MAIL VALIDATION

  public function testShould_invalid_When_MailIsBlank():void
  {
    $this->assertHasErrors($this->getEntity()->setEmail(''),1);
  }

  public function testShould_isValid_When_MailIsNormalMail():void
  {
    $this->assertHasErrors($this->getEntity()->setEmail('francois.test@test.com'),0);
  }

  public function testShould_invalid_When_MailIsNotNormalMail():void
  {
    $this->assertHasErrors($this->getEntity()->setEmail('francois.test.com'),1);
    $this->assertHasErrors($this->getEntity()->setEmail('francois.test@testcom'),1);
  }
}