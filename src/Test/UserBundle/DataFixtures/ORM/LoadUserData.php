<?php

/**
 * Author: Podluzhnyy Vladimir aka Quber
 * Contact: quber.one@gmail.com
 * Date & Time: 12.11.2014 / 19:05
 * Filename: TestUserBundle.php
 * Notes: Special for Domotehnika
 */

namespace Test\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Test\UserBundle\Entity\User;
use Faker\Factory;

/**
 * Class LoadUserData
 * @package Test\UserBundle\DataFixtures\ORM
 */
class LoadUserData implements FixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('ru_RU');

        for ($i = 0; $i < 25; $i++)
        {
            $entity = new User();
            $entity->setEmail($faker->freeEmail);
            $entity->setNick($faker->name);
            $entity->setUsername($faker->userName);

            // Password generation
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($entity);
            $entity->setPassword(
                $encoder->encodePassword(
                    $faker->password, $entity->getSalt()
                )
            );

            $manager->persist($entity);
        }

        // Demo
        $demo = new User();
        $demo->setUsername("demo");
        $entity->setNick("demo");
        $demo->setEmail('demo@demo.ru');
        $demo->setEnabled(true);

        $encoder = $this->container->get('security.encoder_factory')->getEncoder($demo);
        $demo->setPassword(
            $encoder->encodePassword(
                'demo', $demo->getSalt()
            )
        );

        $manager->persist($demo);
        $manager->flush();

    }

}