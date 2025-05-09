<?php

namespace Totocsa\UniqueMultiple;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Validation\Rules\DatabaseRule;

class UniqueMultiple implements ValidationRule
{
    use Conditionable, DatabaseRule;
    protected $request;
    protected string $tableName;
    protected array $attributes;
    protected array $ignores;

    public function __construct(Request $request, string $table, array $attributes, array $ignores = [])
    {
        $this->request = $request;
        $this->tableName = $table;
        $this->attributes = $attributes;
        $this->ignores = $ignores;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $attributes = [];
        foreach ($this->attributes as $v) {
            $attributes[$v] = $this->request->{$v};
        }

        $ignores = [];
        foreach ($this->ignores as $v) {
            $ignores[$v] = $this->request->{$v};
        }

        $query = DB::table($this->tableName);

        // Mezők hozzáadása a lekérdezéshez
        foreach ($attributes as $column => $val) {
            $query->where($column, '=', $val);
        }

        // Kizárt rekordok hozzáadása
        foreach ($ignores as $column => $val) {
            $query->where($column, '!=', $val);
        }

        if ($query->exists()) {
            $fail($this->message());
        }
    }

    public function message()
    {
        return 'The combination of values must be unique.';
    }
}
