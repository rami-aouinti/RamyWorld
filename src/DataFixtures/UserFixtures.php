<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Profile;
use App\Entity\Project;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

/**
 * Class UserFixtures
 * @package App\Application\DataFixtures
 */
class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    protected $faker;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        $tags = [];

        for($i = 1; $i <= 5; $i++)
        {
            $tag = new Tag();
            $tag->setName($this->faker->word);
            $tag->setType($this->faker->word);
            $manager->persist($tag);
            $tags[] = $tag;
        }

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail("email$i@email.com");
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, "password"));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);

            $profile = new Profile();
            $profile->setFirstname($this->faker->firstName);
            $profile->setLastname($this->faker->lastName);
            $profile->setUser($user);
            $manager->persist($profile);

            for ($j = 1; $j <= 5; $j++) {
                $project = new Project();
                $project->setLogo($this->faker->word . ".png");
                $project->setName($this->faker->title);
                $project->setDescription($this->faker->word);
                $project->setActive(true);
                $project->setStartDate($this->faker->dateTime);
                $project->setEndDate($this->faker->dateTime);
                $project->setFile('test.png');
                $project->setAuthor($user);
                $project->addTag($tags[$j-1]);
                $project->addTeam($user);
                $manager->persist($project);
            }
        }
        $manager->flush();
    }
}
