<?php

namespace App\Repository;

use App\Helpers\EncryptHelper;
use Exception;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CryptographyRepository
{
    protected $appModels = [];

    public function __construct()
    {
        $models = collect(File::allFiles(app_path()))
        ->map(function ($item) {
            $path = $item->getRelativePathName();
            $class = sprintf('\%s%s',
                Container::getInstance()->getNamespace(),
                strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));

            return $class;
        })->filter(function ($class) {
            $valid = false;

            if (class_exists($class)) {
                $reflection = new \ReflectionClass($class);
                $valid = $reflection->isSubclassOf(Model::class) &&
                    !$reflection->isAbstract();
            }

            return $valid;
        });

        $this->appModels = $models;
    }

    public function decrypt ()
    {
        foreach ($this->appModels as $model) {
            $instance = app($model);
            if (isset($instance->encryptedAttributes)) {
                $connection = DB::table($instance->getTable());
                $objects = $connection->select('*')->get();

                foreach ($objects as $obj) {
                    try {
                        DB::beginTransaction();

                        $cypherData = collect($obj)->only($instance->encryptedAttributes)->all();

                        $values = [];
                        foreach ($cypherData as $key => $value) {
                            $decrypted = EncryptHelper::decrypt($value);
                            if ($decrypted !== false) $values[$key] = $decrypted;
                        }

                        if (sizeof($values)) {
                            $updateQuery = $connection;
                            $updateQuery->wheres = null;
                            $updateQuery->bindings['where'] = [];
                            $updateQuery->where('id', $obj->id)->update($values);
                        }

                        DB::commit();
                    } catch (Exception $e) {
                        report($e);
                        DB::rollBack();
                    }
                }
            }
        }
    }

    public function encrypt ()
    {
        foreach ($this->appModels as $model) {
            $instance = app($model);
            if (isset($instance->encryptedAttributes)) {
                $connection = DB::table($instance->getTable());
                $objects = $connection->select('*')->get();

                foreach ($objects as $obj) {
                    try {
                        DB::beginTransaction();

                        $cypherData = collect($obj)->only($instance->encryptedAttributes)->all();

                        $values = [];
                        foreach ($cypherData as $key => $value) {
                            if ($value) {
                                if (!EncryptHelper::decrypt($value)) {
                                    $values[$key] = EncryptHelper::encrypt($value);
                                }
                            }
                        }

                        if (sizeof($values)) {
                            $updateQuery = $connection;
                            $updateQuery->wheres = null;
                            $updateQuery->bindings['where'] = [];
                            $updateQuery->where('id', $obj->id)->update($values);
                        }

                        DB::commit();
                    } catch (Exception $e) {
                        report($e);
                        DB::rollBack();
                    }
                }
            }
        }
    }
}
