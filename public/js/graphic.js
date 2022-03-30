const accountSelect = document.getElementById('accountSelect');

accountSelect.addEventListener('change', (e) => {
	window.location = "http://symfony.localhost/graphic/" + e.target.value;
})