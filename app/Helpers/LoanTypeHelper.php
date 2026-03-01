<?php

/**
 * Get all available loan types
 */
function getLoanTypes(): array
{
    return [
        'cash' => 'Cash',
        'swine' => 'Swine',
        'goat' => 'Goat',
        'fertilizers' => 'Fertilizers',
    ];
}

/**
 * Get loan type label
 */
function getLoanTypeLabel(string $type): string
{
    return getLoanTypes()[$type] ?? ucfirst(str_replace('_', ' ', $type));
}

/**
 * Get loan type descriptions
 */
function getLoanTypeDescription(string $type): string
{
    $descriptions = [
        'cash' => 'Cash loan for general purposes',
        'swine' => 'Loan for swine raising and livestock',
        'goat' => 'Loan for goat raising and livestock',
        'fertilizers' => 'Loan for agricultural fertilizers and supplies',
    ];

    return $descriptions[$type] ?? '';
}

/**
 * Get loan type color for UI
 */
function getLoanTypeColor(string $type): string
{
    $colors = [
        'cash' => '#2ecc71',
        'swine' => '#e74c3c',
        'goat' => '#f39c12',
        'fertilizers' => '#27ae60',
    ];

    return $colors[$type] ?? '#2ecc71';
}

/**
 * Get loan type icon for UI
 */
function getLoanTypeIcon(string $type): string
{
    $icons = [
        'cash' => 'fa-money-bill-wave',
        'swine' => 'fa-piggy-bank',
        'goat' => 'fa-cow',
        'fertilizers' => 'fa-flask',
    ];

    return $icons[$type] ?? 'fa-tag';
}
