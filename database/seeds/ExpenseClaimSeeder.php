<?php

use App\Domains\SessionReports\Models\Report;
use App\Expense;
use App\ExpenseClaim;
use App\Mentee;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseClaimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $todaysDate = Carbon::now()->toDateString();  

        // mentor 1
        $claim = $this->addExpenseClaim('mentor-1', 'mentee-1', $todaysDate, 'pending');
        $this->addExpense($claim, $todaysDate, 5.10);
        $this->addExpense($claim, $todaysDate, 5.10);
        $this->rejectExpenseClaim($claim);

        $claim = $this->addExpenseClaim('mentor-1', 'mentee-1', $todaysDate, 'pending');
        $this->addExpense($claim, $todaysDate, 4.10);
        $this->addExpense($claim, $todaysDate, 3.10);
        $this->processExpenseClaim($claim);

        // mentor 2
        $claim = $this->addExpenseClaim('mentor-2', 'mentee-2', $todaysDate, 'pending');
        $this->addExpense($claim, $todaysDate, 1.10);
        $this->addExpense($claim, $todaysDate, 2.10);

        // mentor 3
        $claim = $this->addExpenseClaim('mentor-3', 'mentee-3', $todaysDate, 'pending');
        $this->addExpense($claim, $todaysDate, 1.10);
        $this->addExpense($claim, $todaysDate, 2.10);
    }

    private function addExpense($expense_claim, $date, $amount, $description="Some expense item") {
        $expenseItem = new Expense();
        $expenseItem->expense_claim_id = $expense_claim->id;
        $expenseItem->date = $date;
        $expenseItem->description = $description;
        $expenseItem->amount = $amount;
        $expenseItem->save();
    }

    private function addExpenseClaim($mentorName, $menteeName, $sessionDate) {
        $mentor = User::whereName($mentorName)->first();
        $mentee = Mentee::whereFirstName(explode('-', $menteeName)[0])->whereLastName(explode('-', $menteeName)[1])->first();
        $sessionReport = Report::whereMentorId($mentor->id)->whereMenteeId($mentee->id)->whereSessionDate($sessionDate)->first();

        $expenseClaim = new ExpenseClaim();
        $expenseClaim->mentor_id = $mentor->id;
        $expenseClaim->report_id = $sessionReport->id;
        $expenseClaim->status = 'pending';
        $expenseClaim->save();

        return $expenseClaim;
    }

    private function processExpenseClaim($expenseClaim, $username="admin") {
        $adminUser = User::whereName($username)->whereRole('admin')->first();

        $dt = new DateTime;
        $expenseClaim->status = 'processed';
        $expenseClaim->check_number = '12345';
        $expenseClaim->processed_by_id = $adminUser->id;
        $expenseClaim->processed_at = $dt->format('Y-m-d');
        $expenseClaim->save();
    }

    private function rejectExpenseClaim($expenseClaim, $username="admin") {
        $adminUser = User::whereName($username)->whereRole('admin')->first();

        $dt = new DateTime;
        $expenseClaim->status = 'rejected';
        $expenseClaim->processed_by_id = $adminUser->id;
        $expenseClaim->processed_at = $dt->format('Y-m-d');
        $expenseClaim->save();
    }

}
