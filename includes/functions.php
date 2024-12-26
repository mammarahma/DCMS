<?php
/**
 * Sanitize input by trimming, stripping slashes, and converting special characters to HTML entities.
 *
 * @param string $input The input string to sanitize.
 * @return string The sanitized input.
 */
function sanitize_input($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

/**
 * Check if the input is empty and return sanitized input or -1 if empty.
 *
 * @param string $input The input string to check.
 * @return string|int The sanitized input or -1 if empty.
 */
function check_empty_input($input) {
    return !empty($input) ? sanitize_input($input) : -1;
}

/**
 * Check if the input contains only alphabetic characters and spaces.
 *
 * @param string $input The input string to check.
 * @return bool True if the input is valid, false otherwise.
 */
function check_alpha_spaces($input) {
    return ctype_alpha(str_replace(' ', '', $input));
}

/**
 * Validate an email address.
 *
 * @param string $email The email address to validate.
 * @return bool True if valid, false otherwise.
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate a phone number (basic validation).
 *
 * @param string $phone The phone number to validate.
 * @return bool True if valid, false otherwise.
 */
function validate_phone($phone) {
    return preg_match('/^[0-9]{10}$/', $phone); // Adjust regex as needed for your phone format
}

/**
 * Validate a date of birth.
 *
 * @param string $dob The date of birth to validate.
 * @return bool True if valid, false otherwise.
 */
function validate_dob($dob) {
    $date = DateTime::createFromFormat('Y-m-d', $dob);
    return $date && $date->format('Y-m-d') === $dob;
}
?>