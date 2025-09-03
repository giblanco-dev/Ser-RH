<?php 

require '../../../lib/xlsx/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

use PhpOffice\PhpSpreadsheet\Style\Fill;


$styleArray = []; 

$styleArray_he1 = [
    'font' => [
        'bold' => true, // Negrita
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'B6D7A8', // Color de fondo
        ],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN, // Opciones: THIN, MEDIUM, THICK, etc.
            'color' => ['argb' => 'FF000000'],   // Negro
        ],
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ]
];

$styleArray_he2 = [
    'font' => [
        'bold' => true, // Negrita
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '9BC2E6', // Color de fondo
        ],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN, // Opciones: THIN, MEDIUM, THICK, etc.
            'color' => ['argb' => 'FF000000'],   // Negro
        ],
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ]
];

$styleArray_he3 = [
    'font' => [
        'bold' => true, // Negrita
        'color' => [
            'rgb' => 'FFFFFF', // 👈 Texto en blanco
        ],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '203764', // 👈 Fondo azul
        ],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN, // Opciones: THIN, MEDIUM, THICK, etc.
            'color' => ['argb' => 'FF000000'],   // Negro
        ],
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ]
];


$styleRightBorder = [
    'borders' => [
        'right' => [
            'borderStyle' => Border::BORDER_THICK, // Opciones: THIN, MEDIUM, THICK
            'color' => ['argb' => 'FF000000'],     // Negro
        ],
    ],
];


$styleLeftBorder = [
    'borders' => [
        'left' => [
            'borderStyle' => Border::BORDER_THICK,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];


$styleArray_colab = [
    'font' => [
        'bold' => true
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'F2F2F2', // 👈 Fondo gris
        ],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN, // Opciones: THIN, MEDIUM, THICK, etc.
            'color' => ['argb' => 'FF000000'],   // Negro
        ],
    ]
];

$styleArray_asis = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN, // Opciones: THIN, MEDIUM, THICK, etc.
            'color' => ['argb' => 'FF000000'],   // Negro
        ],
    ]
];

$styleArray_noLab = [
    'font' => [
        'bold' => true
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => 'FFFF00', // 👈 Fondo amarillo
        ],
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN, // Opciones: THIN, MEDIUM, THICK, etc.
            'color' => ['argb' => 'FF000000'],   // Negro
        ],
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ]
];

$styleArray_titul = [
    'font' => [
        'bold' => true,
        'size' => 14 // 👈 Tamaño de fuente
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ]
];

?>