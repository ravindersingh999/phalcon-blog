<?php

use Phalcon\Mvc\Controller;


class UserController extends Controller
{
    public function indexAction()
    {
        if (!$this->session->get('auth')) {
            $this->response->redirect('login');
        } elseif ($this->session->get('auth')['role'] == "admin") {
            $this->response->redirect('admin');
        }
        $this->view->blogs = Blogs::find();
    }

    public function addblogAction()
    {
        if ($this->session->get('auth')['role'] == "admin") {
            $this->response->redirect('admin');
        } else {
            $title = $this->request->getPost('title');
            $content = $this->request->getPost('content');
            $blog = new Blogs();
            if ($this->request->getPost()) {
                if (empty($title) || empty($content)) {
                    $this->view->addmsg = "*Please fill all details";
                } else {
                    $blog->assign(
                        $this->request->getPost(),
                        [
                            'title',
                            'content',
                        ]
                    );
                    $blog->user_id = $this->session->get('auth')['id'];
                    // print_r(json_encode($blog));
                    // die();
                    $blog->save();
                }
            }
        }
    }

    public function editblogAction()
    {
        if ($this->session->get('auth')['role'] == "admin") {
            $this->response->redirect('admin');
        } else {
            $id = $this->request->getPost('blogid');
            // print_r(json_encode($id));
            $blog = Blogs::findFirst(
                [
                    "id = '$id'"
                ]
            );
            // print_r(json_encode($blog->title));
            // die();
            if ($this->request->getPost('delete')) {
                $blog->delete();
            }

            $this->view->blog = $blog;
        }
    }

    public function updateAction()
    {
        $blog = new Blogs();
        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');
        $id = $this->request->getPost('id');

        // $blog = Blogs::findFirst(array(
        //     'title = :title: and content = :content:', 'bind' => array(
        //         'title' => $this->request->getPost("title"),
        //         'content' => $this->request->getPost("content")
        //     )
        // ));

        $blog = Blogs::findFirst(
            [
                "id = '$id'"
            ]
        );
        // print_r(json_encode($blog->content));
        // die();
        if ($this->request->isPost()) {
            $blog->title = $title;
            $blog->content = $content;
            $blog->save();
        }
        $this->response->redirect('/user');
    }
    public function deleteAction()
    {
        $id = $this->request->getPost('blogid');
        // print_r(json_encode($id));
        $blog = Blogs::findFirst(
            [
                "id = '$id'"
            ]
        );
        // print_r(json_encode($blog));
        // die();
        if ($this->request->getPost('dltBlog')) {
            $blog->delete();
        }
        $this->response->redirect('/user');
    }

    public function logoutAction()
    {
        // print_r($this->session->get('auth'));
        $this->session->remove('auth');
        unset($this->session->auth);
        // echo "after";
        // print_r($this->session->get('auth'));

        $this->response->redirect('login');
    }
}
