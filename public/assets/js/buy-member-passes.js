document.addEventListener('DOMContentLoaded', function () {

  const categorySelect = document.getElementById('adminMemberCategory');
  const packageSelect = document.getElementById('adminMemberPackage');

  const priceDisplay = document.getElementById('memberPriceDisplay');
  const priceHidden = document.getElementById('memberPrice');

  if (!categorySelect || !packageSelect) {
    return;
  }

  const originalOptions = Array.from(packageSelect.options).map(opt => opt.cloneNode(true));

  function formatIDR(val) {
    return Number(val || 0).toLocaleString('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    });
  }

  function resetPrice() {
    if (priceDisplay) priceDisplay.value = '';
    if (priceHidden) priceHidden.value = '';
  }

  function filterMembershipPackages() {
    const selectedCategory = categorySelect.value;

    packageSelect.innerHTML = '';

    originalOptions.forEach(option => {
      const value = option.value;
      const targetMemberType = option.getAttribute('data-target-member-type');

      if (value === '') {
        packageSelect.appendChild(option.cloneNode(true));
        return;
      }

      if (!selectedCategory) return;

      const isMatch =
        targetMemberType === selectedCategory ||
        targetMemberType === 'ALL';

      if (isMatch) {
        packageSelect.appendChild(option.cloneNode(true));
      }
    });

    packageSelect.value = '';
    resetPrice();
  }

  function updateMembershipPrice() {
    const selected = packageSelect.options[packageSelect.selectedIndex];

    if (!selected || !selected.value) {
      resetPrice();
      return;
    }

    const price = parseFloat(selected.getAttribute('data-price')) || 0;

    if (priceDisplay) priceDisplay.value = formatIDR(price);
    if (priceHidden) priceHidden.value = price;
  }

  categorySelect.addEventListener('change', filterMembershipPackages);
  packageSelect.addEventListener('change', updateMembershipPrice);

  filterMembershipPackages();

});