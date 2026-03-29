<?php
declare(strict_types=1);

function str_or_null(?string $value): ?string
{
    $v = trim((string) $value);
    return $v === '' ? null : $v;
}

function validate_required(string $field, ?string $value, array &$errors): ?string
{
    $v = trim((string) $value);
    if ($v === '') { $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required.'; return null; }
    return $v;
}

function validate_email(string $field, ?string $value, array &$errors): ?string
{
    $v = strtolower(trim((string) $value));
    if ($v === '') { $errors[$field] = 'Email is required.'; return null; }
    if (!filter_var($v, FILTER_VALIDATE_EMAIL)) { $errors[$field] = 'Email format is invalid.'; return null; }
    return $v;
}

function validate_int(?string $value, int $min = PHP_INT_MIN, int $max = PHP_INT_MAX): ?int
{
    $v = trim((string) $value);
    if ($v === '' || !is_numeric($v) || str_contains($v, '.')) return null;
    $i = (int) $v;
    return ($i >= $min && $i <= $max) ? $i : null;
}

function slugify(string $value): string
{
    $v = preg_replace(['/\s+/', '/[^a-z0-9\-]/', '/-+/'], ['-', '', '-'], strtolower(trim($value)));
    return trim($v, '-');
}

function allowed_positions(): array { return ['C', 'LW', 'RW', 'D', 'G']; }
function allowed_levels(): array    { return ['Pro', 'Junior', 'Amateur', 'Other']; }
