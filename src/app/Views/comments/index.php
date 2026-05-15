<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Комментарии - Тестовое задание</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .container { max-width: 800px; }
        .header { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .comment-item { transition: transform 0.2s; }
        .comment-item:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .delete-btn { transition: all 0.2s; }
        .delete-btn:hover { transform: scale(1.05); }
        .loading { display: none; text-align: center; padding: 20px; }
        .error { color: #dc3545; font-size: 0.875em; margin-top: 5px; }
        .form-card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .sort-bar { background: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        h1 { color: #333; font-size: 2rem; margin: 0; }
        .pagination { justify-content: center; }
        .pagination a { cursor: pointer; }
    </style>
</head>
<body>
    <div class="container mt-4 mb-4">
        <div class="header text-center">
            <h1><i class="fas fa-comments"></i> Комментарии</h1>
            <p class="text-muted">Оставьте свой комментарий</p>
        </div>
        
        <div class="sort-bar">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center align-items-center flex-wrap">
                        <label class="mr-2 font-weight-bold mb-0">Сортировать по:</label>
                        <select id="sort" class="form-control mr-2" style="width: auto;">
                            <option value="created_at">📅 Дате добавления</option>
                            <option value="id">🔢 ID</option>
                        </select>
                        <select id="order" class="form-control" style="width: auto;">
                            <option value="desc">⬇ По убыванию</option>
                            <option value="asc">⬆ По возрастанию</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="comments-list">
            <?= view('comments/list', ['comments' => $comments]) ?>
        </div>
        
        <div id="pagination" class="mt-4">
            <?= $pager->links() ?>
        </div>
        
        <div class="form-card mt-4">
            <h4 class="mb-3"><i class="fas fa-plus-circle"></i> Добавить комментарий</h4>
            <form id="comment-form">
                <div class="form-group">
                    <label for="name"><i class="fas fa-envelope"></i> Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="name" name="name" placeholder="example@mail.com" required>
                    <div id="name-error" class="error"></div>
                </div>
                <div class="form-group">
                    <label for="text"><i class="fas fa-comment"></i> Комментарий <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="text" name="text" rows="4" placeholder="Ваш комментарий..." required></textarea>
                    <div id="text-error" class="error"></div>
                </div>
                <div class="form-group">
                    <label for="date"><i class="fas fa-calendar"></i> Дата <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="date" name="date" placeholder="ДД.ММ.ГГГГ" required>
                    <small class="form-text text-muted">Пример: 15.01.2025</small>
                    <div id="date-error" class="error"></div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-paper-plane"></i> Отправить
                </button>
                <div id="loading" class="loading">
                    <i class="fas fa-spinner fa-spin"></i> Отправка...
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        // Функция валидации email
        function validateEmail(email) {
            var re = /^[^\s@]+@([^\s@.,]+\.)+[^\s@.,]{2,}$/;
            return re.test(email);
        }
        
        // Функция загрузки комментариев с учетом страницы
        function loadComments(page) {
            var sort = $('#sort').val();
            var order = $('#order').val();
            page = page || 1;
            
            $.get('/comments', {sort: sort, order: order, page: page}, function(data) {
                $('#comments-list').html(data.comments);
                $('#pagination').html(data.pagination);
                bindPaginationClick();
            }).fail(function() {
                alert('Ошибка загрузки комментариев');
            });
        }
        
        // Обработчик кликов по пагинации
        function bindPaginationClick() {
            $('.pagination a').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var page = url.match(/page=(\d+)/);
                if (page) {
                    loadComments(page[1]);
                }
            });
        }
        
        // Сортировка
        $('#sort, #order').change(function() {
            loadComments(1);
        });
        
        // Добавление комментария с клиентской валидацией
        $('#comment-form').submit(function(e) {
            e.preventDefault();
            $('.error').empty();
            
            var email = $('#name').val();
            var text = $('#text').val();
            var date = $('#date').val();
            var hasError = false;
            
            // Валидация email
            if (!validateEmail(email)) {
                $('#name-error').text('Введите корректный email (например: user@domain.com)');
                hasError = true;
            }
            
            // Валидация текста
            if (text.trim().length < 3) {
                $('#text-error').text('Комментарий должен содержать минимум 3 символа');
                hasError = true;
            }
            
            // Валидация даты (формат ДД.ММ.ГГГГ)
            if (!date.trim()) {
                $('#date-error').text('Введите дату');
                hasError = true;
            } else if (!/^\d{2}\.\d{2}\.\d{4}$/.test(date.trim())) {
                $('#date-error').text('Введите дату в формате ДД.ММ.ГГГГ (например: 15.01.2025)');
                hasError = true;
            }
            
            if (hasError) {
                return;
            }
            
            $('#loading').show();
            
            $.post('/comments', $(this).serialize(), function(data) {
                if (data.success) {
                    $('#comment-form')[0].reset();
                    loadComments(1);
                } else {
                    $.each(data.errors, function(field, msg) {
                        $('#' + field + '-error').text(msg);
                    });
                }
            }).fail(function() {
                alert('Ошибка при отправке');
            }).always(function() {
                $('#loading').hide();
            });
        });
        
        // Удаление комментария
        $(document).on('click', '.delete-btn', function() {
            if (confirm('Удалить комментарий?')) {
                var id = $(this).data('id');
                $.ajax({
                    url: '/comments/' + id,
                    type: 'DELETE',
                    success: function(data) {
                        if (data.success) loadComments(1);
                    },
                    error: function() {
                        alert('Ошибка при удалении');
                    }
                });
            }
        });
        
        bindPaginationClick();
    });
    </script>
</body>
</html>