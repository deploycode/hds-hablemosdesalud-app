<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    $f_post = $em->getRepository('AppBundle:Post')->findOneByIsFavorite(1);
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
  public function correoAction(Request $request, \Swift_Mailer $mailer)
  {
    $message = (new \Swift_Message('Hola'))
        ->setFrom('web@hablemosdesalud.com.co')
        ->setTo('promocionyprevencion@hablemosdesalud.com.co')
        ->setSubject('Mensaje de Hablemos de Salud')
        ->setBody(
            $this->renderView(
                'mail.html.twig',
                array(
                        'name' => $request->request->get('name'),
                        'phone' => $request->request->get('phone'),
                        'correo' => $request->request->get('email')
                      )
            ),
            'text/html'
        );

    $this->addFlash(
      'notice',
      ', hemos recibido sus datos, en breve le contactaremos'
    );

    $mailer->send($message);
    return $this->redirectToRoute('inicio');
  }
  /**
  *@Route("/autoComplete", name="autoComplete")
  */
  public function autoComplete(Request $request) {
    $query = $request->get('term','');
    $em = $this->getDoctrine()->getManager();
    $sql = $em->createQuery("SELECT p FROM AppBundle\Entity\Post p WHERE p.title LIKE :query ");
    $sql->setParameter('query', '%'.$query.'%');
    $allPosts = $sql->getResult();
    $data=array();
    foreach ($allPosts as $post) {
      $data[]=array('post'=> $post->getSlug(), 'value'=>$post->getTitle(),'id'=>$post->getId());
    }
    if(count($data))
      return new JsonResponse($data);
    else
      return new JsonResponse(['value'=>'No se han encontrado resultados','id'=>'']);
  }
}
