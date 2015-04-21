<?php

namespace Sigec\controller;

use Sigec\view\User as View;

class UserController extends ControllerBase
{
    private $view = null;

    public function __construct()
    {
        parent::__construct();
        $this->view = new View();
        $this->view->assign('action', '');
        $this->view->assign('username', $this->user->getName());
        $this->user->setPdo(new \PDO(DB_DSN, DB_USER, DB_PASS));
    }

    public function listAll()
    {
        $this->view->assign('title', 'Listagem');
        $this->view->assign('users', $this->user->fetchAll());
        $this->view->generateHTML();
    }

    public function add($errors = array(), $post = null)
    {
        $this->view->assign('errors', $errors);
        $this->view->assign('post', $post);
        $this->view->assign('action', 'add');
        $this->view->assign('title', 'Cadastrar');
        $this->view->generateHTML();

    }

    public function update()
    {
        $this->view->assign('action', 'update');
        $this->view->assign('title', 'Editar');
        $this->view->generateHTML();

    }

    public function delete()
    {

        $this->listAll();
    }

    public function save()
    {
        $errors = Array();
        $post = (object) $_POST;

        if ($post->password != $post->rpassword) {
            $errors[] = "Password don't match";
        }
        try {
            $this->user->setName($post->name);
            $this->user->setLogin($post->login);
            $this->user->setPassword($post->password);
            $this->user->setProfile($post->profile);
            $this->user->save();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $errors[] = 'Nao foi possivel salvar o usuario';
        }

        if (count($errors) > 0) {
            $this->add($errors, $post);
        } else {
            $this->listAll();
        }
    }

}

