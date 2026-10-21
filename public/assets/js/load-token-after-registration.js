document.addEventListener('DOMContentLoaded', function () {
  const params = new URLSearchParams(window.location.search);

  const token = params.get('token');
  const autoload = params.get('autoload');

  if (token && autoload === '1') {
    const input = document.getElementById('tokenInput');

    if (!input) return;

    input.value = token;

    // optional: hapus flag autoload dari URL biar makin aman
    params.delete('autoload');
    const newUrl =
      window.location.pathname + '?' + params.toString();
    window.history.replaceState({}, '', newUrl);

    input.closest('form').requestSubmit();
  }
});
