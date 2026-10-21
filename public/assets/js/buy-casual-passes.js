document.addEventListener('DOMContentLoaded', function () {
  const categorySelect = document.getElementById('adminCasualCategory');
  const packageSelect = document.getElementById('adminCasualPackage');
  const quantityInput = document.getElementById('adminCasualQuantity');
  const durationInput = document.getElementById('adminCasualTotalDuration');
  const totalPriceInput = document.getElementById('adminCasualTotalPrice');
  const totalPriceDisplay = document.getElementById('adminCasualTotalPriceDisplay');

  if (!categorySelect || !packageSelect) {
    return;
  }

  const originalPackageOptions = Array.from(packageSelect.options).map(option => option.cloneNode(true));

  function formatIDR(value) {
    return Number(value || 0).toLocaleString('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    });
  }

  function clampQuantity() {
    if (!quantityInput) {
      return 1;
    }

    let quantity = parseInt(quantityInput.value, 10);

    if (isNaN(quantity) || quantity < 1) {
      quantity = 1;
    }

    if (quantity > 30) {
      quantity = 30;
    }

    quantityInput.value = quantity;

    return quantity;
  }

  function resetCalculation() {
    if (durationInput) {
      durationInput.value = '';
    }

    if (totalPriceInput) {
      totalPriceInput.value = '';
    }

    if (totalPriceDisplay) {
      totalPriceDisplay.value = '';
    }
  }

  function filterPackagesByCategory() {
    const selectedCategory = categorySelect.value;

    packageSelect.innerHTML = '';

    originalPackageOptions.forEach(option => {
      const optionValue = option.value;
      const targetMemberType = option.getAttribute('data-target-member-type');

      /**
       * Always keep placeholder option.
       */
      if (optionValue === '') {
        packageSelect.appendChild(option.cloneNode(true));
        return;
      }

      /**
       * If category is empty, don't show package yet.
       */
      if (!selectedCategory) {
        return;
      }

      /**
       * REGULAR should see REGULAR + ALL
       * STUDENT should see STUDENT + ALL
       */
      const isMatching =
        targetMemberType === selectedCategory ||
        targetMemberType === 'ALL';

      if (isMatching) {
        packageSelect.appendChild(option.cloneNode(true));
      }
    });

    packageSelect.value = '';
    resetCalculation();
  }

  function recalculateCasualPass() {
    const selectedOption = packageSelect.options[packageSelect.selectedIndex];

    if (!selectedOption || !selectedOption.value) {
      resetCalculation();
      return;
    }

    const baseDuration = parseInt(selectedOption.getAttribute('data-duration'), 10) || 0;
    const basePrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
    const quantity = clampQuantity();

    const totalDuration = baseDuration * quantity;
    const totalPrice = basePrice * quantity;

    if (durationInput) {
      durationInput.value = totalDuration;
    }

    if (totalPriceInput) {
      totalPriceInput.value = totalPrice;
    }

    if (totalPriceDisplay) {
      totalPriceDisplay.value = formatIDR(totalPrice);
    }
  }

  categorySelect.addEventListener('change', function () {
    filterPackagesByCategory();
  });

  packageSelect.addEventListener('change', function () {
    recalculateCasualPass();
  });

  if (quantityInput) {
    quantityInput.addEventListener('input', function () {
      recalculateCasualPass();
    });

    quantityInput.addEventListener('keydown', function (event) {
      const allowedKeys = [
        'ArrowUp',
        'ArrowDown',
        'ArrowLeft',
        'ArrowRight',
        'Tab',
        'Backspace',
        'Delete'
      ];

      const isNumber = /^[0-9]$/.test(event.key);

      if (!allowedKeys.includes(event.key) && !isNumber) {
        event.preventDefault();
      }
    });
  }

  /**
   * Initial state:
   * hide packages until category selected.
   */
  filterPackagesByCategory();
});