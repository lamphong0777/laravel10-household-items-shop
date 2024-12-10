function submitUpdateForm(formId, submitUrl, redirectUrl) {
    $(formId).submit(function(event) {
        event.preventDefault();
        let element = $(this).serializeArray();

        $("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: submitUrl,
            type: 'put',
            data: element,
            dataType: 'json',
            success: function(response) {
                $("button[type=submit]").prop('disabled', false);

                if (response.status) {
                    window.location.href = redirectUrl;
                } else {
                    let errors = response.errors;

                    $("input[type='text'], input[type='password']").removeClass('is-invalid')
                        .siblings('p').removeClass('invalid-feedback')
                        .html('');

                    $.each(errors, function(key, value) {
                        $(`#${key}`).addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback').html(value);
                    });
                }
            },
            error: function(jqXHR, exception) {
                console.log('Some thing went wrong!');
            }
        });
    });
}

function deleteRecord(id, deleteUrl, redirectUrl) {
    let url = deleteUrl.replace("ID", id);
    Swal.fire({
        title: "Bạn chắc chắn muốn xóa?",
        text: "Bạn không thể khôi phục!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Xóa",
        cancelButtonText: "Hủy"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Đã xóa!",
                text: "Đã xóa bản ghi này.",
                icon: "success"
            });
            $.ajax({
                url: url,
                type: 'delete',
                dataType: 'json',
                success: (response) => {
                    if (response["status"]) {
                        let delayInMilliseconds = 2000; //2 seconds
                        setTimeout(function() {
                            window.location.href = redirectUrl;
                        }, delayInMilliseconds);
                    } else {
                        window.location.href = redirectUrl;
                    }
                },
                error: () => {
                    console.log('Something went wrong!');
                }
            });
        }
    });
}

function createFormSubmit(formSelector, options) {
    $(formSelector).submit(function (event) {
        event.preventDefault();

        const form = $(this);
        const formData = form.serializeArray();
        const submitButton = form.find("button[type=submit]");

        // Disable submit button
        submitButton.prop("disabled", true);

        $.ajax({
            url: options.url || form.attr("action"),
            type: options.method || "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                // Enable submit button
                submitButton.prop("disabled", false);

                if (response.status) {
                    // Redirect if specified
                    if (options.successRedirect) {
                        window.location.href = options.successRedirect;
                    }

                    // Call custom success callback
                    if (options.onSuccess) {
                        options.onSuccess(response);
                    }
                } else {
                    // Handle validation errors
                    const errors = response.errors || {};
                    form.find("input, textarea, select").removeClass("is-invalid");
                    form.find(".invalid-feedback").remove();

                    $.each(errors, function (key, value) {
                        const field = form.find(`#${key}`);
                        field.addClass("is-invalid");
                        field.after(`<p class="invalid-feedback">${value}</p>`);
                    });
                }
            },
            error: function (jqXHR, exception) {
                console.error("An error occurred: ", exception);

                if (options.onError) {
                    options.onError(jqXHR, exception);
                }

                // Enable submit button
                submitButton.prop("disabled", false);
            },
        });
    });
}

