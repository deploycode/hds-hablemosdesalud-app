<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Utils\Slugger;

/**
 * Post controller.
 *
 * @Route("post")
 */
class PostController extends Controller
{
    /**
     * Lists all post entities.
     *
     * @Route("/", name="post_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')->findAll();

        return $this->render('post/index.html.twig', array(
            'posts' => $posts,
        ));
    }

    /**
     * Creates a new post entity.
     *
     * @Route("/new", name="post_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Slugger $slugger)
    {
        $post = new Post();
        $form = $this->createForm('AppBundle\Form\PostType', $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $this->get('AppBundle\Utils\Slugger')->slugify($post->getTitle());
            $post->setSlug($slug);

            $file=$form['image']->getData();
            $ext=$file->guessExtension();
            $file_name=md5(uniqid()).".".$ext;
            $file->move("uploads", $file_name);
            $post->setImage($file_name);

            $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
            $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
            $post->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('articulo', array('slug' => $post->getSlug()));
        }

        return $this->render('post/new.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a post entity.
     *
     * @Route("/{id}", name="post_show")
     * @Method("GET")
     */
    public function showAction(Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);

        return $this->render('post/show.html.twig', array(
            'post' => $post,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing post entity.
     *
     * @Route("/{id}/edit", name="post_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Post $post, Slugger $slugger)
    {
        $oldImage= $post->getImage();
        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createForm('AppBundle\Form\PostType', $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $slug = $this->get('AppBundle\Utils\Slugger')->slugify($post->getTitle());
            $post->setSlug($slug);

            if (!empty($editForm['image']->getData()))
            {
                unlink('uploads/'.$oldImage);
                $file=$editForm['image']->getData();
                $ext=$file->guessExtension();
                $file_name=md5(uniqid()).".".$ext;
                $file->move("uploads", $file_name);
                $post->setImage($file_name);
            }else{
                $post->setImage($oldImage);
            }

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('post_edit', array('id' => $post->getId()));
        }

        return $this->render('post/edit.html.twig', array(
            'post' => $post,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a post entity.
     *
     * @Route("/{id}", name="post_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Post $post)
    {
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();

            $image=$post->getImage();
            unlink('uploads/'.$image);
        }

        return $this->redirectToRoute('post_index');
    }

    /**
     * Creates a form to delete a post entity.
     *
     * @param Post $post The post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    /**
     * Displays a form to edit an existing post entity.
     *
     * @Route("/{id}/upgrade", name="post_upgrade")
     * @Method({"GET", "POST"})
     */
    public function upgrade($id){
      $em = $this->getDoctrine()->getManager();
      $old_f_post = $em->getRepository('AppBundle:Post')->findOneByIsFavorite(1);
      $old_f_post->setIsFavorite(0);

      $new_f_post= $em->getRepository('AppBundle:Post')->findOneById($id);
      $new_f_post->setIsFavorite(1);

      $this->getDoctrine()->getManager()->flush();
      return $this->redirectToRoute('post_index');
    }
}
