<?php 
session_start();
require_once 'config.php';

if(!empty($_POST['email']) && !empty($_Post['password'])){

    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    $email = strtolower($email);

    // verifier si la personne est bien inscrite dans la base
    $check = $bdd->prepare('SELECT pseudo, email, password FROM utilisateurs WHERE email = ?');
    $check->execute(array($email));
    $data = $check->fetch();
    $row = $check->rowCount();

    //si > 0 alors l'utilisateur existe
    if($row > 0 ){
        // verifier le format du mail
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){

            //si le mot de pass est le bon
            if (password_verify($password, $data['password'])){

                // on crée la session et on redirige sur landing.php
                $_SESSION['user'] = $data['pseudo'];
                header('Location:landing.php');
                die();
            }else{
                header('Location:index.php?login_err=password');
                die();
            }
        }else {
            header('Location:index.php?login_err=email');
            die();
        }

    }else {
        header('Location:index.php?login_err=already');
        die();
    }

}else {
    header('Location:index.php');
    die();
}
?>