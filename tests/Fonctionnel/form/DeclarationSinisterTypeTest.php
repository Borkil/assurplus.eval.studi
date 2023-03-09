<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeclarationSinisterTypeTest extends WebTestCase
{


    function getSubmittingForm(string $adress, string $description, $numberRegistration)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/declaration');

        return $client->submitForm('Valider',[
            'declaration_sinister[adressOfSinister]' => $adress,
            'declaration_sinister[description]' => $description,
            'declaration_sinister[numberRegistration]' => $numberRegistration
        ]);
    }
    
    /** 
     * @return void
     */
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/declaration');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Déclarer votre sinistre');
    }

    /** tester l'affichage des differents champs du formulaire 
     * @return void
     */
    public function testDisplayFormIsOk(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/declaration');
        $this->assertSelectorExists('#declaration_sinister_adressOfSinister');
        $this->assertSelectorExists('#declaration_sinister_description');
        $this->assertSelectorExists('#declaration_sinister_numberRegistration');
        $this->assertSelectorExists('#declaration_sinister_valider');
    }

    // tester l'affichage d'un messages success

    public function testDisplayMessageIfSubmitIsOk(): void
    {
        //soumettre le formulaire avec de bonne valeur
        $this->getSubmittingForm(
            '13 boulevard du test 16470 testville', 
            'je suis rentré dans une autre voiture test avec ma voiture et badaboum',
            'xx-123-xx'
        );
        
        $this->assertSelectorTextContains('p', 'Votre déclaration nous a bien été transmise');
    }

    // tester l'affichage d'un messages danger

    public function testDisplayMessageIfSubmitIsNotOk(): void
    {
        //soumettre le formulaire avec de bonne valeur
        $this->getSubmittingForm(
            '13 boulevard du test 16470 testville', 
            'je suis rentré dans une autre voiture test avec ma voiture et badaboum',
            'xx-123-x'
        );
        
        $this->assertSelectorTextContains('p', 'Il y a des erreurs dans la saisi du formulaire.');
    }


}


// 'declaration_sinister[adressOfSinister]' => '13 boulevard du test 16470 testville',
//             'declaration_sinister[description]' => 'je suis rentré dans une autre voiture test avec ma voiture et badaboum',
//             'declaration_sinister[numberRegistration]' => 'xx-123-xx'