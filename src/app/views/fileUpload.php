<?php
/* @var array $data */
?>
<h1>File upload</h1>

<form action="/file-upload" method="post" enctype="multipart/form-data" id="fileForm">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <button class="btn btn-outline-secondary" type="submit" id="inputGroupFileAddon03">Upload</button>
        </div>
        <div class="custom-file">
            <input type="file" class="custom-file-input" aria-describedby="inputGroupFileAddon03" id="attachedFile" name="file">
            <label class="custom-file-label" for="inputGroupFile03" id="attachedFileLabel">Choose file</label>
        </div>
    </div>
</form>

<?php if (isset($data['files'])): ?>
    <table class="table table-striped table-hover" id="files">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Size</th>
            <th scope="col">Name</th>
            <th scope="col">Meta</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['files'] as $file): ?>
            <tr>
                <th scope="row"><?= $file['size'] ?></th>
                <th><?= $file['name'] ?></th>
                <th><?= $file['meta'] ?></th>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No files found</p>
<?php endif ?>