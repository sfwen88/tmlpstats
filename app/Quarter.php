<?php
namespace TmlpStats;

use Illuminate\Database\Eloquent\Model;
use Eloquence\Database\Traits\CamelCaseModel;
use Carbon\Carbon;

class Quarter extends Model {

    use CamelCaseModel;

    protected $fillable = array(
        'start_weekend_date',
        'end_weekend_date',
        'classroom1_date',
        'classroom2_date',
        'classroom3_date',
        'location',
        'distinction',
        'global_region',
        'local_region',
    );

    protected $dates = [
        'start_weekend_date',
        'end_weekend_date',
        'classroom1_date',
        'classroom2_date',
        'classroom3_date',
    ];

    public static function findByDateAndRegion($date, $region = null)
    {
        $query = static::where('start_weekend_date', '<', $date->toDateString())
                       ->where('end_weekend_date', '>=', $date->toDateString());
        if ($region !== null) {
            $query->where('global_region', $region);
        }
        return $query->first();
    }

    public function scopePresentAndFuture($query)
    {
        return $query->where('end_weekend_date', '>=', Carbon::now()->startOfDay());
    }

    public function scopeCurrent($query, $region = null)
    {
        $query = $query->where('start_weekend_date', '<', Carbon::now()->startOfDay())
                       ->where('end_weekend_date', '>=', Carbon::now()->startOfDay());

        if ($region !== null) {
            $query = $query->where('global_region', $region);
        }

        return $query;
    }

    public function programTeamMember()
    {
        return $this->hasMany('TmlpStats\ProgramTeamMember')->withTimestamps();
    }

    public function statsReport()
    {
        return $this->hasMany('TmlpStats\StatsReport')->withTimestamps();
    }
}
