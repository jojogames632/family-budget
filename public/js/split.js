const amount1 = document.getElementById('amount1');
const amount2 = document.getElementById('amount2');
const total = document.getElementById('total');

const validSplitBtn = document.getElementById('validSplitBtn');
validSplitBtn.disabled = true;

const transactionAmountContainer = document.getElementById('transactionAmountContainer');

amount1.addEventListener('input', () => {
	if (amount1.value !== "" && amount2.value !== "") {
		total.value = parseFloat(amount1.value) + parseFloat(amount2.value);
		if (total.value !== transactionAmountContainer.innerHTML) {
			validSplitBtn.disabled = true;
		}
		else {
			validSplitBtn.disabled = false;
		}
		console.log(amount1.value);
	}
})

amount2.addEventListener('input', () => {
	if (amount1.value !== "" && amount2.value !== "") {
		total.value = parseFloat(amount1.value) + parseFloat(amount2.value);
		if (total.value !== transactionAmountContainer.innerHTML) {
			validSplitBtn.disabled = true;
		}
		else {
			validSplitBtn.disabled = false;
		}
	}
})
