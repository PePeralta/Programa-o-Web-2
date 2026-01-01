const stars = document.querySelectorAll('#stars i');
const estrelasInput = document.getElementById('estrelas');

stars.forEach(star => {
    star.addEventListener('click', () => {
        const value = star.getAttribute('data-value');
        estrelasInput.value = value;

        stars.forEach(s => {
            s.classList.remove('text-warning');
            if (s.getAttribute('data-value') <= value) {
                s.classList.add('text-warning');
            }
        });
    });
});

