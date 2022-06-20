<?php
/* @var array $data */
?>

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
    <form>
        <div class="form-group row">
            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="inputEmail" placeholder="<?= $data['email'] ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="inputName" placeholder="<?= $data['name'] ?>">
            </div>
        </div>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <label class="input-group-text" for="inputGroupSelect01">Options</label>
            </div>
            <select class="custom-select" id="inputGroupSelect01">
                <option selected>Choose...</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
        </div>
        <div class="btn-group">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="genderDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Gender
                </button>
                <div class="dropdown-menu" aria-labelledby="genderDropdown">
                    <a class="dropdown-item active" href="#">Male</a>
                    <a class="dropdown-item" href="#">Female</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="statusDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Status
                </button>
                <div class="dropdown-menu" aria-labelledby="statusDropdown">
                    <a class="dropdown-item" href="#">Active</a>
                    <a class="dropdown-item active" href="#">Inactive</a>
                </div>
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