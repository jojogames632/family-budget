const startDateInput = document.getElementById('startDate');
const endDateInput = document.getElementById('endDate');
const budgetSection = document.getElementById('budgetSection');
const Url = new URL(window.location.href);

let startDate = startDateInput.value;
let endDate = endDateInput.value;

startDateInput.addEventListener('change', () => {
	startDate = startDateInput.value
	fetching();
})

endDateInput.addEventListener('change', () => {
	endDate = endDateInput.value
	fetching();
})

function fetching() {
	fetch(Url.pathname + "?startDate=" + startDate + "&endDate=" + endDate + "&ajax=1", {
		headers: {
			'X-Requested-Width': 'XMLHttpRequest'
		}
	}).then(response => 
		response.json()
	).then(data => {
		budgetSection.innerHTML = data.content;
	}).catch(e => alert(e));
}