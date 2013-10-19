<?php

return array(
    'Larium\\Shop\\Store\\Product' => array(
        'variants' => array(
            'type'  => 'HasMany',
            'class' => 'Larium\Shop\\Store\\Variant',
            'inverse' => 'product',
        )
    ),
    'Larium\\Shop\\Store\\Variant' => array(
        'product' => array(
            'type'  => 'BelongsTo',
            'class' => 'Larium\Shop\\Store\\Product'
        )
    )
);
