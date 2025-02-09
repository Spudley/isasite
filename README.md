
Cushon technical interview assignment notes
-------------------------------------------

Simon Champion. February 2025.

### Analysis and assumptions

The task is a broad one and open-ended; potentially an entire application. Clearly this isn't the intent, so I need to focus in on the single workflow of ordering units from a fund.

I will assume that the account already has a cash balance paid into it. We don't need to write code to simulate paying cash into the account.

I will also completely ignore the need for login and authentication, etc. The system has no concept of users; records do not have a userID field.

To keep things simple, I will ignore any variability in values in the system. eg unit prices will be static and hard-coded (not much of an investment, but never mind!).

### Design

We will use a SQLite database.

* We will have a transactions table that stores the number of units of a fund ordered, and the unit value.
* For each order transaction, there will be a balancing transaction record on the same table to decrease the cash balance by the same amount.
* Thus every buy or sell (or deposit or withdrawl), will have a pair of transaction records that balance out to zero.

* Orders will go through a three-stage process:
  1) When customer places order, the fund's API will be called to confirm that the order would be allowed. This will also give us the current unit price. Transaction records will be created at this stage in PENDING status.
  2) A job will run periodically checking for PENDING transactions. It will call the fund's API to initiate the process of purchasing the units. Transaction records will be updated to IN_PROGRESS.
  3) Another job will run checking for IN_PROGRESS transactions. We will call the fund API to check if the order has been processed. If so, we can set the transactions to COMPLETE.
  4) Errors during the process will result in us calling the API to request the order be rolled back, and the transactions records being marked as ROLLED_BACK.
  5) All the steps above will also involve checking the account balance again to ensure that the user account holder has/still has sufficient funds to place the order.

None of the fund APIs actually exist, so I'm not really making any calls to them; my code simply pretends it's made the call and got the expected results.

To reduce traffic in real life, the third step above would probably be better served by having the fund APIs call us, or send a message into a queue, rather than us calling them. That's obviously not going to happen, since the fund system doesn't exist.

I'm not going to create much (if any) user interface for this; the significant actions will be achieved by calling URLs as GET or POST requests.
