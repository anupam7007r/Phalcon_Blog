<?php

use Phalcon\Mvc\Controller;
use Phalcon\Escaper;

class SignupController extends Controller
{
    public function indexAction()
    {
        $escaper = new Escaper();
        $user = new Users();
        $data = $this->request->getPost();
        $myescaper = new \App\Components\myescaper;
        $santitizedata = $myescaper->sanitize($data);
        // $user->assign(
        //     $santitizedata,
        //     [

        //         'full_name',
        //         'email',
        //         'username',
        //         'password',
        //         'confirm_password'
        //     ]
        // );
        // if ($this->request->getPost()) {
        //     $this->view->users = Users::find();
        //     $postdata=array(
        //         'full_name'=>$escaper->escapehtml($this->request->getPost('full_name')),
        //         'username'=>$escaper->escapehtml($this->request->getPost('username')),
        //         'email'=>$escaper->escapehtml($this->request->getPost('email')),
        //         'password'=>$escaper->escapehtml($this->request->getPost('password')),
        //         'confirm_password'=>$escaper->escapehtml($this->request->getPost('confirm_password')),

        //     );
        if ($this->request->getPost()) {
            $user->assign(
                $santitizedata,
                [
                    'full_name',
                    'username',
                    'email',
                    'password',
                    'confirm_password',

                ]
            );
            $user->role = 'user';
            $user->status = 'pending';
            // function emailExists($email)
            // {
            //     $user = new Users();
            //     foreach ($user as $k) {
            //         if ($k->email == $email) {
            //             return true;
            //         } else {
            //             return false;
            //         }
            //     }
            // }
            // $emailExists = emailExists($postdata->email);
            // $emailExists = $this->view->users = Users::find($postdata['email']);
            if ($santitizedata['password'] != $santitizedata['confirm_password']) {
                $this->view->passwordError = "Passwords don't match" . "<br>";
            }
            // foreach($user as $k){
            //     if($k->email == $postdata['email']){
            //         $this->view->emailExists = "<br>"."Email already exists"."<br>";
            //     }

            //     }

            // if ($emailExists) {
            //     $this->view->emailExistsError = "*Email already Registered !! Try with a different email !!";
            // } 
            else {
                $success = $user->save();
                $this->view->success = $success;
            }
            if ($success) {
                $this->view->message = "Register succesfully";
            } else {
                $this->view->message = "Not Register succesfully due to following reason: <br>" . implode("<br>", $user->getMessages());
            }
        }
    }
}
