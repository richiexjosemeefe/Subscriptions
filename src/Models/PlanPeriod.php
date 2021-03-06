<?php

namespace Emeefe\Subscriptions\Models;

use Illuminate\Database\Eloquent\Model;
use Emeefe\Subscriptions\Contracts\PlanPeriodInterface;

class PlanPeriod extends Model implements PlanPeriodInterface{
    public const UNIT_DAY = 'day';
    public const UNIT_MONTH = 'month';
    public const UNIT_YEAR = 'year';
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('subscriptions.tables.plan_periods'));
    }

    public function plan() {
        return $this->belongsTo(config('subscriptions.models.plan'), 'plan_id');
    }

    public function subscriptions() {
        return $this->hasMany(config('subscriptions.models.subscription'), 'period_id');
    }

    public function scopeVisible($query) {
        return $query->where('is_visible', 1);
    }

    public function scopeHidden($query) {
        return $query->where('is_visible', 0);
    }

    public function isRecurring() {
        return $this->is_recurring;
    }

    public function isLimitedNonRecurring() {
        return !$this->is_recurring && $this->period_count != null;
    }

    public function isUnlimitedNonRecurring() {
        return !$this->is_recurring && $this->period_count == null;
    }

    public function isVisible() {
        return $this->is_visible;
    }

    public function isHidden() {
        return !$this->is_visible;
    }

    public function isDefault() {
        if ($this->is_default) {
            return true;
        }
        return false;
        // return $this->is_default;
    }

    public function isFree() {
        return $this->price == 0;
    }

    public function hasTrial() {
        return $this->trial_days != 0;
    }

    public function setAsVisible() {
        $this->is_visible = true;
        $this->save();
    }

    public function setAsHidden() {
        $this->is_visible = false;
        $this->save();
    }

    public function setAsDefault() {
        $this->is_default = true;
        $this->save();
    }
}