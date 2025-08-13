<?php
namespace App\Keuangan\Controllers;

use App\Core\Controller;

final class InvoiceController extends Controller
{
    public function gaji(): void
    {
        $this->setPageTitle('Keuangan - Invoice Gaji');
        $this->render('Keuangan', 'invoice/gaji', [
            'title' => 'Invoice Gaji',
        ]);
    }
}