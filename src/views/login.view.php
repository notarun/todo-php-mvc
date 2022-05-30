<?php require VIEWS_DIR . '/partials/header.php' ?>

    <?php require VIEWS_DIR . '/partials/message.php' ?>

    <form action="/login" method="post">
        <input type="hidden" name="csrf-token" value="<?php e($data['csrfToken']) ?>">
        <div class="form-group">
            <input
                class="input-style login-input <?php e(Core\Session::has('errors.email') ? 'input-error' : '') ?>"
                name="email"
                type="email"
                placeholder="Email"
                required
            />
            <?php if (Core\Session::has('errors.email')): ?>
                <span class="error">
                    <?php e(Core\Session::get('errors.email')) ?>
                </span>
            <?php endif ?>
        </div>
        <div class="form-group">
            <input
                class="input-style login-input <?php e(Core\Session::has('errors.password') ? 'input-error' : '') ?>"
                name="password"
                type="password"
                placeholder="Password"
                required
            />
            <?php if (Core\Session::has('errors.password')): ?>
                <span class="error">
                    <?php e(Core\Session::get('errors.password')) ?>
                </span>
            <?php endif ?>
        </div>
        <button class="btn login-btn" type="submit">Login</button>
    </form>

<?php require VIEWS_DIR . '/partials/footer.php' ?>