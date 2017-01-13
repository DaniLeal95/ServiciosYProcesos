<?php
/**
 * Created by PhpStorm.
 * User: dleal
 * Date: 13/01/17
 * Time: 8:34
 */

//importamos las clases

//Autoload, carga automaticamente las clases
require_once "../vendor/autoload.php";
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


$app= new Silex\Application();

/*PRUEBAS*/
$app -> get('/hello/{name}',function ($name) use ($app){
   return 'Hello '.$app->escape($name);

});



$blogPosts = array(
    1 => array(
        'date'   => '2011-03-29',
        'author' => 'igorw',
        'title'  => 'Using Silex',
        'body'   => '...',
    ),
);

$app->get('/blog', function () use ($blogPosts) {
    $output = '';
    $variable=$blogPosts[1];
    $output .= $variable['title'];
    $output .= '<br />';


    return $output;
});



$app->get('/blog/show/{id}', function (Silex\Application $app, $id) use ($blogPosts) {
    if (!isset($blogPosts[$id])) {
        $app->abort(404, "El artículo $id no existe.");
    }

    $post = $blogPosts[$id];

    return  "<h1>{$post['title']}</h1>".
    "<p>{$post['body']}</p>";
});



//ESTO NO VA
$app->post('/correo', function (Request $request) {
    $message = $request->get('message');

    $to = "dgarosto@gmail.com";
    $subject = "My subject";
    $txt = "Hello world!";
    $headers = "From: dlealreyes@gmail.com"."\r\n".
    "CC: somebodyelse@example.com";

    mail($to, $subject, $txt,$headers);


    return new Response('Gracias por tus comentarios', 201);
});

/*FIN PRUEBAS*/

$app->run();

