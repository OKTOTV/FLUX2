<?php

namespace AppBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Series;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
/**
 * Series backend controller.
 *
 * @Route("/backend/oktothek")
 */
class SeriesController extends Controller
{
    /**
     * @Route("/series/{uniqID}", name="oktothek_series_backend")
     * @Method("GET")
     * @Template
     */
    public function indexAction($uniqID)
    {
        $series = $this->get('oktolab_media')->getSeries($uniqID);
        return ['series' => $series];
    }
}
