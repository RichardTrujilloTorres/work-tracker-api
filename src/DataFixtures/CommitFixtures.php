<?php

namespace App\DataFixtures;

use App\Entity\Commit;
use App\Entity\Entry;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CommitFixtures extends Fixture
{
    protected function getData()
    {
        return [
            [
                'repository' => 'work-tracker-api',
                'branch' => 'master',
                'commitsNumber' => 13,
                'date' => '2019-08-23 12:55',
                'entry_id' => 2,
            ],
            [
                'repository' => 'work-tracker-api',
                'branch' => 'master',
                'commitsNumber' => 10,
                'date' => '2019-09-07 16:18',
                'entry_id' => 2,
            ],
        ];
    }
    public function load(ObjectManager $manager)
    {
        foreach ($this->getData() as $data) {
            $commit = new Commit();
            $commit->setRepository($data['repository']);
            $commit->setBranch($data['branch']);
            $commit->setCommitsNumber($data['commitsNumber']);
            $commit->setDate(new \DateTime($data['date']));

            if (! empty(@$data['entry_id'])) {
                $entry = $manager->getRepository(Entry::class)
                    ->find(@$data['entry_id']);

                if ($entry) {
                    $commit->setEntry($entry);
                }
            }

            $manager->persist($commit);
        }

        $manager->flush();
    }
}
