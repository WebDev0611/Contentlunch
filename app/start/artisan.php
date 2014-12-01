<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/
Artisan::add(new DeployCommand);

// Wait until Beta ends to register these
Artisan::add(new BalancedTransferCommand);

//Artisan::add(new NoticeTrialEndCommand);
//Artisan::add(new NoticeTrialNearingEndCommand);

//Artisan::add(new PaymentMonthlyCommand);
//Artisan::add(new PaymentAnnualRenewCommand);
//Artisan::add(new PaymentAnnualNoRenewCommand);

Artisan::add(new TestCommand);
Artisan::add(new TestRepeatCommand);

Artisan::add(new TaskReminderCommand);

