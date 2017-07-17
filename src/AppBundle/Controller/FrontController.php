<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Service;
use AppBundle\Entity\Menu;
use AppBundle\Entity\Post;

class FrontController extends Controller
{
  /**
  *@Route("/", name="inicio")
  */
  public function incioAction()
  {
    $em = $this->getDoctrine()->getManager();
    $services = $em->getRepository('AppBundle:Service')->findAll();

    return $this->render('home.html.twig', array ('services'=>$services));
  }
  /**
  *@Route("/servicios/{slug}", name="servicio")
  */
  public function servicioAction(Service $service)
  {
      return $this->render('service.html.twig' , array('service' => $service) );
  }
  /**
  *@Route("/temas/{slug}", name="tema")
  */
  public function temaAction(Menu $menu)
  {
    return $this->render('menu.html.twig' , array('menu' => $menu) );
  }
  /**
  *@Route("/articulos/{slug}", name="articulo")
  */
  public function articuloAction(Post $post)
  {
    return $this->render('post.html.twig' , array('post' => $post) );
  }
  /**
  *@Route("/email", name="correo")
  */
  public function correoAction()
  {
    return $this->redirectToRoute('inicio');
  }

}
