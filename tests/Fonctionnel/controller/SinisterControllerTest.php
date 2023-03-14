<?php

namespace App\Tests;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SinisterControllerTest extends WebTestCase
{


    function getSubmittingForm(
        string $adress = '13 boulevard du test 16780 test', 
        string $description = 'je suis une description valide de plus de 20 caractères', 
        string $numberRegistration = 'xx-123-xx')
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/declaration');

        return $client->submitForm('declaration_sinister_submit',[
            'declaration_sinister[adressOfSinister]' => $adress,
            'declaration_sinister[description]' => $description,
            'declaration_sinister[numberRegistration]' => $numberRegistration
        ]);
    }
    
    
    /**  test que la route fonctionne et que le titre s'affiche correctement
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
        $this->assertSelectorExists('#declaration_sinister_submit');
        $this->assertSelectorExists('#declaration_sinister_cancel');
    }



    /** Test que l'on est bien rediriger vers la home page quand on soumet un formulaire valide
     * @return void
     */
    public function testShould_RedirectToHomePage_When_submitValidSinisterDeclarationType(): void
    {
        $this->getSubmittingForm();
        $this->assertResponseRedirects('/', Response::HTTP_FOUND);
    }


    /** test l'affichage sur la home page lorsque l'on soummet un formulaire valide
     * @return void
     */
    public function testShould_DisplayASuccessMessage_When_submitValidSinisterDeclarationType(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/declaration');
        $client->submitForm('declaration_sinister_submit',[
            'declaration_sinister[adressOfSinister]' => '13 boulevard du test 16470 testville',
            'declaration_sinister[description]' => 'je suis rentré dans une autre voiture test avec ma voiture et badaboum',
            'declaration_sinister[numberRegistration]' => 'xx-123-xx'
        ]);
        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('p', 'Votre déclaration nous a bien été transmise');
    }

    
    /** tester l'affichage d'un messages danger

     * @return void
     */
    public function testShould_DisplayADangerMessage_When_submitInvalidSinisterDeclarationType(): void
    {
        $this->getSubmittingForm(
            '13 boulevard du test 16470 testville', 
            'je suis rentré dans une autre voiture test avec ma voiture et badaboum',
            'xx-123-x'
        );
        $this->assertSelectorTextContains('p', 'Il y a des erreurs dans la saisi du formulaire.');
    }


    /** Test du boutton annuler qui renvoie sur la homepage
     * @return void
     */
    public function testShould_SendToHomePage_When_ClickOnCancelButton(): void
    {  
        $client = static::createClient();
        $crawler = $client->request('GET', '/declaration');
        $client->clickLink('Annuler');
        $this->assertEquals('/', $client->getRequest()->getPathInfo());
    }

}
