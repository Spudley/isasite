
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

### Getting up and running

This is a Symfony app. You'll need PHP 8.4 (it should be okay in 8.3, but I haven't checked).

* Download the repo into a folder.
* Open a console in the project folder.
* Run `composer install` to install the dependencies (mainly Symfony).
* Run `php bin/console doctrine:migrations:migrate` to set up the database (it's SQLite, so no server required).
* Run `cd public` and `php -S localhost:8000 index.php` to start the PHP dev web server.
* You can now navigate to the site at `http://localhost:8000`.

### Usage

* The root path `/` shows an account summary, listing the funds and cash assets in the account, To start with, it will be empty.
* Add £25k to the fund to get started, by going to `/utility/add-cash`.
* Now `/` should show a £25k balance.
* You can also see the raw transaction data at `/utility/raw-data`

### Create an order for units on a fund:

* URL `/api/initiate-order?amount=...&fund=...`
* Amount is in pence, fund can be either `fund-a` or `fund-b` for now.
* eg `/api/initiate-order?amount=1000000&fund=fund-a`

This will create a pair of transaction records; one for the order, the other a negative balancing record against the cash on the account.

The amount on the records won't be the actual amount requested; it will be cost of buying the whole number of units in the fund that can be afforded with the amount requested. The unit price of the funds is hard-coded in the app for now. We also obviously haven't made any actual calls to fund APIs.

At this point, the transaction records will be marked as PENDING and won't show up in the account balance screen.

To proceed with the transaction, we need to move it through IN_PROGRESS status and then to COMPLETE status. **I have not completed the code to do these steps yet.** If this was a real system, these steps would be done behind the scenes as follows:
* We would have a cron job that checks for PENDING transactions and calls the fund API to request processing of the order. We would set the transaction records to IN_PROGRESS at this point.
* We could either use another cron job to check with the fund whether in-progress orders are complete or the fund's system would send us a notification using an API or message queue. Either way, when the fund tells us that the order has been processed successfully, we mark the transaction records as COMPLETE, at which point they will be counted into the balance data on the account details screen.

Since this isn't a real system, the plan is to simply have URLs that can be called manually to do these tasks.

