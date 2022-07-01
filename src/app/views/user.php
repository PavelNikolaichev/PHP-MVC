<?php
/* @var array $data */
?>

<h1>User</h1>
<?php if ($data['user']): ?>
    <form action="/user?id=<?= $data['user']->id ?>" method="post" id="userForm">
        <div class="form-group row">
            <label for="inputEmail" class="col-sm-1 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control <?= isset($data['errors']['email']) ? 'is-invalid' : '' ?>" id="inputEmail" name="email" value="<?= htmlspecialchars($data['user']->email) ?>" autocomplete="off">
                <div class="invalid-feedback" id="invalid-email">
                    <?= $data['errors']['email'] ?? '' ?>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="inputName" class="col-sm-1 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control <?= isset($data['errors']['name']) ? 'is-invalid' : '' ?>" id="inputName" name="name" value="<?= htmlspecialchars($data['user']->name) ?>" autocomplete="off">
                <div class="invalid-feedback" id="invalid-name">
                    <?= $data['errors']['name'] ?? '' ?>
                </div>
            </div>
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <label class="input-group-text" for="inputGender">Gender</label>
            </div>
            <select class="custom-select <?= isset($data['errors']['gender']) ? 'is-invalid' : '' ?>" id="inputGender" name="gender">
                <option value="male" <?= $data['user']->gender === 'male' ? 'selected' : '' ?> >Male</option>
                <option value="female" <?= $data['user']->gender === 'female' ? 'selected' : '' ?> >Female</option>
            </select>
            <div class="invalid-feedback" id="invalid-gender">
                <?= $data['errors']['gender'] ?? '' ?>
            </div>
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <label class="input-group-text" for="inputStatus">Status</label>
            </div>
            <select class="custom-select <?= isset($data['errors']['status']) ? 'is-invalid' : '' ?>" id="inputStatus" name="status">
                <option value="active" <?= $data['user']->gender === 'active' ? 'selected' : '' ?> >Active</option>
                <option value="inactive" <?= $data['user']->gender === 'inactive' ? 'selected' : '' ?> >Inactive</option>
            </select>
            <div class="invalid-feedback" id="invalid-status">
                <?= $data['errors']['status'] ?? '' ?>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Edit</button>
            </div>
        </div>
    </form>
<?php else: ?>
    <p>User wasn't found</p>
<?php endif ?>