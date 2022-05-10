<?php
namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

use App\Entity\User; 
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// class LoadImmatriculationsData extends Fixture
// {
class LoadUserData extends Fixture implements FixtureGroupInterface
{
    protected $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder )
    {
        $this->encoder=$encoder;

    }
  
    public function load(ObjectManager $manager)
    {
       
     
        $users=[['username'=>'admin','password'=>'adminpass','apitoken'=>'100972','roles'=>['ROLE_ADMIN']]]; 

        foreach($users as $key=> $user){
            $objUser = new User;
            $objUser->setUsername($user['username']);
            $objUser->setPassword($this->encoder->encodePassword($objUser,$user['password']));
            $objUser->setapiToken($user['apitoken']);
            $objUser->setRoles($user['roles']);

            $manager->persist($objUser);
        }

        
  // mise à jour de la base avec tous les persists
        $manager->flush();


    }
    public static function getGroups():array
    {

        return ['group3']; 
    }
     
}

