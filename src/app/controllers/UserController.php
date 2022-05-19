<?php

use Phalcon\Mvc\Controller;


class UserController extends Controller
{
    public function indexAction()
    {
        if (!$this->session->get('activeUser') || $this->session->get('activeRole')!=="user") {
            header('location:/');
        } else {
            $this->view->users = Users::find();
            // return '<h1>Hello World!</h1>';
        }
    }
    public function blogsAction()
    {
        if (!$this->session->get('activeUser') || $this->session->get('activeRole')!=="user") {
            header('location:/');
        } else {

            $this->view->blog = Blog::find();
            // return '<h1>Hello World!</h1>';
        }
    }
    public function readmoreAction($blog_id)
    {
        if (!$this->session->get('activeUser') || $this->session->get('activeRole')!=="user") {
            header('location:/');
        } else {

            $blog = new Blog();
            $this->view->blog_id = $blog_id;
            $this->view->blog = Blog::find();
        }
    }
    public function logoutAction()
    {
        $this->session->destroy();
        $this->response->redirect("/");
    }
}
