<?php
namespace TmlpStats;

use Illuminate\Database\Eloquent\Model;
use Eloquence\Database\Traits\CamelCaseModel;

class TmlpGameData extends Model {

    use CamelCaseModel;

    protected $table = 'tmlp_games_data';

    protected $fillable = [
        'center_id',
        'quarter_id',
        'reporting_date',
        'tmlp_game_id',
        'offset',
        'quarter_start_registered',
        'quarter_start_approved',
        'stats_report_id',
    ];

    protected $dates = [
        'reporting_date',
    ];

    public function setReportingDateAttribute($value)
    {
        $date = $this->asDateTime($value);
        $this->attributes['reporting_date'] = $date->toDateString();
    }

    public function center()
    {
        return $this->belongsTo('TmlpStats\Center');
    }

    public function quarter()
    {
        return $this->belongsTo('TmlpStats\Quarter');
    }

    public function game()
    {
        return $this->belongsTo('TmlpStats\TmlpGame');
    }
}