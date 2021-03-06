<?php

namespace App\Model\Accounting;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $connection = 'tenant';

    protected $table = 'chart_of_accounts';

    /**
     * Get the type that owns the chart of account.
     */
    public function type()
    {
        return $this->belongsTo(get_class(new ChartOfAccountType()), 'type_id');
    }

    /**
     * Get the type that owns the chart of account.
     */
    public function group()
    {
        return $this->belongsTo(get_class(new ChartOfAccountGroup()), 'group_id');
    }

    public function journals($date)
    {
        return $this->hasMany(get_class(new Journal()), 'chart_of_account_id')->where('date', '<=', $date);
    }

    public function totalDebit($date)
    {
        return $this->journals($date)->sum('debit');
    }

    public function totalCredit($date)
    {
        return $this->journals($date)->sum('credit');
    }

    public function total($date)
    {
        if ($this->type->is_debit) {
            return $this->totalDebit($date) - $this->totalCredit($date);
        }

        return $this->totalCredit($date) - $this->totalDebit($date);
    }
}
