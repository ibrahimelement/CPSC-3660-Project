<?php require_once BASE_PATH . '/partials/header.php';
render_flash(); ?>

<div class="row justify-content-center">
  <div class="col-sm-8 col-md-5 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h2 class="card-title mb-4">Admin Login</h2>

        <?php if (!empty($errors['form'])): ?>
          <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($errors['form'], ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php endif; ?>

        <form method="post" action="" novalidate>
          <input type="hidden" name="next" value="<?= $nextHidden ?>">

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email"
              class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
              value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" autocomplete="email" required>
            <?php if (isset($errors['email'])): ?>
              <div class="invalid-feedback">
                <?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?>
              </div>
            <?php endif; ?>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password"
              class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" autocomplete="current-password"
              required>
            <?php if (isset($errors['password'])): ?>
              <div class="invalid-feedback">
                <?= htmlspecialchars($errors['password'], ENT_QUOTES, 'UTF-8') ?>
              </div>
            <?php endif; ?>
          </div>

          <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once BASE_PATH . '/partials/footer.php'; ?>