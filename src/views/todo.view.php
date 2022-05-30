<?php require VIEWS_DIR . '/partials/header.php' ?>

    <form action="/add" method="post">
        <input type="hidden" name="csrf-token" value="<?php e($data['csrfToken']) ?>">
        <div class="flex-box">
            <div class="form-group">
                <input
                    class="input-style todo-input <?php e(Core\Session::has('errors.todo-input') ? 'input-error' : '') ?>"
                    name="todo-input"
                    type="text"
                    placeholder="Add an item"
                    required
                />

                <?php if (Core\Session::has('errors.todo-input')): ?>
                    <span class="error">
                        <?php e(Core\Session::get('errors.todo-input')) ?>
                    </span>
                <?php endif ?>
            </div>
            <button class="icon-btn todo-btn" type="submit">➕</button>
        </div>
    </form>

    <ul class="todo-item-list">
        <?php foreach ($data['todoItems'] as $item): ?>
            <li class="todo-item flex-box">
                <form action="/<?php e($item['id'] . ($item['done'] ? '/undone' : '/done')) ?>" method="post">
                    <input name="item-id" type="hidden" value="<?php e($item['id']) ?>">
                    <input type="hidden" name="csrf-token" value="<?php e($data['csrfToken']) ?>">
                    <button class="icon-btn" type="submit"><?php e($item['done'] ? '❌' : '✅') ?></button>
                </form>
                <form action="/<?php e($item['id']) ?>/delete" method="post">
                    <input name="item-id" type="hidden" value="<?php e($item['id']) ?>">
                    <input type="hidden" name="csrf-token" value="<?php e($data['csrfToken']) ?>">
                    <button class="icon-btn" type="submit">🗑️️</button> 
                </form>

                <?php echo($item['done'] ? '<s class="strikethrough">' : '') ?>
                    <a href="/<?php e($item['id']) ?>" class="item-link"><?php e($item['item']) ?></a>
                <?php echo($item['done'] ? '</s>' : '') ?>
            </li>
        <?php endforeach ?>
    </ul>

<?php require VIEWS_DIR . '/partials/footer.php' ?>