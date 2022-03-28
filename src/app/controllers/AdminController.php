<?php

use Phalcon\Mvc\Controller;


class AdminController extends Controller
{
    public function indexAction()
    {
        if (!$this->session->get('auth')) {
            $this->response->redirect('login');
        }
        $this->view->users = Users::find();
    }

    public function statusAction()
    {
        print_r($this->request->getPost());
        $id = $this->request->getPost('id');
        //$status = $this->request->getPost('status');
        $btn = $this->request->getPost('btn');
        //print_r($btn);
        //die();

        $user = Users::findFirst(
            [
                "id = '$id'"
            ]
        );
        //print_r(json_encode($user->email));
        if ($btn == "delete") {
            $user->delete();
        } elseif ($btn == "change") {
            if ($user->status == "restricted") {
                $user->status = "approved";
                $user->save();
            } else {
                $user->status = "restricted";
                $user->save();
            }
        }
        $this->response->redirect('admin');
    }

    public function addblogAction()
    {
        // print_r($this->request->getPost());
        // die();
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

    public function blogAction()
    {
        if (!$this->session->get('auth')) {
            $this->response->redirect('login');
        }
        $this->view->blogs = Blogs::find();
    }

    public function editblogAction()
    {
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
        $this->response->redirect('/admin/blog');
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
        $this->response->redirect('/admin/blog');
    }
    public function logoutAction()
    {
        // $this->session->remove('auth');
        // unset($this->session->auth);
        // print_r($this->session->get('auth'));
        $this->session->destroy();

        // echo "after";
        // print_r($this->session->get('auth'));

        $this->response->redirect('login');
    }
}
