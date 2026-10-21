document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('editScheduleModal');

  if (!modal) {
    return;
  }

  const form = document.getElementById('editScheduleForm');

  const idInput = document.getElementById('editScheduleId');
  const nameInput = document.getElementById('editScheduleName');
  const dayInput = document.getElementById('editScheduleDay');
  const startInput = document.getElementById('editScheduleStartTime');
  const endInput = document.getElementById('editScheduleEndTime');
  const genderInput = document.getElementById('editScheduleGender');
  const accessTypeInput = document.getElementById('editScheduleAccessType');
  const capacityInput = document.getElementById('editScheduleCapacity');
  const priorityInput = document.getElementById('editSchedulePriority');
  const activeInput = document.getElementById('editScheduleIsActive');

  modal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;

    if (!button) {
      return;
    }

    const scheduleId = button.dataset.id || '';

    idInput.value = scheduleId;
    nameInput.value = button.dataset.name || '';
    dayInput.value = button.dataset.day || '';
    startInput.value = normalizeTime(button.dataset.start);
    endInput.value = normalizeTime(button.dataset.end);
    genderInput.value = button.dataset.gender || 'ALL';
    accessTypeInput.value = button.dataset.accessType || 'ALL';
    capacityInput.value = button.dataset.capacity || '0';
    priorityInput.value = button.dataset.priority || '0';
    activeInput.checked = button.dataset.active === '1';

    form.action =
      window.updateScheduleBaseUrl + '/' + scheduleId;
  });

  function normalizeTime(value) {
    if (!value) {
      return '';
    }

    return value.substring(0, 5);
  }
});