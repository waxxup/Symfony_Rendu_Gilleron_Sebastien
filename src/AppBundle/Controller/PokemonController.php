<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PokemonController extends Controller
{
    /**
     * @Route("/all-pokemon", name="pokemons")
     */
    public function allPokemonAction()
    {

        $manager = $this
            ->getDoctrine()
            ->getManager();

        $pokemonRepository = $manager->getRepository('AppBundle:Pokemon\Pokemon');

        $pokemons = $pokemonRepository->findAll();

        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $json = $serializer->serialize($pokemons, 'json');

        return new JsonResponse($json, 200, [], true);
    }
    /**
     * @Route("/all-users", name="user")
     */
    public function allUserAction()
    {

        $manager = $this
            ->getDoctrine()
            ->getManager();

        $userRepository = $manager->getRepository('AppBundle:User\User');

        $users = $userRepository->findAll();

        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $json = $serializer->serialize($users, 'json');

        return new JsonResponse($json, 200, [], true);
    }
    /**
     * @Route("/find/{userId}", name="find")
     */
    public function findAction($userId)
    {

        $pokemonBehavior = $this->get('behavior.pokemon');
        //création d'un random pokemon pour le user avec l'id obtenu dans la route
        $randomPokemon = $pokemonBehavior->createRandomPokemon($userId);

        $manager = $this
            ->getDoctrine()
            ->getManager();


        $manager->persist($randomPokemon);


        $manager->flush();



        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $json = $serializer->serialize($randomPokemon, 'json');

        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/catch/{id}", name="catch")
     */
    public function catchAction($id)
    {


        $manager = $this
            ->getDoctrine()
            ->getManager();

        $pokemonUserStatsRepository = $manager->getRepository('AppBundle:Pokemon\UserPokemonStats');

        $catched = $pokemonUserStatsRepository->find($id);



    if($catched->getCaptured(true))
    {
        $alreadyCaptured = "vous avez déjà ce pokémon";
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $json = $serializer->serialize($alreadyCaptured, 'json');
        return new JsonResponse($json, 200, [], true);
    }
    elseif ((rand(1,100)) < 50)
    {
        $catched->setCaptured(true);
    }
    else
    {
        $miss = "oh non vous n'avez pas réussi à l'attraper !";
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $json = $serializer->serialize($miss, 'json');
        return new JsonResponse($json, 200, [], true);

    }

        $manager->persist($catched);

        $manager->flush();


        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $json = $serializer->serialize($catched, 'json');

        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/pokedex/{userid}", name="pokedex")
     */
    public function pokedexAction($userid)
    {


        $manager = $this
            ->getDoctrine()
            ->getManager();

        $pokemonUserStatsRepository = $manager->getRepository('AppBundle:Pokemon\UserPokemonStats');

        $userPokedex = $pokemonUserStatsRepository->findBy(
            array('user' => $userid,
                  'captured' => true
            ));


        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $json = $serializer->serialize($userPokedex, 'json');

        return new JsonResponse($json, 200, [], true);
    }
}