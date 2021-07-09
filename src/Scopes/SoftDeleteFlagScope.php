<?php

namespace Tsyama\LaravelSoftDeleteFlag\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SoftDeleteFlagScope extends SoftDeletingScope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where(function($q) use($model){
            $q->where($model->getQualifiedDeletedAtColumn(), false)
                ->orWhereNull($model->getQualifiedDeletedAtColumn());
        });
    }

    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }

        $builder->onDelete(function (Builder $builder) {
            $column = $this->getDeletedAtColumn($builder);

            return $builder->update([
                $column => true,
            ]);
        });
    }

    protected function addRestore(Builder $builder)
    {
        $builder->macro('restore', function (Builder $builder) {
            $builder->withTrashed();

            return $builder->update([$builder->getModel()->getDeletedAtColumn() => null]);
        });
    }

    protected function addWithoutTrashed(Builder $builder)
    {
        $builder->macro('withoutTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder
                ->withoutGlobalScope($this)
                ->where($model->getQualifiedDeletedAtColumn(), null);

            return $builder;
        });
    }

    protected function addOnlyTrashed(Builder $builder)
    {
        $builder->macro('onlyTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder
                ->withoutGlobalScope($this)
                ->where($model->getQualifiedDeletedAtColumn(), true);

            return $builder;
        });
    }
}
