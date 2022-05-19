<?php

use Phalcon\Mvc\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        if (!$this->session->get('activeUser') || $this->session->get('activeRole') !== "admin") {
            header('location:/');
        }
        // $this->view->users = blog::find();
    }
    public function dashboardAction()
    {
        if (!$this->session->get('activeUser') || $this->session->get('activeRole') !== "admin") {
            header('location:/');
        } else {
            $this->view->users = Users::find();
            $users = Users::find();
            $statusName = $this->request->getpost('statusName');
            $id = $this->request->getpost('id');
            $change = $this->request->getpost('change');
            $delete = $this->request->getpost('delete');
            foreach ($users as $row) {
                if ($row->user_id == $id) {
                    if ($this->request->getpost('delete')) {
                        $row->delete($id);
                        header('location:/admin/dashboard');
                    } elseif ($this->request->getpost('change')) {
                        if ($row->status == "pending") {
                            $row->status = "approved";
                            $row->save();
                            header('location:/admin/dashboard');
                        } else {
                            if ($row->status == "approved") {
                                $row->status = "pending";
                                $row->save();
                                header('location:/admin/dashboard');
                            }
                        }
                    }
                }
            }
        }
    }
    public function blogAction()
    {
        if (!$this->session->get('activeUser') || $this->session->get('activeRole') !== "admin") {
            header('location:/');
        } else {
            if ($this->request->getPost("readmore")) {
                $blog_id = $this->request->getPost("blogid");
                header('location:/admin/readmore/' . $blog_id);
            }

            if ($this->request->getPost("editBlog")) {
                $blog_id = $this->request->getPost("blogid");
                header('location:/admin/editblog/' . $blog_id);
            }


            if ($this->request->getPost("dltBlog")) {
                $blog = new Blog();
                $blog_id = $this->request->getPost("blogid");
                $blog = Blog::find(["blog_id='$blog_id'"]);

                foreach ($blog as $row) {
                    if ($row->blog_id == $blog_id) {
                        $row->delete($blog_id);
                        header('location:/admin/blog');
                    }
                }
            }
            $this->view->blog = Blog::find();
        }
    }

    public function addblogAction()
    {
        if (!$this->session->get('activeUser') || $this->session->get('activeRole') !== "admin") {
            header('location:/');
        } else {
            $blog = new Blog();
            // $blog = Blog::find();
            $this->view->blog = Blog::find();
            if ($this->request->getPost('addBlog')) {
                //print_r($this->request->getPost());
                $this->view->users = Blog::find();
                $blog->assign(
                    $postdata = $this->request->getPost(),
                    [
                        'title',
                        'content',

                    ]
                );
                // $user->role = 'user';
                // $emailExists = $this->view->users = Users::find($postdata['email']);
                if (empty($postdata['content']) || empty($postdata['title'])) {
                    $this->view->blogError = '*Both fields are required';
                } elseif (empty($postdata['title'])) {
                    $this->view->blogError = '*Title is required';
                } elseif (empty($postdata['content'])) {
                    $this->view->blogError = '*Content is required';
                } else {
                    $success = $blog->save();
                    $this->view->success = $success;
                    if ($success) {
                        $this->view->blogError = "Added Successfully !!";
                    }
                    // echo $this->view->success;
                }
            }
        }
    }
    public function adduserAction()
    {
        if (!$this->session->get('activeUser') || $this->session->get('activeRole') !== "admin") {
            header('location:/');
        } else {
            $user = new Users();
            if ($this->request->getPost()) {
                $this->view->users = Users::find();
                $user->assign(
                    $postdata = $this->request->getPost(),
                    [
                        'full_name',
                        'username',
                        'email',
                        'password',

                    ]
                );
                $user->confirm_password = $postdata['password'];
                $user->role = 'user';
                $user->status = 'pending';
                $success = $user->save();
                $this->view->success = $success;


                if ($success) {
                    $this->view->message = "User Added Successfully !!";
                } else {
                    $this->view->message = "Not Added succesfully due to following reasons: <br>" . implode("<br>", $user->getMessages());
                }
            }
        }
    }
    public function readmoreAction($blog_id)
    {
        if (!$this->session->get('activeUser') || $this->session->get('activeRole') !== "admin") {
            header('location:/');
        } else {
            $blog = new Blog();
            $this->view->blog_id = $blog_id;
            $this->view->blog = Blog::find();
        }
    }
    public function editblogAction($blog_id)
    {
        if (!$this->session->get('activeUser') && $this->session->get('activeRole') !== "admin") {
            header('location:/');
        } else {
            // echo $blog_id; die;
            $blog = new Blog();
            $this->view->blog_id = $blog_id;
            $blog = Blog::find();
            $this->view->blog = Blog::find();
            if ($this->request->getPost("saveEditedBlog")) {

                foreach ($blog as $k) {
                    // die('reached');

                    if ($k->blog_id == $blog_id) {
                        $newTitle = $this->request->getPost("newtitle");
                        $newContent = $this->request->getPost("newcontent");
                        if (empty($newContent) && empty($newTitle)) {
                            $this->view->saveMsg = '*Both fields are required';
                        } elseif (empty($newTitle)) {
                            $this->view->saveMsg = '*Title is required';
                        } elseif (empty($newContent)) {
                            $this->view->saveMsg = '*Content is required';
                        } else {
                            $k->title = $newTitle;
                            $k->content = $newContent;
                            $success = $this->view->saveMsg = $k->save();
                            $this->view->success = $success;
                            if ($success) {
                                $this->view->saveMsg = "Saved Successfully !!" . "<br>" . "Please refresh your page to view Changes.";
                            } else {
                                $this->view->saveMsg = "Some error occured while saving your blog !!";
                            }
                            // echo $this->view->success;
                        }
                    }
                }
            }
        }
    }
    public function logoutAction()
    {
        $this->session->destroy();
        $this->response->redirect("/");
    }
}
