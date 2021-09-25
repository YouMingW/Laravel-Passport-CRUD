<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Employee;

class EmployeeTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(App\Models\Employee $employee)
    {
        return [
            //
            'empname' => $employee->empname,
            'designation' => $employee->designation,
        ];
    }
}
