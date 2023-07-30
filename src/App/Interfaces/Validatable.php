<?php

namespace App\Interfaces;

/**
 * Interface Validatable
 *
 * @author Luka
 */
interface Validatable {

    /**
     * Validates a given value.
     *
     * @param mixed $value The value to validate.
     *
     * @return bool Result of the validation process.
     *
     * @throws \Exception If the validation fails, an exception will be thrown.
     */
    public function validate(mixed $value): bool;
}
