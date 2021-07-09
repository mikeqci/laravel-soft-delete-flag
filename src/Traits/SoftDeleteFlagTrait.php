<?php
namespace Tsyama\LaravelSoftDeleteFlag\Traits;

use Tsyama\LaravelSoftDeleteFlag\Scopes\SoftDeleteFlagScope;
use Illuminate\Database\Eloquent\SoftDeletes;

trait SoftDeleteFlagTrait
{
    use SoftDeletes;

    public static function bootSoftDeleteFlagTrait()
    {
        static::addGlobalScope(new SoftDeleteFlagScope());
    }

    public static function bootSoftDeletes()
    {
    }

    public function initializeSoftDeletes()
    {
    }

    protected function runSoftDelete()
    {
        $query = $this->setKeysForSaveQuery($this->newModelQuery());

        $time = $this->freshTimestamp();

        $columns = [$this->getDeletedAtColumn() => true];

        $this->{$this->getDeletedAtColumn()} = true;

        if (!$this->getDeletedAtColumn()) {
            $this->{$this->getUpdatedAtColumn()} = $time;

            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }

        $query->update($columns);
    }

    public function restore()
    {
        if ($this->fireModelEvent('restoring') === false) {
            return false;
        }

        $this->{$this->getDeletedAtColumn()} = null;

        $this->exists = true;

        $result = $this->save();

        $this->fireModelEvent('restored', false);

        return $result;
    }
}
