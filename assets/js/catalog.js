document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("catalogFilterForm");
  if (!form) return;

  const search = document.getElementById("catalogSearch");
  const category = document.getElementById("catalogCategory");
  const sort = document.getElementById("catalogSort");
  const clear = document.getElementById("clearCatalogFilters");
  const count = document.getElementById("catalogResultCount");
  const groups = Array.from(document.querySelectorAll("[data-catalog-group]"));
  const cards = Array.from(document.querySelectorAll("[data-product-card]"));

  cards.forEach(function (card, index) {
    card.dataset.originalOrder = index;
  });

  function applyFilters() {
    const query = search.value.trim().toLowerCase();
    const selectedCategory = category.value;
    let visibleCount = 0;

    cards.forEach(function (card) {
      const matchesSearch = !query || card.dataset.search.includes(query);
      const matchesCategory = !selectedCategory || card.dataset.category === selectedCategory;
      const visible = matchesSearch && matchesCategory;
      card.hidden = !visible;
      if (visible) visibleCount += 1;
    });

    groups.forEach(function (group) {
      const grid = group.querySelector(".product-grid");
      const empty = group.querySelector(".catalog-empty");
      const groupCards = Array.from(group.querySelectorAll("[data-product-card]"));
      const sortedCards = groupCards.slice().sort(function (a, b) {
        if (sort.value === "price-low") return Number(a.dataset.price) - Number(b.dataset.price);
        if (sort.value === "price-high") return Number(b.dataset.price) - Number(a.dataset.price);
        if (sort.value === "name") return a.dataset.name.localeCompare(b.dataset.name);
        return Number(a.dataset.originalOrder) - Number(b.dataset.originalOrder);
      });

      sortedCards.forEach(function (card) { grid.insertBefore(card, empty); });
      const hasVisibleCards = groupCards.some(function (card) { return !card.hidden; });
      empty.hidden = hasVisibleCards;
      group.hidden = Boolean(selectedCategory) && group.dataset.catalogGroup !== selectedCategory;
    });

    count.textContent = visibleCount + (visibleCount === 1 ? " product found" : " products found");
  }

  form.addEventListener("submit", function (event) {
    event.preventDefault();
    applyFilters();
  });
  search.addEventListener("input", applyFilters);
  category.addEventListener("change", applyFilters);
  sort.addEventListener("change", applyFilters);
  clear.addEventListener("click", function () {
    if (window.location.search) {
      window.location.href = "products.php";
      return;
    }
    search.value = "";
    category.value = "";
    sort.value = "newest";
    applyFilters();
    search.focus();
  });

  applyFilters();
});
