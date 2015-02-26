<?php
namespace TmlpStats\Validate;

use Carbon\Carbon;
use Respect\Validation\Validator as v;

class CommCourseInfoValidator extends ValidatorAbstract
{
    protected $classDisplayName = 'CAP & CPC Course Info.';

    protected function populateValidators()
    {
        $positiveIntValidator        = v::int()->min(0, true);
        $positiveIntNotNullValidator = v::when(v::nullValue(), v::alwaysInvalid(), $positiveIntValidator);
        $positiveIntOrNullValidator  = v::when(v::nullValue(), v::alwaysValid(), $positiveIntValidator);
        $rowIdValidator              = v::numeric()->positive();

        $types = array('CAP', 'CPC');

        $this->dataValidators['startDate']                  = v::date('Y-m-d');
        $this->dataValidators['type']                       = v::in($types);
        // Skipping center (auto-generated)
        $this->dataValidators['statsReportId']              = $rowIdValidator;

        $this->dataValidators['reportingDate']              = v::date('Y-m-d');
        $this->dataValidators['courseId']                   = $rowIdValidator;
        $this->dataValidators['quarterStartTer']            = $positiveIntNotNullValidator;
        $this->dataValidators['quarterStartStandardStarts'] = $positiveIntNotNullValidator;
        $this->dataValidators['quarterStartXfer']           = $positiveIntNotNullValidator;
        $this->dataValidators['currentTer']                 = $positiveIntNotNullValidator;
        $this->dataValidators['currentStandardStarts']      = $positiveIntNotNullValidator;
        $this->dataValidators['currentXfer']                = $positiveIntNotNullValidator;
        $this->dataValidators['completedStandardStarts']    = $positiveIntOrNullValidator;
        $this->dataValidators['potentials']                 = $positiveIntOrNullValidator;
        $this->dataValidators['registrations']              = $positiveIntOrNullValidator;
        // Skipping quarter (auto-generated)
    }

    protected function validate()
    {
        $this->validateCourseCompletionStats();
        $this->validateCourseStartDate();

        return $this->isValid;
    }

    protected function validateCourseCompletionStats()
    {
        $statsReport = $this->getStatsReport($this->data->statsReportId);
        $startDate = $this->getDateObject($this->data->startDate);
        if ($startDate->lt($statsReport->reportingDate)) {
            if (is_null($this->data->completedStandardStarts)) {
                $this->addMessage("Course has completed but is missing Standard Starts Completed", 'error');
                $this->isValid = false;
            }
            if (is_null($this->data->potentials)) {
                $this->addMessage("Course has completed but is missing Potentials", 'error');
                $this->isValid = false;
            }
            if (is_null($this->data->registrations)) {
                $this->addMessage("Course has completed but is missing Registrations", 'error');
                $this->isValid = false;
            }
        }
    }

    protected function validateCourseStartDate()
    {
        $statsReport = $this->getStatsReport($this->data->statsReportId);
        $startDate = $this->getDateObject($this->data->startDate);
        if ($startDate->lt($statsReport->quarter->startWeekendDate)) {
            $this->addMessage("Course occured before quarter started", 'error');
            $this->isValid = false;
        }
    }
}