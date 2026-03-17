<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function updateStatusBasedOnUsage()
    {
        $hasTransactions = $this->transactions()->exists();
        $this->status = $hasTransactions ? 'active' : 'inactive';
        $this->save();
    }
}
