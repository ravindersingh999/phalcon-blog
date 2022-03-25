<?php

use Phalcon\Mvc\Controller;


class DashboardController extends Controller
{
    public function indexAction()
    {
        $this->view->users = Users::find();
    }

    public function statusAction() {
        $users = Users::find();
        if ($this->request->isPost('change')) {
            $id = $this->request->getPost('id');
            
            // echo $id;
            foreach($users as $k) {
                // $k = json_encode($k);
                // print_r(json_decode($k));
                if ($k->id == $id) {
                    if($k->status == "restricted" ) {
                        $k->status = "approved";
                        $k->save();
                        $this->response->redirect("dashboard");
                    } else {
                        $k->status = "restricted";
                        $k->save();
                        $this->response->redirect("dashboard");
                    }
                }
            }
        }
        if($this->request->isPost('delete')) {
            $id = $this->request->getPost('id');

            foreach($users as $k) {
                if($k->id == $id) {
                    $k->delete();
                    $this->response->redirect('dashboard');
                }
            }
        }
    }
}