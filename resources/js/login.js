(function(){
    const grid = document.getElementById('rolesGrid');
    if(!grid) return;
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
})();
