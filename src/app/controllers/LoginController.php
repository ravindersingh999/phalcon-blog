<?php

use Phalcon\Mvc\Controller;

// Session
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;

class LoginController extends Controller
{
    public function indexAction()
    {
        //return '<h1>Hello!!!</h1>';
    }

    public function signinAction()
    {
        $session = new Manager();
        $user = new Users();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if ($this->request->getPost()) {
            if (empty($email) || empty($password)) {
                $this->session->set('loginmsg', '*Fill all Details');
                $this->response->redirect('login');
            } else {
                $user = Users::findFirst(array(
                    'email = :email: and password = :password:', 'bind' => array(
                        'email' => $this->request->getPost("email"),
                        'password' => $this->request->getPost("password")
                    )
                ));
                // print_r(json_encode($user->id));
                // die();

                if (!$user) {
                    $this->session->set('loginmsg', '*Wrong all Credentials');
                    $this->response->redirect('login');
                }
                if ($user->role == "admin") {
                    // $this->session->set('loginmsg', '*Login request not approved yet');
                    $this->response->redirect('admin');
                    $this->session->set('auth', array(
                        'id' => $user->id,
                        'name' => $user->name,
                        'role' => $user->role,
                    ));
                    // $this->response->redirect('login');
                }
                if ($user->role == "user") {
                    if ($user->status == "restricted") {
                        $this->session->set('loginmsg', '*Login request not approved yet');
                        $this->response->redirect('login');
                    } else {
                        $this->response->redirect('user');
                        $this->session->set('auth', array(
                            'id' => $user->id,
                        ));
                    }
                }
            }
        }
    }
}
