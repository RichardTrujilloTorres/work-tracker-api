<?php

namespace App\DataFixtures;

use App\Entity\Entry;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EntriesFixtures extends Fixture
{
    protected function getData()
    {
        return [
            [
                'startTime' => '2019-08-19 8:37',
                'endTime' => '2019-08-19 10:40',
                'description' => 'Order return resources',
            ],
            [
                'startTime' => '2019-08-19 8:37',
                'endTime' => '2019-08-19 9:05',
                'description' => 'Api Platform review',
            ],
            [
                'startTime' => '2019-09-07 18:02',
                'endTime' => '2019-00-07 21:05',
                'description' => 'Work Tracker: Travis setup',
            ],
        ];
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $data) {
            $entry = new Entry();
            $entry->setStartTime(new \DateTime($data['startTime']));
            $entry->setEndTime(new \DateTime($data['endTime']));
            $entry->setDescription($data['description']);

            $manager->persist($entry);

            // TODO commits
        }

        $manager->flush();
    }
}
