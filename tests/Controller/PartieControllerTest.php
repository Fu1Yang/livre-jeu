<?php

namespace App\Test\Controller;

use App\Entity\Partie;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PartieControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/partie/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Partie::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Partie index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'partie[date_partie]' => 'Testing',
            'partie[aventurier]' => 'Testing',
            'partie[aventure]' => 'Testing',
            'partie[etape]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Partie();
        $fixture->setDate_partie('My Title');
        $fixture->setAventurier('My Title');
        $fixture->setAventure('My Title');
        $fixture->setEtape('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Partie');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Partie();
        $fixture->setDate_partie('Value');
        $fixture->setAventurier('Value');
        $fixture->setAventure('Value');
        $fixture->setEtape('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'partie[date_partie]' => 'Something New',
            'partie[aventurier]' => 'Something New',
            'partie[aventure]' => 'Something New',
            'partie[etape]' => 'Something New',
        ]);

        self::assertResponseRedirects('/partie/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDate_partie());
        self::assertSame('Something New', $fixture[0]->getAventurier());
        self::assertSame('Something New', $fixture[0]->getAventure());
        self::assertSame('Something New', $fixture[0]->getEtape());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Partie();
        $fixture->setDate_partie('Value');
        $fixture->setAventurier('Value');
        $fixture->setAventure('Value');
        $fixture->setEtape('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/partie/');
        self::assertSame(0, $this->repository->count([]));
    }
}
