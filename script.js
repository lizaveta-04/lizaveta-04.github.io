$(document).ready(function () {
    // Модалка Bootstrap
    const formModal = new bootstrap.Modal(document.getElementById('formModal'));

    // Отправка формы AJAX
    $('#contactForm').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showModal("✅ " + response.message);
                    $('#contactForm')[0].reset();
                    updateFeedbackList(); // обновить список сообщений
                } else {
                    showModal("❌ " + response.message);
                }
            },
            error: function() {
                showModal("❌ Ошибка при отправке формы");
            }
        });
    });

    // Функция показа модального окна
    function showModal(message) {
        $('#modal-text').text(message);
        formModal.show();
    }

    // Получение списка feedback через GET
    function updateFeedbackList() {
        $.ajax({
            url: 'ajax.php',
            method: 'GET',
            data: { action: 'get_feedback' },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    renderFeedback(response.data);
                } else {
                    $('#feedbackList').html('<div>Нет сообщений</div>');
                }
            },
            error: function() {
                $('#feedbackList').html('<div>Ошибка загрузки</div>');
            }
        });
    }

    // Выводим сообщения в feedbackList
    function renderFeedback(data) {
        if (!data.length) {
            $('#feedbackList').html('<div>Нет сообщений</div>');
            return;
        }
        let html = '';
        data.forEach(function(item) {
            html += `<div class="border rounded p-2 mb-2">
                <b>${escapeHtml(item.name)}</b>
                <span style="font-size:13px;padding-left:8px;">${escapeHtml(item.email)} | ${escapeHtml(item.phone)} | <time>${item.created_at ? item.created_at.substr(0,16).replace("T", " ") : ""}</time></span>
                <div>${escapeHtml(item.message)}</div>
            </div>`;
        });
        $('#feedbackList').html(html);
    }

    // Защита от XSS на выводе
    function escapeHtml(txt) {
        return $('<div/>').text(txt).html();
    }

    // Кнопка "Обновить"
    $('#refreshFeedback').on('click', updateFeedbackList);

    // Обновление каждые 60 секунд
    setInterval(updateFeedbackList, 60000);

    // Первый показ по загрузке
    updateFeedbackList();
});