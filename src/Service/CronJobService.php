<?php

namespace App\Service;

/*
 * Check the database for pending orders, and attempt to action them.
 *
 * This won't be run on a real cron job of course, we'll simulate it for the demo by calling it from a utility controller.
 */

use App\Repository\TransactionRepository;

class CronJobService
{
    public function __construct(
        protected TransactionRepository $transactionRepository,
    )
    {
    }

    public function cronJob()
    {
        // 1. search for IN_PROGRESS cash transactions.
        // also search for matching fund transactions. Should also be in progress.

        // if all is good with the transactions, and the requested amount is available in the account
        //   place call to fund API to check if processing is complete
        //   if it is complete and no errors, then set both transaction records to COMPLETE.
        //   if issues with the processing, issue rollback call to API and set records to ROLLED_BACK.
        // To minimise traffic overhead, all of this would ideally be done by the fund notifying us
        // via a message queue rather than us polling for an answer, but I don't have time in this demo for that.

        // 2. search for PENDING cash transactions.
        // also search for matching fund transactions. Should also be pending.

        // if all is good with the transactions, and the requested amount is available in the account
        //   place completion call to fund for the order
        //   if no errors, then set both transaction records to IN_PROGRESS.
    }
}
