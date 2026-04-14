<?php
$action ??= '';
?>
<form method="get" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>" class="mb-4">
    <div class="row g-2 align-items-end" style="max-width: 500px;">
        <div class="col">
            <label for="season_id" class="form-label fw-semibold">Season</label>
            <select name="season_id" id="season_id" class="form-select">
                <?php foreach ($seasons as $_s): ?>
                    <option value="<?= (int) $_s['season_id'] ?>" <?= (int) $_s['season_id'] === $selectedSeason ? 'selected' : '' ?>>
                        <?= htmlspecialchars($_s['league'] . ' — ' . $_s['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" type="submit">View</button>
        </div>
    </div>
</form>