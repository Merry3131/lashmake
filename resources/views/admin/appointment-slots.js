document.addEventListener('DOMContentLoaded', function () {
    const specialistSelect = document.getElementById('specialist_id');
    const dateInput = document.getElementById('appointment_date');
    const timeSelect = document.getElementById('appointment_time');

    // Находим контейнер формы, на котором мы сохраним все PHP переменные
    const formContainer = document.getElementById('appointment-edit-form');
    if (!formContainer) return;

    // Считываем сохраненные значения из data-атрибутов HTML
    const currentSavedTime = formContainer.dataset.currentSavedTime;
    const currentSavedDate = formContainer.dataset.currentSavedDate;
    const currentSpecialistId = formContainer.dataset.currentSpecialistId;
    const appointmentId = formContainer.dataset.appointmentId;

    async function fetchAvailableSlots() {
        const specialistId = specialistSelect.value;
        const date = dateInput.value;

        if (!specialistId || !date) {
            timeSelect.innerHTML = '<option value="">Сначала выберите дату и мастера</option>';
            return;
        }

        timeSelect.innerHTML = '<option value="">Загрузка окон...</option>';

        try {
            // Стучимся на твой роут из файла api.php (/api/slots)
            const response = await fetch(`/api/slots?specialist_id=${specialistId}&date=${date}&ignore_appointment_id=${appointmentId}`);
            const slots = await response.json();

            timeSelect.innerHTML = '';

            // Если слотов нет и это НЕ день текущей записи, пишем что окон нет
            if (slots.length === 0 && date !== currentSavedDate) {
                timeSelect.innerHTML = '<option value="">Нет свободных окон или выходной</option>';
                return;
            }

            // Безопасность: возвращаем текущее время записи в массив
            if (date === currentSavedDate && specialistId == currentSpecialistId && !slots.includes(currentSavedTime)) {
                slots.push(currentSavedTime);
                slots.sort();
            }

            // Наполняем выпадающий список доступными окнами
            slots.forEach(slot => {
                const option = document.createElement('option');
                option.value = slot;
                option.textContent = slot;

                // Автоматически выделяем слот, если он совпадает с текущим временем сеанса в БД
                if (slot === currentSavedTime && date === currentSavedDate && specialistId == currentSpecialistId) {
                    option.selected = true;
                }
                timeSelect.appendChild(option);
            });

        } catch (error) {
            console.error('Ошибка загрузки слотов через API:', error);
            timeSelect.innerHTML = '<option value="">Ошибка загрузки окон</option>';
        }
    }

    // Перезапускаем поиск окон при смене мастера или даты
    specialistSelect.addEventListener('change', fetchAvailableSlots);
    dateInput.addEventListener('change', fetchAvailableSlots);

    // Первый автоматический запуск при открытии страницы админом
    fetchAvailableSlots();
});
