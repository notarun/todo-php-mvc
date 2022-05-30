<?php if (Core\Session::has('message')): ?>
    <div class="message-box center">
        <?php e(Core\Session::get('message')) ?>
    </div>
<?php endif ?>