<?php if (empty($comments)): ?>
    <div class="alert alert-info text-center">Нет комментариев. Будьте первым!</div>
<?php else: ?>
    <?php foreach ($comments as $comment): ?>
        <div class="card mb-3 comment-item">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="card-subtitle mb-2 text-primary">
                            <i class="fas fa-envelope"></i> <?= esc($comment['name']) ?>
                        </h6>
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt"></i> <?= esc($comment['date']) ?>
                        </small>
                    </div>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $comment['id'] ?>">
                        <i class="fas fa-trash"></i> Удалить
                    </button>
                </div>
                <p class="card-text mt-3"><?= nl2br(esc($comment['text'])) ?></p>
                <small class="text-muted">
                    ID: <?= $comment['id'] ?> | Добавлен: <?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?>
                </small>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>