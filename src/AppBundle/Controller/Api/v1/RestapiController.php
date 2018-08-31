<?php

namespace AppBundle\Controller\Api\v1;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Teams;
use AppBundle\Entity\League;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\JsonResponse;

Debug::enable();

class RestapiController extends Controller {

    /**
     * @Route("/api/v1", name="index")
     */
    public function indexAction() {
// replace this example code with whatever you need
        return new Response("Please enter your API function!");
    }

    /**
     * @Route("/api/v1/getTeams/{league}")
     * @Method("GET")
     * @param $league
     */
    public function GetTeamAction($league = 'UK Premier') {


        $leagues = $this->getDoctrine()
                ->getRepository(League::class)
                ->findBy(['name' => $league]);


        if (!$leagues) {
            throw $this->createNotFoundException(
                    'No teams found for ' . $league . ' league!'
            );
        }

        $response = array();
        foreach ($leagues as $data) {

            $response[] = array(
                'league_id' => $data->getId(),
                'league' => $data->getName()
            );
        }
        $teams = $this->getDoctrine()
                ->getRepository(Teams::class)
                ->findBy(['strip' => $response[0]['league_id']]);
        foreach ($teams as $team) {

            $response[] = array(
                'id' => $team->getId(),
                'name' => $team->getName());
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/api/v1/createTeam", name="create_team")
     * @Method("POST")
     */
    public function CreateTeamAction(Request $request) {

// Get the Query Parameters from the URL
        $fteam = $request->query->get('team');
        $league = $request->query->get('league');
        if (!$fteam || !$league) {
            throw $this->createNotFoundException(
                    'No values to send found!'
            );
        }
        $teams = new Teams();
        $leag = new League();

// Use methods from the Team entity to set the values
        $leag->setName($league);
        // relates this team to the league
// Get the Doctrine service and manager
        $db = $this->getDoctrine()->getManager();

// Add our record to Doctrine so that it can be saved
        $db->persist($leag);
// Save our record
        $db->flush();
        $lid = $leag->getId();
        $teams->setName($fteam);
        $teams->setStrip($lid);
// Add our record to Doctrine so that it can be saved
        $db->persist($teams);

// Save our record
        $db->flush();

        return new Response("Team was added!", 200);
    }

    /**
     * @Route("/api/v1/updateTeam/{id}", name="update_team")
     * @Method("PUT")
     */
    public function UpdateTeamAction($id = null, Request $request) {
        $team_name = $request->query->get('name');
        $strip = $request->query->get('strip');

        if (!$team_name || !$strip) {
            throw $this->createNotFoundException(
                    'No values to send found!'
            );
        }

        $db = $this->getDoctrine()->getManager();
        $team = $this->getDoctrine()->getRepository('AppBundle:Teams')->find($id);
        $leauge_data = $this->getDoctrine()->getRepository('AppBundle:League')->findOneBy(['name' => $strip]);

        if (empty($team)) {
            return new Response("team not found", Response::HTTP_NOT_FOUND);
        } elseif (!empty($team_name) && !empty($strip)) {
            $team->setName($team_name);
            $lid = $team->getStrip();
            $leage = $this->getDoctrine()->getRepository('AppBundle:League')->find($lid);
            var_dump($lid);
            var_dump($leauge_data);
            if (!isset($leauge_data)) {
                $teams = new Teams();
                $leag = new League();
                $leag->setName($strip);
                $db = $this->getDoctrine()->getManager();
                $db->persist($leag);
                $db->flush();
                $lid = $leag->getId();
                $teams->setName($team_name);
                $teams->setStrip($lid);
                $db->persist($teams);
                $db->flush();
            } elseif ($leage->getName() != $leauge_data->getName()) {
                $stripid = $leauge_data->getId();
                $team->setStrip($stripid);
                $db->flush();
            } else {
                $leage->setName($strip);
                $db->flush();
            }
            return new Response("Team Updated Successfully", Response::HTTP_OK);
        } elseif (empty($team_name) && !empty($strip)) {
            $leage = $this->getDoctrine()->getRepository('AppBundle:League')->find($strip);
            $leage->setName($strip);
            $db->flush();
            return new Response("League Updated Successfully", Response::HTTP_OK);
        } elseif (!empty($team_name) && empty($strip)) {
            $team->setName($team_name);
            $db->flush();
            return new Response("Team Name Updated Successfully", Response::HTTP_OK);
        } else {
            return new Response("Team name or league cannot be empty", Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    /**
     * @Route("/api/v1/deleteLeague/{name}", name="delete_league")
     * @Method("DELETE")
     */
    public function deleteLeagueAction($name) {
        $db = $this->getDoctrine()->getManager();
        $team = $this->getDoctrine()->getRepository('AppBundle:Teams')->findBy(['strip' => $name]);
        $leag = $this->getDoctrine()->getRepository('AppBundle:League')->findOneBy(['name' => $name]);
        if (empty($team) && empty($leag)) {
            return new Response("team not found", Response::HTTP_NOT_FOUND);
        } else {
            foreach ($team as $records) {
                $db->remove($records);
            }
            $db->remove($leag);
            $db->flush();
        }
        return new Response("deleted successfully", Response::HTTP_OK);
    }

    /**
     * @Route("/{url}", name="remove_trailing_slash",
     *     requirements={"url" = ".*\/$"})
     */
    public function removeTrailingSlash(Request $request) {
        $pathInfo = $request->getPathInfo();
        $requestUri = $request->getRequestUri();
        $url = str_replace($pathInfo, rtrim($pathInfo, ' /'), $requestUri);
        return $this->redirect($url, 301);
    }

}
