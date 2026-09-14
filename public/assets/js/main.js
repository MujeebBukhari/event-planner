document.addEventListener('DOMContentLoaded', function () {
    // Mobile navigation toggle
    var mobileToggle = document.querySelector('.mobile-toggle');
    var navLinks = document.querySelector('.nav-links');

    if (mobileToggle && navLinks) {
        mobileToggle.addEventListener('click', function () {
            navLinks.classList.toggle('open');
        });
    }

    // Payment method selector on tickets page
    var payButtons = document.querySelectorAll('.pay-methods button');
    var paymentMethodInput = document.getElementById('payment_method_input');
    payButtons.forEach(function (button) {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            payButtons.forEach(function (btn) {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            if (paymentMethodInput) {
                paymentMethodInput.value = this.getAttribute('data-method') || 'card';
            }
        });
    });

    // Ticket quantity selector & summary calculation
    var tierRows = document.querySelectorAll('.tier-row');
    if (tierRows.length > 0) {
        function updateOrderSummary() {
            var total = 0;
            var summaryLines = document.querySelectorAll('.summary-line');
            var payButton = document.querySelector('.panel .btn-solid');

            tierRows.forEach(function (row, index) {
                var nameEl = row.querySelector('.tier-name');
                var priceEl = row.querySelector('.tier-price');
                var qtySpan = row.querySelector('.qty-control span');

                if (nameEl && priceEl && qtySpan) {
                    var price = parseFloat(priceEl.textContent.replace('$', '').trim()) || 0;
                    var qty = parseInt(qtySpan.textContent.trim(), 10) || 0;
                    var subtotal = price * qty;
                    total += subtotal;

                    if (summaryLines[index]) {
                        var spans = summaryLines[index].querySelectorAll('span');
                        if (spans.length >= 2) {
                            spans[0].innerHTML = nameEl.textContent.trim() + ' &times; ' + qty;
                            spans[1].textContent = '$' + subtotal.toFixed(2);
                        }
                    }
                }
            });

            // Sync hidden inputs for server form submission
            var eventTypeInput = document.getElementById('event_type_input');
            var guestCountInput = document.getElementById('guest_count_input');
            if (eventTypeInput && guestCountInput && tierRows.length >= 2) {
                var gaQty = parseInt(tierRows[0].querySelector('.qty-control span')?.textContent.trim() || '0', 10);
                var vipQty = parseInt(tierRows[1].querySelector('.qty-control span')?.textContent.trim() || '0', 10);

                if (vipQty > 0 && gaQty === 0) {
                    eventTypeInput.value = 'VIP Access';
                    guestCountInput.value = vipQty;
                } else if (gaQty > 0) {
                    eventTypeInput.value = 'General Admission';
                    guestCountInput.value = gaQty;
                } else {
                    eventTypeInput.value = 'General Admission';
                    guestCountInput.value = 0;
                }
            }

            var totalEl = document.querySelector('.summary-line.total b');
            if (totalEl) {
                totalEl.textContent = '$' + total.toFixed(2);
            }

            if (payButton && payButton.textContent.indexOf('Pay') !== -1) {
                payButton.textContent = 'Pay $' + total.toFixed(2) + ' Securely';
            }
        }

        tierRows.forEach(function (row) {
            var decreaseBtn = row.querySelector('button[aria-label="Decrease"]');
            var increaseBtn = row.querySelector('button[aria-label="Increase"]');
            var qtySpan = row.querySelector('.qty-control span');

            if (decreaseBtn && qtySpan) {
                decreaseBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    var current = parseInt(qtySpan.textContent.trim(), 10) || 0;
                    if (current > 0) {
                        qtySpan.textContent = current - 1;
                        updateOrderSummary();
                    }
                });
            }

            if (increaseBtn && qtySpan) {
                increaseBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    var current = parseInt(qtySpan.textContent.trim(), 10) || 0;
                    qtySpan.textContent = current + 1;
                    updateOrderSummary();
                });
            }
        });

        // Initialize on load
        updateOrderSummary();
    }

    // Prevent default jump for demo placeholder action buttons
    var placeholderButtons = document.querySelectorAll('a.btn[href="#"]');
    placeholderButtons.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
        });
    });
});
