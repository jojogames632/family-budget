const accountSelector = document.getElementById('accountSelector');
const accountInfos = document.getElementById('accountInfos');

const Url = new URL(window.location.href);

accountSelector.addEventListener('change', (e) => {
	fetch(Url.pathname + "?account=" + e.target.value + "&ajax=1", {
		headers: {
			'X-Requested-Width': 'XMLHttpRequest'
		}
	}).then(response => 
		response.json()
	).then(data => {
		accountInfos.innerHTML = data.content;
	}).catch(e => alert(e));
})