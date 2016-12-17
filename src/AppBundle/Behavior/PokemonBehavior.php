<?php

namespace AppBundle\Behavior;

use AppBundle\Entity\Pokemon\UserPokemonStats;
use Doctrine\ORM\EntityManager;

class PokemonBehavior
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    public function createRandomPokemon($userId)
    {
        //recupération entité
        $userPokemonStats = new UserPokemonStats();

        //récupération de la liste des pokémon
        $pokemonRespository = $this->em->getRepository('AppBundle:Pokemon\Pokemon');
        $pokemons = $pokemonRespository->findAll();

        //Mélange de la liste
        shuffle($pokemons);
        //on récupère le pokemon au rang 0 du tableau
        $pokemon = $pokemons[0];

        //récupération des infos du user
        $userRespository = $this->em->getRepository('AppBundle:User\User');
        $user = $userRespository->find($userId);

        //attribution des stats et attribition d'un user au pokemon
        $userPokemonStats
            ->setPokemon($pokemon)
            ->setCombatPoint(mt_rand(10, 1500))
            ->setHp(rand(50, 1500))
            ->setSize(rand(1, 8))
            ->setWeight(rand(1, 150))
            ->setUser($user)
            ->setCaptured(false)
        ;

        return $userPokemonStats;
    }
}