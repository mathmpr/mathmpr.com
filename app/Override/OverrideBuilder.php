<?php

namespace App\Override;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
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
        $objectClass = str_replace('\\', '/', get_class($this->model));
        $sql = DB::table('translates')
            ->select(DB::raw(1))
            ->whereColumn('translates.object_id', $this->model->getTable() . '.id')
            ->where('translates.object_class', $objectClass)
            ->where('translates.field', $column)
            ->where('translates.value', $value);

        return $this->whereRaw(($not ? 'not' : '') . ' exists (' .  static::toSqlWithBindings($sql) . ')', [], $boolean);
    }

    public static function toSqlWithBindings(QueryBuilder $query): string
    {
        $bindings = $query->getBindings();
        $sql = $query->toSql();
        while(count($bindings) > 0) {
            $binding = array_shift($bindings);
            $sql = preg_replace('/\?/', is_numeric($binding) ? $binding : "'".$binding."'" , $sql, 1);
        }
        return $sql;
    }

}
