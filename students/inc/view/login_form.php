<div class="container" style="margin-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="text-center mb-4">User Login</h4>
                    
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['errors'])): ?>
                        <div class="alert alert-danger">
                            <?php foreach($_SESSION['errors'] as $error): ?>
                                <div><?= htmlspecialchars($error) ?></div>
                            <?php endforeach; unset($_SESSION['errors']); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/students/login">
                        <div class="form-group mb-3">
                            <label>Email address</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group mb-4">
                            <label>Password</label>
                            <input type="password" class="form-control" name="passcode" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>