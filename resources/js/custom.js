function showAlert(message, type) {
    $('#alert_placeholder').append('<div id="alert" class="alert alert-'+type+' alert-dismissible fade show" role="alert">' +
        message +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
    '</button>' +
    '</div>');

    setTimeout(function() {
        $("#alert").remove();
    }, 3000);
}
$(document).ready(function () {
    $('.job-role-settings').on('submit', function () {
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: $(this).serialize(),
            url: $(this).prop('action'),
            success: function (response) {
                $('#jobrole-dropdown .jobrole-count').text(response.assigned_courses)
            }
        });

        return false;
    });

    $('#bulk-delete-form').on('submit', function() {
        if(!$(this).find('input:checked').length) {
            showAlert('<strong>Warning!</strong> You should check at least one item to be able to delete it.', 'warning');

            return false;
        }
    });

    $('.js-course-assign-employees').on('click', function() {
        $.ajax({
            type: 'get',
            url: $(this).data('url'),
            success:function (response) {
                $('#assign-employees').find('.loader').parent().html(response);
            }
        })
    });

    $('.js-archive-employees').on('click', function() {
        if(!$('.js-employee-id:checked').length) {
            alert('No employees selected');
            return false;
        }
        if(confirm('Do you really want to archive selected employees?')) {
            $('#js-employees-form').attr('action', $(this).data('href'))
                .submit();
        }
    });

    $('.js-import-employees').on('click', function() {
        $('#js-import-file').click();
    });

    $('#js-import-file').on('change', function() {
        if($(this).val().length) {
            $(this).closest('form').submit();
        }
    });

    $(document).on('click', '.js-employee-id', function() {
        if(!$('.js-employee-id:checked').length) {
            $('.btn-assign-show-popup').attr('data-target', '#assign-course-no-users-selected');
            $('.btn-markCompleted-show-popup').attr('data-target', '#markCompleted-course-no-users-selected');
        } else {
            $('.btn-assign-show-popup').attr('data-target', '#assign-course');
            $('.btn-markCompleted-show-popup').attr('data-target', '#markCompleted-course');
        }
    });

    $('#js-employees-markCompleted-course, #js-employees-assign-course').on('click', function() {
        if(!$('.js-employee-id:checked').length) {
            alert('No employees selected');
        }
        $.ajax({
            url: $(this).data('url'),
            data: $('#js-employees-form').serialize() + '&course_id=' + $(this).parents('.modal').find('.js-course-id').val(),
            type: 'post',
            dataType: 'json',
            success: function() {
                location.reload();
            }
        });
    });

    function suggestUsername(firstName, lastName) {
        var userName = firstName[0] + lastName;
        $('input[name=username]').val(userName);
    }

    $('.js-add-employee-form input[name=first_name]').on('change', function() {
        var shouldSuggestUsername = !$('input[name=username]').val().length && $(this).val().length
            && $('.js-add-employee-form input[name=last_name]').val().length;
        if(shouldSuggestUsername) {
            suggestUsername($(this).val(), $('.js-add-employee-form input[name=last_name]').val());
        }
    });

    $('.js-add-employee-form input[name=last_name]').on('change', function() {
        var shouldSuggestUsername = !$('input[name=username]').val().length && $(this).val().length
            && $('.js-add-employee-form input[name=first_name]').val().length;
        if(shouldSuggestUsername) {
            suggestUsername($('.js-add-employee-form input[name=first_name]').val(), $(this).val());
        }
    });

    $('.js-display-employee-courses').on('click', function() {
        var table = $('#course-history-table').DataTable().column(1);
        switch($(this).text()) {
            case 'Completed':
                table.search('Yes').draw();
                break;
            case 'Uncompleted':
                table.search('No').draw();
                break;
            default:
                table.search('').draw();
        }
    });

    $(document).on('click', '.js-bulk-action-employees', function () {
        if(!$('.js-employee-id:checked').length) {
            $('#assign-course-no-users-selected').modal('toggle');
            return  false;
        }
        $('#js-bulk-action-form')
            .attr('action', $(this).data('href'))
            .submit();
        return false;
    });

    let items = $(`a[href="${window.location}"].dropdown-item`);
    if (items.length) {
        items.each(function (i, item) {
            $(item).parents('.dropdown').find('.dropdown-placeholder').html($(item).html());
        })
    }
});
