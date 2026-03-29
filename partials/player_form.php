<!-- Shared player form fields — included by admin/players/create.php and edit.php -->
<!-- Expects: $input (array), $errors (array), allowed_positions(), allowed_levels() -->
<div class="row g-3">
    <!-- First Name -->
    <div class="col-sm-6">
        <label for="first_name" class="form-label">
            First Name <span class="text-danger">*</span>
        </label>
        <input type="text" id="first_name" name="first_name"
            class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars($input['first_name'], ENT_QUOTES, 'UTF-8') ?>" maxlength="100" required>
        <?php if (isset($errors['first_name'])): ?>
            <div class="invalid-feedback">
                <?= htmlspecialchars($errors['first_name'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Last Name -->
    <div class="col-sm-6">
        <label for="last_name" class="form-label">
            Last Name <span class="text-danger">*</span>
        </label>
        <input type="text" id="last_name" name="last_name"
            class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars($input['last_name'], ENT_QUOTES, 'UTF-8') ?>" maxlength="100" required>
        <?php if (isset($errors['last_name'])): ?>
            <div class="invalid-feedback">
                <?= htmlspecialchars($errors['last_name'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Position -->
    <div class="col-sm-6">
        <label for="position" class="form-label">
            Position <span class="text-danger">*</span>
        </label>
        <select id="position" name="position" class="form-select <?= isset($errors['position']) ? 'is-invalid' : '' ?>"
            required>
            <option value="">— Select —</option>
            <?php foreach (allowed_positions() as $pos): ?>
                <option value="<?= $pos ?>" <?= $input['position'] === $pos ? 'selected' : '' ?>>
                    <?= $pos ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['position'])): ?>
            <div class="invalid-feedback">
                <?= htmlspecialchars($errors['position'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Jersey Number -->
    <div class="col-sm-6">
        <label for="jersey_number" class="form-label">Jersey Number</label>
        <input type="number" id="jersey_number" name="jersey_number"
            class="form-control <?= isset($errors['jersey_number']) ? 'is-invalid' : '' ?>"
            value="<?= htmlspecialchars((string) $input['jersey_number'], ENT_QUOTES, 'UTF-8') ?>" min="0" max="99"
            placeholder="Optional">
        <?php if (isset($errors['jersey_number'])): ?>
            <div class="invalid-feedback">
                <?= htmlspecialchars($errors['jersey_number'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Level -->
    <div class="col-12">
        <label for="level" class="form-label">Level</label>
        <select id="level" name="level" class="form-select <?= isset($errors['level']) ? 'is-invalid' : '' ?>">
            <option value="">— Optional —</option>
            <?php foreach (allowed_levels() as $lvl): ?>
                <option value="<?= $lvl ?>" <?= $input['level'] === $lvl ? 'selected' : '' ?>>
                    <?= $lvl ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($errors['level'])): ?>
            <div class="invalid-feedback">
                <?= htmlspecialchars($errors['level'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>
</div>