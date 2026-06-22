<?php

namespace App\Services;

use App\Models\Contract;
use PhpOffice\PhpWord\TemplateProcessor;

class ContractGeneratorService
{
    public static function generate(
        Contract $contract
    )
    {
        $templatePath = storage_path(
            'app/templates/contract_template.docx'
        );

        if (!file_exists($templatePath)) {

            throw new \Exception(
                'Template tidak ditemukan: '
                . $templatePath
            );
        }

        $template = new TemplateProcessor(
            $templatePath
        );

        $template->setValue(
            'contract_number',
            $contract->contract_number
        );

        $template->setValue(
            'contract_name',
            $contract->contract_name
        );

        $template->setValue(
            'customer_address',
            $contract->customer_address
        );

        $directory = storage_path(
            'app/contracts'
        );

        if (!is_dir($directory)) {

            mkdir(
                $directory,
                0755,
                true
            );
        }

        $filename =
            'CONTRACT-' .
            $contract->id .
            '.docx';

        $fullPath =
            $directory .
            DIRECTORY_SEPARATOR .
            $filename;

        $template->saveAs($fullPath);

        return [
            'filename' => $filename,
            'path' => 'contracts/' . $filename,
        ];
    }
}