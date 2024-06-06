<?php

namespace App\Test\Controller;

use App\Entity\Etape;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EtapeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/etape/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Etape::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Etape index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'etape[texte_ambiance]' => 'Testing',
            'etape[libelle]' => 'Testing',
            'etape[aventureDeButee]' => 'Testing',
            'etape[aventure]' => 'Testing',
            'etape[finAventure]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Etape();
        $fixture->setTexte_ambiance('My Title');
        $fixture->setLibelle('My Title');
        $fixture->setAventureDeButee('My Title');
        $fixture->setAventure('My Title');
        $fixture->setFinAventure('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Etape');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Etape();
        $fixture->setTexte_ambiance('Value');
        $fixture->setLibelle('Value');
        $fixture->setAventureDeButee('Value');
        $fixture->setAventure('Value');
        $fixture->setFinAventure('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'etape[texte_ambiance]' => 'Something New',
            'etape[libelle]' => 'Something New',
            'etape[aventureDeButee]' => 'Something New',
            'etape[aventure]' => 'Something New',
            'etape[finAventure]' => 'Something New',
        ]);

        self::assertResponseRedirects('/etape/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTexte_ambiance());
        self::assertSame('Something New', $fixture[0]->getLibelle());
        self::assertSame('Something New', $fixture[0]->getAventureDeButee());
        self::assertSame('Something New', $fixture[0]->getAventure());
        self::assertSame('Something New', $fixture[0]->getFinAventure());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Etape();
        $fixture->setTexte_ambiance('Value');
        $fixture->setLibelle('Value');
        $fixture->setAventureDeButee('Value');
        $fixture->setAventure('Value');
        $fixture->setFinAventure('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/etape/');
        self::assertSame(0, $this->repository->count([]));
    }
}
