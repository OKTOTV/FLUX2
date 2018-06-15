<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\RegisterType;

/**
 * @Route("/tv")
 */
class TvController extends Controller
{
    /**
     * @Route("/", name="tv")
     * @Template
     */
    public function TvAction(Request $request)
    {
        $current = $this->get('oktothek_tv')->getCurrent(false, 2);
        $date = new \DateTime($request->query->get('date', 'now'));
        return [
            'current' => $current,
            'url' => $this->generateUrl('oktothek_tv_program_for_date', ['date' => $date->format('d-m-Y')]),
            'date' => $date
        ];
    }

    /**
     * @Route("/embed", name="tv_embed")
     * @Template()
     */
    public function embedAction()
    {
        return [];
    }

    /**
     * @Route("/program/{date}.{_format}", defaults={"date": "now", "_format": "html"}, name="oktothek_tv_program_for_date")
     * @Template()
     */
    public function programAction(Request $request, $date)
    {
        $start = new \Datetime($date);
        $start->setTime(8, 0);
        $end = new \Datetime($date);
        $end->modify('+1 day');
        $end->setTime(8, 0);

        if ($request->getMethod() != "GET") {
            $start = new \Datetime($request['start']);
            $end = new \Datetime($request['end']);
        }

        $shows = $this->get('oktothek_tv')->getShows($start, $end);
        return ['shows' => $shows, 'datetime' => $start, 'date' => $start];
    }

    /**
    * @Route("/current.{_format}", name="oktothek_tv_current_show", defaults={"_format": "html"})
    * @Template()
    */
    public function currentAction($_format)
    {
        $current = $this->get('oktothek_tv')->getCurrent(false, 2);
        switch ($_format) {
            case 'html':
                return ['current' => $current];
            case 'json':
                return new JsonResponse($current);
            default:
                $current = $this->get('oktothek_tv')->getCurrent(false, 2);
                return ['current' => $current];
        }

    }
}
