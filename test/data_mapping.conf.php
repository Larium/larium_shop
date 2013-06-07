<?php

return array(
    'Larium\\Store\\Product' => array(
        'variants' => array(
            'type'  => 'HasMany',
            'class' => 'Larium\\Store\\Variant',
            'inverse' => 'product',
        )
    ),
    'Larium\\Store\\Variant' => array(
        'product' => array(
            'type'  => 'BelongsTo',
            'class' => 'Larium\\Store\\Product'
        )
    )
);
