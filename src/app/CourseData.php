<?php
namespace TmlpStats;

class CourseData extends ModelCachedRelationships
{
    protected $table = 'courses_data';

    protected $fillable = [
        'stats_report_id',
        'course_id',
        'quarter_start_ter',
        'quarter_start_standard_starts',
        'quarter_start_xfer',
        'current_ter',
        'current_standard_starts',
        'current_xfer',
        'completed_standard_starts',
        'potentials',
        'registrations',
    ];

    public function __get($name)
    {
        switch ($name) {
            case 'center':
                return $this->course->$name;
            default:
                return parent::__get($name);
        }
    }

    public function scopeByStatsReport($query, $statsReport)
    {
        return $query->whereStatsReportId($statsReport->id);
    }

    public function statsReport()
    {
        return $this->belongsTo('TmlpStats\StatsReport');
    }

    public function course()
    {
        return $this->belongsTo('TmlpStats\Course');
    }
}
