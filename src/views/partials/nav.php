<nav>
    <ul class="navigation">
        <?php if (!App\Models\User::isLoggedin()): ?>
            <li class="nav-item"><a class="link" href="/login">Login</a></li>
            <li class="nav-item"><a class="link" href="/register">Register</a></li>
        <?php else: ?>
            <li>
                <form action="/logout" method="POST">
                    <input type="hidden" name="csrf-token" value="<?php e($data['csrfToken']) ?>">
                    <span class="user-name">
                    </span>
                    (<button class="link-btn logout-btn" type="submit">Logout</button>)
                </form>
            </li>
        <?php endif ?>
    </ul>
</nav>