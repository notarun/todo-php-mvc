<?php require VIEWS_DIR . '/partials/header.php' ?>

    <?php require VIEWS_DIR . '/partials/message.php' ?>

    <div class="span">Enter your e-mail address</div>
    <form method="post">
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
        <button class="btn" type="submit">Verify</button>
    </form>

    <?php if (Core\Session::has('verificationFailed')): ?>
        <?php if (Core\Session::get('verificationFailed')): ?>
            <a href="regenerate-otp">Regenerate verification code.</a>
        <?php endif ?>
    <?php endif ?>

<?php require VIEWS_DIR . '/partials/footer.php' ?>