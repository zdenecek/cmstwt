<?php

namespace App\Controller;

use App\App;
use App\View;
use App\Service\ArticleService;

class ArticleController
{
    private \PDO $database;
    private ArticleService $service;

    public function __construct(\PDO $database)
    {
        $this->database = $database;
        $this->service = new  ArticleService($database);
    }

    public function showDetail($request, $response, $id) {



        if(!$this->validateId($id)) return $response->notFound();
        
        $article = $this->service->getOne($id);

        if(!$article) return $response->notFound();

        $response->view(View::create('article-detail', [
            'article' => $article
        ]));
    }

    public function showAll($request, $response ) {
        
        $articles = $this->service->getAll();

        // App::debug($articles);

        $response->view(View::create('article-list', [
            'articles' => $articles
        ]));
    }

    public function showUpdate($request, $response, $id) {

        if(!$this->validateId($id)) return $response->notFound();
        
        $article = $this->service->getOne($id);

        if(!$article) return $response->notFound();

        $response->view(View::create('article-edit', [
            'article' => $article
        ]));
    }

    private function validateName($name): bool {
        return strlen($name) > 0 && strlen($name) <= 32;
    }

    private function validateContent($content): bool {
        return true;
    }


    public function update($request, $response, $id) {

        if (!$this->validateId($id)) {
            $response->notFound();
        }

        $article = $this->service->getOne($id);

        if(!$article) {
            $response->notFound();
        }

        $errors = [];

        if(array_key_exists("name", $request->data)) {
            if($this->validateName($request->data['name'])) {
                $article->name = $request->data['name'];
            }
            else {
                $errors['name'] = "Invalid name";
            }
        }

        if(array_key_exists("content", $request->data)) {
            if($this->validateContent($request->data['content'])) {
                $article->content = $request->data['content'];
            }
            else {
                $errors['content'] = "Invalid content";
            }
        }

        if(count($errors) > 0) {
            $response->redirect($request->url)->with(
                [
                    'errors' => $errors,
                ]
            );
            return;
        }

        $this->service->updateOne($article);

        $response->redirectHard('articles');
    }

    public function delete($request, $response, $id) {
        if(!$this->validateId($id)) return $response->code(400);

        $res = $this->service->deleteOne($id);

        $response->code($res ? 200 : 404);
    }

    public function create($request, $response) {
        if (!array_key_exists('name', $request->data) || !$this->validateName($request->data['name']) ) {
            $response->redirectHard('articles')
                    ->code(400)
                    ->with([
                        'error' => 'Invalid name',
                    ]);
            return;
        } 

        $article = $this->service->createOne($_POST['name']);

        $response->redirectHard('article-edit/' . $article->id);

    }

    private function validateId(&$id) {

        if (!is_numeric($id)) {
            return false;
        }
        $id = (int) $id;
        return true;
    }
}