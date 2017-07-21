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
    //El post con el id 1
    $f_post = $em->getRepository('AppBundle:Post')->findOneById(3);
    //todos los posts
    $query = $em->getRepository('AppBundle:Post')->createQueryBuilder('p')
    ->orderBy('p.updatedAt', 'DESC')
    ->getQuery();
    $collage = $query->getResult();


    return $this->render('home.html.twig', array ('services'=>$services , 'f_post'=>$f_post , 'collage'=>$collage ));
  }
  /**
  *@Route("/servicios/{slug}", name="servicio")
  */
  public function servicioAction(Service $service)
  {
    $em = $this->getDoctrine()->getManager();
    $services = $em->getRepository('AppBundle:Service')->findAll();
    $query = $em->getRepository('AppBundle:Post')->createQueryBuilder('p')
    ->orderBy('p.updatedAt', 'DESC')
    ->getQuery();
    $collage = $query->getResult();
    return $this->render('service.html.twig' , array('service' => $service , 'services' => $services , 'collage'=>$collage) );
  }
  /**
  *@Route("/temas/{slug}", name="tema")
  */
  public function temaAction(Menu $menu)
  {
    $em = $this->getDoctrine()->getManager();
    $services = $em->getRepository('AppBundle:Service')->findAll();
    $query = $em->getRepository('AppBundle:Post')->createQueryBuilder('p')
    ->orderBy('p.updatedAt', 'DESC')
    ->getQuery();
    $collage = $query->getResult();
    return $this->render('menu.html.twig' , array('menu' => $menu, 'services' => $services , 'collage'=>$collage) );
  }
  /**
  *@Route("/articulos/{slug}", name="articulo")
  */
  public function articuloAction(Post $post)
  {
    $em = $this->getDoctrine()->getManager();
    $services = $em->getRepository('AppBundle:Service')->findAll();
    $query = $em->getRepository('AppBundle:Post')->createQueryBuilder('p')
    ->orderBy('p.updatedAt', 'DESC')
    ->getQuery();
    $collage = $query->getResult();
    return $this->render('post.html.twig' , array('post' => $post ,'services'=>$services , 'collage'=>$collage) );
  }
  /**
  *@Route("/email", name="correo")
  */
  public function correoAction()
  {
    return $this->redirectToRoute('inicio');
  }

}
