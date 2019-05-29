<?php

use Illuminate\Database\Seeder;

class ExpenseClaimTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // mentor 1
        $this->addExpenseClaim(1, 12, 1, 'rejected', 1);
        $this->addExpense(1, 5.10);
        $this->addExpense(1, 6.10);

        $this->addExpenseClaim(2, 12, 1, 'processed', 1);
        $this->addExpense(2, 6.10);

        // mentor 2
        $this->addExpenseClaim(3, 13, 2, 'rejected', 1);
        $this->addExpense(3, 5.10);

        $this->addExpenseClaim(4, 13, 2, 'pending', null);
        $this->addExpense(4, 5.10);
        $this->addExpense(4, 7.10);

        // mentor 3
        $this->addExpenseClaim(5, 14, 3, 'rejected', 1);
        $this->addExpense(5, 5.10);

        $this->addExpenseClaim(6, 14, 3, 'processed', 1);
        $this->addExpense(6, 5.10);
    }


    private function addExpense($expense_claim_id, $amount) {
        $dt = new DateTime;
        DB::table('expenses')->insert([
            'expense_claim_id' => $expense_claim_id,
            'date' => $dt->format('Y-m-d'),
            'description' => "Some expense item",
            'amount' => $amount,
            'created_at' => $dt->format('Y-m-d')
        ]);
    }

    private function addExpenseClaim($id, $mentor_id, $report_id, $status, $processed_by_id) {
        $dt = new DateTime;
        DB::table('expense_claims')->insert([
            'id' => $id,
            'mentor_id' => $mentor_id,
            'report_id' => $report_id,
            'status' => $status,
            'created_at' => $dt->format('Y-m-d'),
            'processed_by_id' => $processed_by_id,
            'processed_at' => $dt->format('Y-m-d')
        ]);
    }

}
