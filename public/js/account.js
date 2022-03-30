const accountSelector = document.getElementById('accountSelector');

accountSelector.addEventListener('change', (e) => {
	window.location.href = e.target.value;
})