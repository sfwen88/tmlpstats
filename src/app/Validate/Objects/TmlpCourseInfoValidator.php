<?php
namespace TmlpStats\Validate\Objects;

use TmlpStats\Import\Xlsx\ImportDocument\ImportDocument;
use Respect\Validation\Validator as v;

class TmlpCourseInfoValidator extends ObjectsValidatorAbstract
{
    protected $sheetId = ImportDocument::TAB_COURSES;

    protected function populateValidators($data)
    {
        $positiveIntValidator = v::intVal()->min(0, true);

        $types = [
            'Incoming T1',
            'Future T1',
            'Incoming T2',
            'Future T2',
        ];

        $this->dataValidators['type']                   = v::in($types);
        $this->dataValidators['quarterStartRegistered'] = $positiveIntValidator;
        $this->dataValidators['quarterStartApproved']   = $positiveIntValidator;
    }

    protected function validate($data)
    {
        if (!$this->validateQuarterStartValues($data)) {
            $this->isValid = false;
        }

        return $this->isValid;
    }

    public function validateQuarterStartValues($data)
    {
        $isValid = true;

        if ($data->quarterStartApproved > $data->quarterStartRegistered) {
            $this->addMessage('TMLPCOURSE_QSTART_TER_LESS_THAN_QSTART_APPROVED');
            $isValid = false;
        }

        return $isValid;
    }
}
