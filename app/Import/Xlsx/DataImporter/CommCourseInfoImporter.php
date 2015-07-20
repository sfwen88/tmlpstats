<?php
namespace TmlpStats\Import\Xlsx\DataImporter;

use TmlpStats\Import\Xlsx\ImportDocument\ImportDocument;
use TmlpStats\Course;
use TmlpStats\CourseData;
use TmlpStats\Util;

use Carbon\Carbon;

class CommCourseInfoImporter extends DataImporterAbstract
{
    protected $sheetId = ImportDocument::TAB_COURSES;

    protected $blockCAP = array();
    protected $blockCPC = array();

    protected function populateSheetRanges()
    {
        $this->blockCAP[] = $this->excelRange('A','O');
        $this->blockCAP[] = $this->excelRange(5,14);

        $this->blockCPC[] = $this->excelRange('A','O');
        $this->blockCPC[] = $this->excelRange(18,25);
    }

    protected function load()
    {
        $this->reader = $this->getReader($this->sheet);

        $this->loadBlock($this->blockCAP, 'CAP');
        $this->loadBlock($this->blockCPC, 'CPC');
    }

    protected function loadEntry($row, $type)
    {
        if ($this->reader->isEmptyCell($row,'B')) return;

        $this->data[] = array(
            'type'                       => $type,
            'offset'                     => $row,
            'startDate'                  => $this->reader->getStartDate($row),
            'quarterStartTer'            => $this->reader->getQuarterStartTer($row),
            'quarterStartStandardStarts' => $this->reader->getQuarterStartStandardStarts($row),
            'quarterStartXfer'           => $this->reader->getQuarterStartXfer($row),
            'currentTer'                 => $this->reader->getCurrentTer($row),
            'currentStandardStarts'      => $this->reader->getCurrentStandardStarts($row),
            'currentXfer'                => $this->reader->getCurrentXfer($row),
            'completedStandardStarts'    => $this->reader->getCompletedStandardStarts($row),
            'potentials'                 => $this->reader->getPotentials($row),
            'registrations'              => $this->reader->getRegistrations($row),
        );
    }

    public function postProcess()
    {
        foreach ($this->data as $courseInput) {

            $course = Course::firstOrNew(array(
                'center_id'  => $this->statsReport->center->id,
                'start_date' => $courseInput['startDate'],
                'type'       => $courseInput['type'],
            ));
            if ($course->statsReportId === null) {
                $course->statsReportId = $this->statsReport->id;
                $course->save();
            }

            $courseData = CourseData::firstOrNew(array(
                'center_id'       => $this->statsReport->center->id,
                'quarter_id'      => $this->statsReport->quarter->id,
                'course_id'       => $course->id,
                'reporting_date'  => $this->statsReport->reportingDate->toDateString(),
                'stats_report_id' => $this->statsReport->id,
            ));

            unset($courseInput['startDate']);
            unset($courseInput['type']);

            $courseData = $this->setValues($courseData, $courseInput);
            $courseData->save();
        }
    }
}
