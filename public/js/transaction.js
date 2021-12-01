const months = document.querySelectorAll('.monthRow div');
const transactionsSection = document.getElementById('transactionsSection');
const Url = new URL(window.location.href);

let currentMonth = new Date().getMonth();

months.forEach(month => {
	month.addEventListener('click', (e) => {
		currentMonth = e.target.id;

		// clean
		months.forEach(month => {
			month.classList.remove('active');
		})

		// add
		month.classList.add('active');

		fetch(Url.pathname + "?month=" + currentMonth + "&ajax=1", {
			headers: {
				'X-Requested-Width': 'XMLHttpRequest'
			}
		}).then(response => 
			response.json()
		).then(data => {
			transactionsSection.innerHTML = data.content;
		}).catch(e => alert(e));
	})
});