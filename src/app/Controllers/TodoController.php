<?php

namespace App\Controllers;

use Core\Container;
use App\Middlewares\Auth;

class TodoController extends Controller
{
    use Auth;

    private $todo, $user;
    private $validator;

    public function __construct(Container $container, array $params)
    {
        $this->todo = $container->get('todo');
        $this->user = $container->get('user');
        $this->validator = $container->get('validator');

        // authentication middleware
        $this->auth();

        if (!empty($params)) {
            $this->permissionGuard($params['id'], $this->user->id());
        }
    }

    public function show($request, array $params)
    {
        if (!empty($params)) {
            $itemId = $params['id'];
            $todo = $this->todo->item($itemId);

            $this->render('todo-item', [
                'id' => $itemId,
                'pageTitle' => 'Edit Todo (' . $itemId . ')',
                'item' => $todo['item'],
                'done' => $todo['done']
            ]);
        }

        $this->render('todo', [
            'pageTitle' => 'Todo List',
            'todoItems' => $this->todo->all($this->user->id()),
        ]);
    }

    public function add($request)
    {
        $request['todo-input'] = trim($request['todo-input']);

        $errors = $this->validator->make($request, [
            'todo-input' => ['required'],
        ]);

        if (!empty($errors)) {
            $this->back([
                'errors' => $errors
            ]);
        }

        $this->todo->add($this->user->id(), $request['todo-input']);
        $this->back();
    }

    public function update($request, array $params)
    {
        $request['todo-input'] = trim($request['todo-input']);

        $errors = $this->validator->make($request, [
            'todo-input' => ['required'],
        ]);

        if (!empty($errors)) {
            $this->back([
                'errors' => $errors
            ]);
        }

        $this->todo->update($params['id'], $request['todo-input']);
        $this->back([
            'message' => 'Item has been updated.'
        ]);
    }

    public function done($request, array $params)
    {
        $this->todo->done($params['id']);
        $this->back();
    }

    public function undone($request, array $params)
    {
        $this->todo->undone($params['id']);
        $this->back();
    }

    public function delete($request, array $params)
    {
        $this->todo->delete($params['id']);
        $this->redirect('/');
    }
}