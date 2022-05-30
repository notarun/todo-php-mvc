<?php require VIEWS_DIR . '/partials/header.php' ?>

    <?php require VIEWS_DIR . '/partials/message.php' ?>

    <form action="/<?php e($data['id']) ?>/update" method="post">
        <input type="hidden" name="csrf-token" value="<?php e($data['csrfToken']) ?>">
        <div class="flex-box">
            <div class="form-group">
                <input
                    class="input-style todo-input <?php e(Core\Session::has('errors.todo-input') ? 'input-error' : '') ?>"
                    name="todo-input"
                    type="text"
                    placeholder="Add an item"
                    value="<?php e($data['item']) ?>"
                    required
                />

                <?php if (Core\Session::has('errors.todo-input')): ?>
                    <span class="error">
                        <?php e(Core\Session::get('errors.todo-input')) ?>
                    </span>
                <?php endif ?>
            </div>
            <button class="icon-btn todo-btn" type="submit">🖊️</button>
        </div>
    </form>

    <div class="flex-box btn-container">
        <form action="/<?php e($data['id']) ?>/delete" method="post">
            <input type="hidden" name="csrf-token" value="<?php e($data['csrfToken']) ?>">
            <button class="btn btn-red" type="submit">Delete️️</button> 
        </form>

        <form action="/<?php e($data['id'] . ($data['done'] ? '/undone' : '/done')) ?>" method="post">
            <input type="hidden" name="csrf-token" value="<?php e($data['csrfToken']) ?>">
            <button class="btn <?php e($data['done'] ? 'btn-red' : 'btn-green') ?>" type="submit">
                <?php e($data['done'] ? 'Mark Undone' : 'Mark Done') ?>
            </button>
        </form>
    </div>
<?php require VIEWS_DIR . '/partials/footer.php' ?>