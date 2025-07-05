<?php

namespace App\Traits;

trait TagValidationRules
{
    /**
     * Get the validation rules for tags.
     *
     * @return array
     */
    protected function tagRules(): array
    {

        // existing tags
        // [x] array name, id
        // [x] unique id
        // [x] exists in db

        // new tags
        // [x] array full of strings
        // [x] must be all unique
        // [x] must have unique db names 

        
        return [
            'newTags' => 'nullable|array',
            'newTags.*' => ["string", "max:100", "unique:tags,name", "distinct:ignore_case"],
            'existingTags' => 'nullable|array',
            'existingTags.*.id' => ['exists:tags,id', 'distinct'],
        ];
    }
}