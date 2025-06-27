   document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('userMenuToggle');
        const dropdown = document.getElementById('userDropdown');

        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', function (e) {
            const wrapper = document.getElementById('userMenuWrapper');
            if (!wrapper.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    });
