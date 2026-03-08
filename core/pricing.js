document.getElementById('toggleSwitch').addEventListener('change', function() {
    let amounts = document.querySelectorAll('.amount');
    amounts.forEach(amount => {
        let monthly = parseFloat(amount.innerText);
        amount.innerText = this.checked ? (monthly * 12 * 0.83).toFixed(2) : monthly.toFixed(2);document.getElementById('toggleSwitch').addEventListener('change', function() {
    let amounts = document.querySelectorAll('.amount');

    amounts.forEach(amount => {
        let baseMonthlyPrice = parseFloat(amount.dataset.basePrice); // Use stored base price
        if (this.checked) {
            amount.innerText = (baseMonthlyPrice * 12 * 0.83).toFixed(2); // Apply 17% discount for annual
        } else {
            amount.innerText = baseMonthlyPrice.toFixed(2); // Reset to original monthly price
        }
    });
});

// Store original prices to avoid compounding issue
document.addEventListener('DOMContentLoaded', function() {
    let amounts = document.querySelectorAll('.amount');
    amounts.forEach(amount => {
        amount.dataset.basePrice = amount.innerText; // Save original monthly price
    });
});

    });
});
