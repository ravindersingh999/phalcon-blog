<?php

use Phalcon\Mvc\Controller;

class SignupController extends Controller
{

    public function IndexAction()
    {
        if ($this->request->isPost()) {
            $user = new Users();
            $userr = Users::findFirst(array(
                'email = :email:', 'bind' => array(
                    'email' => $this->request->getPost("email"),
                )
            ));
            $userr = json_decode(json_encode($userr));

            if ($userr->email == $this->request->getPost('email') && !empty($this->request->getPost('email'))) {
                $this->session->set("signupmsg", "*Email already exists");
                $this->response->redirect('signup');
            } else {
                $user->assign(
                    $this->request->getPost(),
                    [
                        'name',
                        'email',
                        'password',
                    ]
                );
                $user->role = "user";
                $user->status = "restricted";
                // print_r($user);
                // die();
                $success = $user->save();

                $this->view->success = $success;

                if ($success) {
                    $this->session->set("signupmsg", "Register succesfully");
                    $this->response->redirect('login');
                } else {
                    $this->session->set("signupmsg", "Not Register succesfully due to following reason: <br>" . implode("<br>", $user->getMessages()));
                }
            }
        }
    }
}
