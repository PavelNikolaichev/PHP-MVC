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