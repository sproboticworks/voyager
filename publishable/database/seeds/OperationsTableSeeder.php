<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Operation;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Module;
use TCG\Voyager\Models\MenuItem;
use Illuminate\Support\Str;

class OperationsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     */
    public function run()
    {
        $routeCollection = Route::getRoutes();

        foreach ($routeCollection as $value) {
            if(substr($value->getName(), 0, strlen('voyager')) === 'voyager') {
                Log::info($value->getName());
                $routeParts = explode('.', $value->getName());
                $operationCode = 'OP-';
                $operationName = '';
                if(count($routeParts) == 2) {
                    $operationCode .= 'USER-'.strtoupper($routeParts[count($routeParts)-1]);
                    $operationName .= 'User '.title_case($routeParts[count($routeParts)-1]);
                } else if(count($routeParts) > 2) {
                    if(count($routeParts) == 3) {
                        $operationCode .= strtoupper($routeParts[count($routeParts)-2]).'-'.strtoupper($routeParts[count($routeParts)-1]);
                        $operationName .= title_case($routeParts[count($routeParts)-1].' '.$routeParts[count($routeParts)-2]);
                    }
                    else {
                        $operationCode .= strtoupper($routeParts[count($routeParts)-3]).'-'.strtoupper($routeParts[count($routeParts)-2]).'-'.strtoupper($routeParts[count($routeParts)-1]);                
                        $operationName .= title_case($routeParts[count($routeParts)-1].' '.$routeParts[count($routeParts)-3].' '.$routeParts[count($routeParts)-2]);
                    } 
                } else {
                    $this->command->info("Route '".$value->uri()."' doesn't have proper name '".$value->getName()."'. Skipping this route...");
                    continue;
                }
                $dataType = null;
                $routeAction = $value->getAction();
                if (array_key_exists('data_type', $routeAction)) {
                    $dataType = DataType::where('name', $routeAction['data_type'])->first();
                    if(!$dataType) {
                        $displayName = Str::singular(implode(' ', explode('_', Str::title($routeAction['data_type']))));
                        $dataType = DataType::create([
                            'name'                  => $routeAction['data_type'],
                            'slug'                  => Str::slug($routeAction['data_type']),
                            'display_name'          => $displayName,
                            'display_name_plural'   => Str::plural($displayName),
                            'generate_permissions'  => 1,
                        ]);
                    }
                }
                if(!$dataType) {
                    for ($i=count($routeParts)-2; $i > 0; $i--) { 
                        $dataType = DataType::where('name', $routeParts[i])->first();
                        if($dataType) {
                            break;
                        }
                    }
                }
                $action = null;
                $actionCollection = collect(Operation::ACTIONS);
                if($actionCollection->contains(strtoupper($routeParts[count($routeParts)-1]))) {
                    $action = strtoupper($routeParts[count($routeParts)-1]);
                } else {
                    if (strpos($action, '_') !== false) {
                        $actionParts = collect(explode('_', $action));
                        foreach ($actionParts as $actionPart) {
                            if($actionCollection->contains(strtoupper($actionPart))) {
                                $action = strtoupper($actionPart);
                                break;
                            }
                        }
                    } else {
                        $action = 'SHOW';
                    }
                }

                $dataTypeId = $dataType? $dataType->id : NULL;

                $voyagerModule = Module::firstOrCreate(['code' => 'MD-VOYAGER', 'name' => 'Voyager']);

                $operation = Operation::firstOrNew(['route' => $value->getName()]);
                $operation->fill([
                    'module_id' => $voyagerModule->id,
                    'code' => $operationCode,
                    'name' => $operationName,
                    'data_type_id' => $dataTypeId,
                    'action' => $action,
                ])->save();
            }
        }

        $menuItems = MenuItem::all();
        foreach ($menuItems as $menuItem) {
            $operation = Operation::where('route', $menuItem->route)->first();
            if($operation) {
                $menuItem->operation_id = $operation->id;
                $menuItem->save();
            }
        }
        //$this->command->info(print_r($routeCollection, true));
    }
}
