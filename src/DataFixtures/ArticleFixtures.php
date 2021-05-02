<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker::create('fr_FR');
        \Bezhanov\Faker\ProviderCollectionHelper::addAllProvidersTo($faker);
        // $faker->addProvider(new \Bezhanov\Faker\Provider\Placeholder($faker));
        // $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        for ($i = 1; $i <= 30; $i++) {
            $article = new Article();
            $article->setName($faker->productName)
                ->setImage($faker->placeholder('200x200', 'jpg'))
                ->setDescription($faker->paragraph(3))
                ->setDate($faker->dateTimeThisDecade())
                ->setPrice($faker->randomNumber(3));

            $manager->persist($article);
        }
        $manager->flush();
    }
}
