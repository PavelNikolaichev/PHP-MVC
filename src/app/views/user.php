<?php
/* @var array $data */
?>

<h1>User</h1>
<?php if ($data['user']): ?>
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
            <th scope="row"><?= $data['user']->id ?></th>
            <th><?= $data['user']->name ?></th>
            <th><?= $data['user']->email ?></th>
            <th><?= $data['user']->gender ?></th>
            <th><?= $data['user']->status ?></th>
        </tr>
        </tbody>
    </table>
    <form action="/user?id=<?= $data['user']->id ?>" method="post">
        <div class="form-group row">
            <label for="inputEmail" class="col-sm-1 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control <?= isset($data['errors']['email']) ? 'is-invalid' : '' ?>" id="inputEmail" name="email" value="<?= htmlspecialchars($data['user']->email) ?>">
                <div class="invalid-feedback">
                    <?= $data['errors']['email'] ?? '' ?>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="inputName" class="col-sm-1 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control <?= isset($data['errors']['name']) ? 'is-invalid' : '' ?>" id="inputName" name="name" value="<?= htmlspecialchars($data['user']->name) ?>">
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
                <option value="Male" <?= $data['user']->gender === 'Male' ? 'selected' : '' ?> >Male</option>
                <option value="Female" <?= $data['user']->gender === 'Female' ? 'selected' : '' ?> >Female</option>
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
                <option value="Active" <?= $data['user']->gender === 'Active' ? 'selected' : '' ?> >Active</option>
                <option value="Inactive" <?= $data['user']->gender === 'Inactive' ? 'selected' : '' ?> >Inactive</option>
            </select>
            <div class="invalid-feedback">
                <?= $data['errors']['status'] ?? '' ?>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Sign in</button>
            </div>
        </div>
    </form>
<?php else: ?>
    <p>User wasn't found</p>
<?php endif ?>