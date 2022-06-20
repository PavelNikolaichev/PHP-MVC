<?php
/* @var array $data */
?>
<h1>User List</h1>

<?php if ($data): ?>
    <!-- TODO: Clickable users  -->
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Gender</th>
                <th scope="col">Status</th>
                <th scope="col">Link</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $user): ?>
                <tr>
                    <th scope="row"><?= $user['id'] ?></th>
                    <th><?= $user['name'] ?></th>
                    <th><?= $user['email'] ?></th>
                    <th><?= $user['gender'] ?></th>
                    <th><?= $user['status'] ?></th>
                    <th><a href="/user?id=<?= $user['id'] ?>" class="btn btn-primary">Change</a></th>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No users found</p>
<?php endif ?>