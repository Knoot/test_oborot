<?php

require_once('Models/Client.php');
require_once('functions.php');

use Models\Client;

$pdo = getConnection();
$errors = [];
$client = new Client($_POST['name'], $_POST['surname'], $_POST['phone'], $_POST['comment']);

if (!empty($_POST)) {
    $errors = $client->validate();
}
$valid = empty($errors);

if ($valid && !empty($_POST)) {
    $q = $pdo->prepare("INSERT INTO clients (name, surname, phone, comment) VALUES (?, ?, ?, ?)");
    $q->execute([
        mb_strtolower($_POST['name']),
        mb_strtolower($_POST['surname']),
        $_POST['phone'],
        $_POST['comment'],
    ]);
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style>
        .clients {
            table-layout: fixed;
        }
        .clients td, .clients pre {
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

    <title></title>
</head>

<body>
    <div class="container">
        <?php if (!empty($errors)) {?>
        <div id="errors">
            <?php foreach ($errors as $error) {?>
            <div class="alert alert-warning" role="alert">
                <?=$error?>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
        <form action="index.php" method="post">
            <div class="input-group">
                <span class="input-group-text">First and last name</span>
                <input type="text" aria-label="First name" class="form-control" name="name"
                    value="<?=!$valid ? $client->getName() : ''?>">
                <input type="text" aria-label="Last name" class="form-control" name="surname"
                    value="<?=!$valid ? $client->getSurname() : ''?>">
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="inputGroup-sizing-default">Phone</span>
                <!-- input type=number, но для проверки валидации type=text -->
                <input type="text" class="form-control" name="phone" value="<?=!$valid ? $client->getPhone() : ''?>">
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label">Comment</label>
                <textarea id="comment" class="form-control" name="comment"
                    rows="3"><?=!$valid ? $client->getComment() : ''?></textarea>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Add</button>
            </div>
        </form>

    </div>
    <div class="container">
        <table class="table clients">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Surname</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Comment</th>
                    <th scope="col">Created at</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pdo->query("SELECT * FROM clients ORDER BY name ASC, surname ASC") as $client) {
                    $client = new Client(
                        $client['name'],
                        $client['surname'],
                        $client['phone'],
                        $client['comment'],
                        $client['created_at']
                    );
                    ?>
                    <tr>
                        <td><?=$client->getName()?></td>
                        <td><?=$client->getSurname()?></td>
                        <td><?=$client->getPhone()?></td>
                        <td><?=$client->getComment()?></td>
                        <td><?=$client->getCreatedAt()?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>
