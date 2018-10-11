<?php

use Main\Controllers\Article\StudentController;
use Main\Controllers\Article\CommentController;
use Main\Controllers\Article\FavoriteController;
use Main\Controllers\Auth\LoginController;
use Main\Controllers\Auth\RegisterController;
use Main\Controllers\ProfileController;
use Main\Controllers\Admin\AdminController;
use Main\Controllers\User\UserController;
use Main\Middleware\OptionalAuth;
use Main\Models\Tag;
use Slim\Http\Request;
use Slim\Http\Response;


// Api Routes
$app->group('/api',
    function () {
        $jwtMiddleware = $this->getContainer()->get('jwt');
        $optionalAuth = $this->getContainer()->get('optionalAuth');
        /** @var \Slim\App $this */

        // Auth Routes
        $this->post('/admin', RegisterController::class . ':register')->setName('auth.register');
        $this->post('/enroll', RegisterController::class . ':enroll')->setName('auth.enroll');
        $this->post('/admin/login', LoginController::class . ':login')->setName('auth.login');
       
        // The General Users Routes (Admin, Staff, Student and Parent)
        $this->get('/admin', UserController::class . ':show')->setName('user.show');
        $this->get('/allAdmin', UserController::class . ':showAll')->setName('user.showAll');
        $this->put('/admin', UserController::class . ':update')->setName('user.update');

        // Admin Profile Update Routes
        $this->get('/profiles/{username}', ProfileController::class . ':showSingle')
            ->add($optionalAuth)
            ->setName('profile.showSingle');
        $this->get('/profiles', ProfileController::class . ':showAllAdmin')
            ->add($optionalAuth)
            ->setName('profile.showAllAdmin');
        $this->get('/administrator', ProfileController::class . ':showAllAdministrator')
            ->add($optionalAuth)
            ->setName('profile.showAllAdministrator');


        // Students Routes
        $this->get('/students/feed', StudentController::class . ':index')->add($optionalAuth)->setName('students.index');
        $this->get('/students/{class}', StudentController::class . ':show')->add($optionalAuth)->setName('students.show');
        $this->put('/students/{class}',
            StudentController::class . ':update')->add($jwtMiddleware)->setName('students.update');
        $this->delete('/students/{class}',
            StudentController::class . ':destroy')->add($jwtMiddleware)->setName('students.destroy');
        $this->post('/students', StudentController::class . ':store')->add($jwtMiddleware)->setName('students.store');
        $this->get('/students', StudentController::class . ':index')->add($optionalAuth)->setName('students.index');

        // Staff Routes
        // $this->get('/students/{class}/comments',
        //     CommentController::class . ':index')
        //     ->add($optionalAuth)
        //     ->setName('comment.index');
        // $this->post('/students/{class}/comments',
        //     CommentController::class . ':store')
        //     ->add($jwtMiddleware)
        //     ->setName('comment.store');
        // $this->delete('/students/{class}/comments/{id}',
        //     CommentController::class . ':destroy')
        //     ->add($jwtMiddleware)
        //     ->setName('comment.destroy');

        // Parents Routes
        // $this->post('/students/{class}/favorite',
        //     FavoriteController::class . ':store')
        //     ->add($jwtMiddleware)
        //     ->setName('favorite.store');

        // $this->delete('/students/{class}/favorite',
        //     FavoriteController::class . ':destroy')
        //     ->add($jwtMiddleware)
        //     ->setName('favorite.destroy');

        // Tags Route
        $this->get('/tags', function (Request $request, Response $response) {
            return $response->withJson([
                'tags' => Tag::all('title')->pluck('title'),
            ]);
        });
    });


// Routes

$app->get('/[{name}]',
    function (Request $request, Response $response, array $args) {
        // Sample log message
        $this->logger->info("Slim-Skeleton '/' route");

        // Render index view
        return $this->renderer->render($response, 'index.phtml', $args);
    });
