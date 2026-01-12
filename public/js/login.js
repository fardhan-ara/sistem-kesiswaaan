$(document).ready(function() {
    // Forgot Password Modal
    $('#forgotPasswordLink').on('click', function(e) {
        e.preventDefault();
        $('#forgotPasswordModal').modal('show');
    });

    // Forgot Password Form Submit
    $('#forgotPasswordForm').on('submit', function(e) {
        e.preventDefault();
        const email = $('#fpEmail').val();
        const url = $('#forgotPasswordLink').data('url');
        
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                email: email,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#fpAlert').html('<div class="alert alert-success">Link reset password telah dikirim ke email Anda.</div>');
                setTimeout(() => $('#forgotPasswordModal').modal('hide'), 2000);
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.message || 'Terjadi kesalahan';
                $('#fpAlert').html('<div class="alert alert-danger">' + msg + '</div>');
            }
        });
    });
});