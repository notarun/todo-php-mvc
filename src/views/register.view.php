<?php require VIEWS_DIR . '/partials/header.php' ?>

    <form action="/register" method="post">
        <input type="hidden" name="csrf-token" value="<?php e($data['csrfToken']) ?>">

        <div class="form-group">
            <input
                class="input-style register-input <?php e(Core\Session::has('errors.name') ? 'input-error' : '') ?>"
                name="name"
                type="text"
                placeholder="Name"
                required
            />
            <?php if (Core\Session::has('errors.name')): ?>
                <span class="error">
                    <?php e(Core\Session::get('errors.name')) ?>
                </span>
            <?php endif ?>
        </div>

        <div class="form-group">
            <input
                class="input-style register-input <?php e(Core\Session::has('errors.email') ? 'input-error' : '') ?>"
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
                class="input-style register-input <?php e(Core\Session::has('errors.password') ? 'input-error' : '') ?>"
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

        <div class="form-group">
            <input
                class="input-style register-input <?php e(Core\Session::has('errors.repeat-password') ? 'input-error' : '') ?>"
                name="repeat-password"
                type="password"
                placeholder="Re-Enter Password"
                required
            />
            <?php if (Core\Session::has('errors.repeat-password')): ?>
                <span class="error">
                    <?php e(Core\Session::get('errors.repeat-password')) ?>
                </span>
            <?php endif ?>
        </div>

        <button class="btn login-btn" type="submit">Register</button>
    </form>

<?php require VIEWS_DIR . '/partials/footer.php' ?>