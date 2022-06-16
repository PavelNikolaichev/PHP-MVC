<?php
/* @var array $data */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
</head>
<body class="d-flex flex-column h-100">
    <?php require_once '../app/views/components/header.php'; ?>

    <main role="main" class="flex-shrink-0">
        <div class="container" style="padding: 60px 15px 0;">
            <h1>User</h1>
            <?php if ($data): ?>
                <!-- TODO: User edit form -->
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Status</th>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row"><?= $data['id'] ?></th>
                        <th><?= $data['name'] ?></th>
                        <th><?= $data['email'] ?></th>
                        <th><?= $data['gender'] ?></th>
                        <th><?= $data['status'] ?></th>
                    </tr>
                    </tbody>
                </table>
            <?php else: ?>
                <p>User wasn't found</p>
            <?php endif ?>
        </div>
    </main>

    <?php require_once '../app/views/components/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
</body>
</html>