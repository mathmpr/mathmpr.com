<?php

namespace App\Override;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Exception;

class OverrideBuilder extends Builder
{
    /**
     * @param $column
     * @param null $value
     * @param string $boolean
     * @param bool $not
     * @return Builder
     * @throws Exception
     */
    public function whereTranslationExists($column, $value = null, string $boolean = 'and', bool $not = false): Builder
    {
        if (is_array($column)) {
            $key = array_keys($column)[0];
            if (is_numeric($key)) {
                throw new Exception(
                    'When pass array as $column, the key can\'t be numeric. Key have to be "column" => "value"'
                );
            }
            $value = $column[$key];
            $column = $key;
        }
        $sql = DB::table('translates')
            ->select(DB::raw(1))
            ->whereColumn('translates.object_id', $this->model->getTable() . '.id')
            ->whereColumn('translates.object_class', str_replace('\\', '/', get_class($this->model)))
            ->whereColumn('translates.field', $column)
            ->whereColumn('translates.value', $value)
            ->toSql();
        return $this->whereRaw(($not ? 'not' : '') . ' exists (' .  $sql . ')', [], $boolean);
    }

}
