
        $("#registerForm").submit(function (event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $('#reg_form_btn').prop('disabled', true);
            $('#reg_form_btn').html(lgSpinner);
            $('#registerForm .error').html('');
            $.ajax({
                url: url,
                type: "POST",
                data: formData + "&action=club_register",
                dataType: "json",
                success: function (response) {
                    console.log('response', response)
                    if (response.success) {
                        window.location.href = 'https://kutuga.dk/login?confirm=1';
                       //  $(".success_user_reg").removeClass('d-none');
                    } else {
                        $.each(response?.data?.errors, function (key, value) {
                            console.log('key', key)
                            $('.error_' + key).html(value);
                        });
                    }
                    $('#reg_form_btn').prop('disabled', false);
                    $('#reg_form_btn').html('Opret en konto');
                },
                error: function () {
                    $('#reg_form_btn').prop('disabled', false);
                    $('#reg_form_btn').html('Opret en konto');
                    alert("Something went wrong. Please try again.");
                }
            });
        });
        
