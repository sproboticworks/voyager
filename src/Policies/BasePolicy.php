<?php

namespace TCG\Voyager\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use TCG\Voyager\Contracts\User;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\Operation;

class BasePolicy
{
    use HandlesAuthorization;

    protected static $datatypes = [];

    /**
     * Handle all requested permission checks.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return bool
     */
    public function __call($name, $arguments)
    {
        if (count($arguments) < 2) {
            throw new \InvalidArgumentException('not enough arguments');
        }
        /** @var \TCG\Voyager\Contracts\User $user */
        $user = $arguments[0];

        /** @var $model */
        $model = $arguments[1];

        return $this->checkPermission($user, $model, $name);
    }

    /**
     * Check if user has an associated permission.
     *
     * @param \TCG\Voyager\Contracts\User $user
     * @param object                      $model
     * @param string                      $action
     *
     * @return bool
     */
    protected function checkPermission(User $user, $model, $action)
    {
        if (!isset(self::$datatypes[get_class($model)])) {
            $dataType = Voyager::model('DataType');
            self::$datatypes[get_class($model)] = $dataType->where('model_name', get_class($model))->first();
        }

        $dataType = self::$datatypes[get_class($model)];

        $hasPermission = false;
        $operationAction = $this->convertBreadActionToOperationAction($action);

        $operation = Operation::where('data_type_id', $dataType->id)
                                ->where('action', $operationAction)->first();
        if($operation) {
            $hasPermission = $user->can('do', $operation);
        }
        //if(!$hasPermission)
            //$hasPermission = $user->hasPermission($action.'_'.$dataType->name);

        return $hasPermission;
    }

    public function convertBreadActionToOperationAction($breadAction) {
        $breadOperationActionMap = array('BROWSE'=>'INDEX', 'READ'=>'SHOW', 'ADD'=>'STORE');
        $operationAction = strtoupper($breadAction);
        if(array_key_exists(strtoupper($breadAction), $breadOperationActionMap)) {
            $operationAction = $breadOperationActionMap[strtoupper($breadAction)];
        } 
        return $operationAction;
    }
}
