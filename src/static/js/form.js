function tableUpdate(users) {
    $('#users tbody').empty();

    if (users.length > 0) {
        users.forEach(user => {
            let row = '<tr>';

            row += '<th scope="row">' + user['id'] + '</th>' +
                '<th>' + user['name'] + '</th>' +
                '<th>' + user['email'] + '</th>' +
                '<th>' + user['gender'] + '</th>' +
                '<th>' + user['status'] + '</th>' +
                '<th><a href="/user?id=' + user['id'] + '" class="btn btn-primary">Edit</a></th>' +
                '<th><form action="/delete?id=' + user['id'] + '" method="post">' +
                '<input type="hidden" name="id" value="' + user['id'] + '">' +
                '<button type="submit" name="delete" class="btn btn-danger">Delete</button></form></th>'

            row += '</tr>';

            $('#users').append(row);
        })
    }
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
                let errors = Object.entries(res['errors']);

                if (errors.length > 0) {
                    errors.forEach(element => {
                        [name, value] = element;
                        $('#invalid-' + name).text(value);

                        name = name[0].toUpperCase() + name.slice(1);
                        $('#input' + name).addClass("is-invalid");
                    })
                }

                let users = res['users'];
                tableUpdate(users);
            }
        })
    })

    $('.form-delete').submit(function (event) {
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

                let users = res['users'];
                tableUpdate(users);
            }
        })
    })
})