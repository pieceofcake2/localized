<?php
/**
 * Brazillian Localized Validation class. Handles localized validation for Brazil.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright    Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link         https://cakephp.org
 * @since        Localized Plugin v 0.1
 * @license      https://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('LocalizedValidation', 'Localized.Validation');

/**
 * BrValidation
 */
class BrValidation extends LocalizedValidation
{
    /**
     * Checks a phone number for Brazil.
     *
     * @param string $check The value to check.
     * @return bool Success.
     */
    public static function phone($check)
    {
        return (bool)preg_match('/^(\+?\d{1,3}? ?)?(\(0?\d{2}\) ?)?9?\d{4}[-. ]?\d{4}$/', $check);
    }

    /**
     * Checks a postal code (CEP) for Brazil.
     *
     * @param string $check The value to check.
     * @return bool Success.
     */
    public static function postal($check)
    {
        return (bool)preg_match('/^[0-9]{5}-?[0-9]{3}$/', $check);
    }

    /**
     * Checks SSN for Brazil.
     *
     * @param string $check The value to check.
     * @return bool Success.
     */
    public static function personId($check)
    {
        return BrValidation::cpf($check) || BrValidation::cnpj($check);
    }

    /**
     * Checks CPF for Brazil.
     *
     * @param string $check The value to check.
     * @return bool Success.
     */
    public static function cpf($check)
    {
        $check = trim($check);

        // sometimes the user submits a masked CNPJ
        if (preg_match('/^\d\d\d.\d\d\d.\d\d\d\-\d\d/', $check)) {
            $check = str_replace(['-', '.', '/'], '', $check);
        } elseif (!ctype_digit($check)) {
            return false;
        }

        if (strlen($check) != 11) {
            return false;
        }

        // repeated values are invalid, but algorithms fails to check it
        if (count(array_unique(str_split($check))) === 1) {
            return false;
        }

        $digits = array_map('intval', str_split($check));
        $dv = substr($check, -2);
        for ($pos = 9; $pos <= 10; $pos++) {
            $sum = 0;
            $position = $pos + 1;
            for ($i = 0; $i <= $pos - 1; $i++, $position--) {
                $sum += $digits[$i] * $position;
            }
            $div = $sum % 11;
            if ($div < 2) {
                $digits[$pos] = 0;
            } else {
                $digits[$pos] = 11 - $div;
            }
        }
        $dvRight = $digits[9] * 10 + $digits[10];

        return $dvRight == $dv;
    }

    /**
     * Checks CNPJ for Brazil.
     *
     * @param string $check The value to check.
     * @return bool Success.
     */
    public static function cnpj($check)
    {
        $check = trim($check);
        // sometimes the user submits a masked CNPJ
        if (preg_match('/^\d\d.\d\d\d.\d\d\d\/\d\d\d\d\-\d\d/', $check)) {
            $check = str_replace(['-', '.', '/'], '', $check);
        } elseif (!ctype_digit($check)) {
            return false;
        }

        if (strlen($check) != 14) {
            return false;
        }
        $digits = array_map('intval', str_split($check));
        $firstSum = ($digits[0] * 5) + ($digits[1] * 4) + ($digits[2] * 3) + ($digits[3] * 2) +
            ($digits[4] * 9) + ($digits[5] * 8) + ($digits[6] * 7) + ($digits[7] * 6) +
            ($digits[8] * 5) + ($digits[9] * 4) + ($digits[10] * 3) + ($digits[11] * 2);

        $firstVerificationDigit = $firstSum % 11 < 2 ? 0 : 11 - ($firstSum % 11);

        $secondSum = ($digits[0] * 6) + ($digits[1] * 5) + ($digits[2] * 4) + ($digits[3] * 3) +
            ($digits[4] * 2) + ($digits[5] * 9) + ($digits[6] * 8) + ($digits[7] * 7) +
            ($digits[8] * 6) + ($digits[9] * 5) + ($digits[10] * 4) + ($digits[11] * 3) +
            ($digits[12] * 2);

        $secondVerificationDigit = $secondSum % 11 < 2 ? 0 : 11 - ($secondSum % 11);

        return ($digits[12] == $firstVerificationDigit) && ($digits[13] == $secondVerificationDigit);
    }
}
