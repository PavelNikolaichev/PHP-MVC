function userTableUpdate(user) {
    let row = '<tr>';

    row += '<th scope="row" id="user-' + user['id'] + '">' + user['id'] + '</th>' +
        '<th>' + user['name'] + '</th>' +
        '<th>' + user['email'] + '</th>' +
        '<th>' + user['gender'] + '</th>' +
        '<th>' + user['status'] + '</th>' +
        '<th><a href="/user?id=' + user['id'] + '" class="btn btn-primary">Edit</a></th>' +
        '<th><form action="/delete?id=' + user['id'] + '" method="post" class="form-delete">' +
        '<input type="hidden" name="id" value="' + user['id'] + '">' +
        '<button type="submit" name="delete" class="btn btn-danger">Delete</button></form></th>'

    row += '</tr>';

    $('#users').append(row).on('submit', '.form-delete', function (event) {
        event.preventDefault();

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (result) {
                let res = JSON.parse(result);

                if (res['message']) {
                    alert(res['message']);
                }

                let id = res['deleted_id'];
                tableDelete(id);
            }
        })
    });
}

function tableDelete(id) {
    $('#user-' + id).parent().remove();
}

function validateSize(file) {
    if (file.files[0].size > 1000000) {
        alert('File size must be less than 1MB');
        return false;
    }

    return true;
}

function updateFileTable(file) {
    let row = '<tr>';

    row += '<th scope="row">' + file['size'] + '</th>' +
        '<th>' + file['name'] + '</th>' +
        '<th>' + file['meta'] + '</th>';

    row += '</tr>';

    $('#files').append(row);
}

$(document).ready(function () {
    $('#userForm').submit(function (event) {
        event.preventDefault();

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (result) {
                let res = JSON.parse(result);

                if (res['message']) {
                    alert(res['message']);
                }

                let errors = Object.entries(res['errors']);

                if (errors.length > 0) {
                    errors.forEach(element => {
                        [name, value] = element;
                        $('#invalid-' + name).text(value);

                        name = name[0].toUpperCase() + name.slice(1);
                        $('#input' + name).addClass("is-invalid");
                    })
                } else {
                    let users = res['user'];
                    userTableUpdate(users);
                }
            }
        })
    });

    $('.form-delete').submit(function (event) {
        event.preventDefault();
        if (confirm("Are you sure you want to delete this user?")) {
            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (result) {
                    let res = JSON.parse(result);

                    if (res['message']) {
                        alert(res['message']);
                    }

                    let id = res['deleted_id'];

                    tableDelete(id);
                }
            })
        }
    });

    $('#attachedFile').change(function (event) {
        if (!validateSize(this)) {
            alert('File size must be less than 1MB');
            $('#attachedFile').val(null);

            $('#attachedFileLabel').text('Choose file');
        } else {
            let file = event.target.files[0].name;
            $('#attachedFileLabel').text(file);
        }
    });

    $('#fileForm').submit(function (event) {
        event.preventDefault();

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (result) {
                let res = JSON.parse(result);

                if (res['message']) {
                    alert(res['message']);
                }
                console.log(res['file'])

                updateFileTable(res['file']);
                $('#attachedFile').val(null);
                $('#attachedFileLabel').text('Choose file');
            }
        })
    });
})