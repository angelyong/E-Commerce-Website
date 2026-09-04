document.addEventListener("DOMContentLoaded", function () {
  const rows = Array.from(document.querySelectorAll("[data-cart-row]"));
  const grandTotal = document.getElementById("cartGrandTotal");
  const itemCount = document.getElementById("totalItem");

  if (!rows.length || !grandTotal) return;

  function refreshEstimate() {
    let total = 0;
    let count = 0;

    rows.forEach(function (row) {
      const quantityInput = row.querySelector("[data-cart-quantity]");
      const lineTotal = row.querySelector("[data-line-total]");
      const price = Number(row.dataset.unitPrice);
      const maximum = Number(quantityInput.max) || Number.MAX_SAFE_INTEGER;
      const quantity = Math.max(1, Math.min(maximum, Number(quantityInput.value) || 1));

      quantityInput.value = quantity;
      count += quantity;
      total += price * quantity;
      lineTotal.textContent = (price * quantity).toFixed(2);
    });

    grandTotal.textContent = total.toFixed(2);
    itemCount.textContent = "Total Items: " + count + " (save updates before ordering)";
  }

  rows.forEach(function (row) {
    row.querySelector("[data-cart-quantity]").addEventListener("input", refreshEstimate);
  });
});
