<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Factory\PostFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        PostFactory::createMany(10);
    }

}
