
(function(){
    console.debug('login.js loaded');

    // Role card selection
    const grid = document.getElementById('rolesGrid');
    if(grid){
        const cards = grid.querySelectorAll('.role-card');
        const selectedRoleInput = document.getElementById('selectedRole');
        const roleName = document.getElementById('roleName');

        function clearSelected(){
            cards.forEach(c=>c.classList.remove('selected'));
        }

        cards.forEach(card => {
            card.addEventListener('click', function(){
                clearSelected();
                this.classList.add('selected');
                const role = this.getAttribute('data-role');
                if(selectedRoleInput) selectedRoleInput.value = role;
                if(roleName) roleName.textContent = role ? role.toUpperCase() : '(belum ada)';
            });
        });
    }

    // Forgot password modal + AJAX
    (function(){
        const forgotLink = document.getElementById('forgotPasswordLink');
        const modal = (typeof $ !== 'undefined') ? $('#forgotPasswordModal') : null;
        const form = document.getElementById('forgotPasswordForm');
        const alertBox = document.getElementById('fpAlert');

        console.debug('forgot-password init', !!forgotLink, !!modal, !!form);

        if(forgotLink){
            forgotLink.addEventListener('click', function(e){
                e.preventDefault();
                console.debug('forgotLink clicked');
                if(modal) modal.modal('show');
            });
        }

        function showAlert(message, type='success'){
            if(!alertBox) return;
            alertBox.innerHTML = '<div class="alert alert-' + type + ' alert-dismissible">' +
                '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                message + '</div>';
        }

        if(form){
            form.addEventListener('submit', function(e){
                e.preventDefault();
                const email = document.getElementById('fpEmail').value;
                if(!email) return showAlert('Masukkan email valid', 'danger');

                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
                const url = (forgotLink && forgotLink.dataset && forgotLink.dataset.url) ? forgotLink.dataset.url : '/forgot-password';

                console.debug('sending forgot-password', url, email);

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                }).then(async r => {
                    const body = await r.json().catch(()=>({}));
                    return { status: r.status, body };
                }).then(res => {
                    if(res.status >= 200 && res.status < 300){
                        showAlert(res.body.message || 'Link reset password telah dikirim. Periksa log jika tidak ada SMTP.', 'success');
                        setTimeout(()=>{ if(modal) modal.modal('hide'); }, 2500);
                    } else {
                        const msg = res.body && res.body.message ? res.body.message : (res.body && res.body.errors ? Object.values(res.body.errors).flat().join('\n') : 'Terjadi kesalahan');
                        showAlert(msg, 'danger');
                    }
                }).catch(err => {
                    showAlert('Gagal mengirim permintaan. Coba lagi.', 'danger');
                    console.error(err);
                });
            });
        }
    })();

})();
