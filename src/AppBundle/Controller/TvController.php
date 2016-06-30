<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
    public function tvAction()
    {
        $current = $this->get('oktothek_tv')->getCurrent();
        return ['current' => $current];
    }

    /**
     * @Route("/program/{date}.{_format}", defaults={"date": "now", "_format": "html"}, name="oktothek_tv_program_for_date")
     * @Template
     */
    public function programAction(Request $request, $date = "now")
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
    public function currentAction()
    {
        $current = $this->get('oktothek_tv')->getCurrent();
        return ['current' => $current];
    }
}
