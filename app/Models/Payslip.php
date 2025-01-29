<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payslip extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'basic_salary',
        'deductions',
        'sss_contribution',
        'pagibig_contribution',
        'philhealth_contribution',
        'overtime',
        'net_pay',
        'period_start',
        'period_end',
        'generated_at',
        'employee_id',
        'instance_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'basic_salary' => 'decimal:2',
        'deductions' => 'decimal:2',
        'sss_contribution' => 'decimal:2',
        'pagibig_contribution' => 'decimal:2',
        'philhealth_contribution' => 'decimal:2',
        'overtime' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
        'generated_at' => 'timestamp',
        'employee_id' => 'integer',
        'instance_id' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function instance(): BelongsTo
    {
        return $this->belongsTo(Instance::class);
    }
}
