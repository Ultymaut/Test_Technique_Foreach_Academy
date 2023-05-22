<?php

namespace App\DataFixtures;

use App\Entity\Excuse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;

class ExcuseFixture extends Fixture
{
    public function load(ObjectManager $manager,): void
    {
        $json = file_get_contents('excuse.json');
        $data = json_decode($json, true);

        foreach ($data as $item) {
            $excuse = new Excuse();
            $excuse->setHttpCode($item['http_code']);
            $excuse->setTag($item['tag']);
            $excuse->setMessage($item['message']);
            $manager->persist($excuse);
        }

        $manager->flush();

    }
}
