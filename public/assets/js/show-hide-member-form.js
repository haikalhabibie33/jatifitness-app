function showForm(type, el) {
  document.getElementById('form-casual').style.display =
    type === 'casual' ? 'block' : 'none';

  document.getElementById('form-member').style.display =
    type === 'member' ? 'block' : 'none';

  // update active nav
  document.querySelectorAll('.nav-link').forEach(link => {
    link.classList.remove('active');
  });

  el.classList.add('active');
}
