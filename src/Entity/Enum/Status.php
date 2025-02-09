<?php

namespace App\Entity\Enum;

/**
 * The actual numbers used for status here are arbitrary.
 * I would usually leave gaps in sets of values like this to allow for additional status types to be added later
 * in a sensible sequence, but it doesn't really matter; we'll never refer directly to the numeric values anyway.
 */
enum Status: int
{
    // Transaction record created locally, ready to start the process if conditions are met (eg funds available).
    case PENDING = 0;

    // Transaction has been started with first stage call to bank/fund API.
    case IN_PROGRESS = 1;

    // Transaction complete. All good.
    case COMPLETE = 10;

    // Transaction failed; will need to be rolled back.
    case FAILED = 49;

    // Transaction failed and has been completely rolled back.
    case ROLLED_BACK = 99;

    // Transaction cancelled before processing started.
    case CANCELLED = 1000;
}
