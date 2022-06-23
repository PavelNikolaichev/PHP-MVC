<?php
/* @var array $data */
?>
<h1>User List</h1>

<form action="/user-list" method="post" id="form">
    <div class="form-group row">
        <label for="inputEmail" class="col-sm-1 col-form-label">Email</label>
        <div class="col-sm-10">
            <input type="email" class="form-control <?= isset($data['errors']['email']) ? 'is-invalid' : '' ?>" id="inputEmail" name="email" placeholder="example@gmail.com">
            <div class="invalid-feedback">
                <?= $data['errors']['email'] ?? '' ?>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="inputName" class="col-sm-1 col-form-label">Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control <?= isset($data['errors']['name']) ? 'is-invalid' : '' ?>" id="inputName" name="name" placeholder="Your name">
            <div class="invalid-feedback">
                <?= $data['errors']['name'] ?? '' ?>
            </div>
        </div>
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="inputGender">Gender</label>
        </div>
        <select class="custom-select <?= isset($data['errors']['gender']) ? 'is-invalid' : '' ?>" id="inputGender" name="gender">
            <option value="Male" selected>Male</option>
            <option value="Female">Female</option>
        </select>
        <div class="invalid-feedback">
            <?= $data['errors']['gender'] ?? '' ?>
        </div>
    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <label class="input-group-text" for="inputStatus">Status</label>
        </div>
        <select class="custom-select <?= isset($data['errors']['status']) ? 'is-invalid' : '' ?>" id="inputStatus" name="status">
            <option value="Active" selected>Active</option>
            <option value="Inactive">Inactive</option>
        </select>
        <div class="invalid-feedback">
            <?= $data['errors']['status'] ?? '' ?>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-10">
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </div>
</form>

<?php if ($data['users']): ?>
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
            <?php foreach ($data['users'] as $user): ?>
                <tr>
                    <th scope="row"><?= $user->id ?></th>
                    <th><?= $user->name ?></th>
                    <th><?= $user->email ?></th>
                    <th><?= $user->gender ?></th>
                    <th><?= $user->status ?></th>
                    <th><a href="/user?id=<?= $user->id ?>" class="btn btn-primary">Change</a></th>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No users found</p>
<?php endif ?>