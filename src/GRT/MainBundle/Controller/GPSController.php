<?php
namespace GRT\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GRT\MainBundle\Entity\GPSLocation;

class GPSController extends Controller {
    
    public function postAction(Request $request) {
        $phone = $request->request->get("phone");
        $longitude = $request->request->get("longitude");
        $latitude = $request->request->get("latitude");
        $updated = $request->request->get("updated");

        $date = date_create_from_format("Y-m-d H:i:s", $updated);
        $gpsLocation = new GPSLocation();
        $gpsLocation->setNumber($phone)
                    ->setLatitude($latitude)
                    ->setLongitude($longitude)
                    ->setDate($date);
        $em = $this->getDoctrine()->getManager();
        $em->persist($gpsLocation);
        $em->flush();
        $response = new Response(json_encode(array("success" => true)));
        return $response;
    }
    
    public function indexAction($phone) {
        $repository = $this->getDoctrine()->getRepository("GRTMainBundle:GPSLocation");
        
        $query = $repository->createQueryBuilder('g')
                ->where('g.number = :number')
                ->setParameter('number',$phone)
                ->orderBy('g.date', 'DESC')
                ->getQuery();
        
        $locations = $query->getResult();
        
        return $this->render("GRTMainBundle:GPS:index.html.twig", array("phone" => $phone, 'locations' => $locations));
    }

    public function testAction() {
        return $this->render("GRTMainBundle:GPS:test.html.twig");
    }
}


