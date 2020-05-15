<?php
/**
 * This file is part of PayLink plugin for FacturaScripts
 * Copyright (C) 2020 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Plugins\PayLink\Lib\PDF;

use FacturaScripts\Core\Lib\PDF\PDFDocument as PDFParent;
use FacturaScripts\Dinamic\Model\FacturaCliente;

/**
 * Description of PDFDocument
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
abstract class PDFDocument extends PDFParent
{

    /**
     * 
     * @param FacturaCliente $invoice
     */
    protected function insertInvoiceReceipts($invoice)
    {
        $receipts = $invoice->getReceipts();
        if (\count($receipts) > 0) {
            $this->newPage();

            $headers = [
                'numero' => $this->i18n->trans('receipt'),
                'bank' => $this->i18n->trans('payment-method'),
                'importe' => $this->i18n->trans('amount'),
                'vencimiento' => $this->i18n->trans('expiration')
            ];
            $rows = [];
            foreach ($receipts as $receipt) {
                $paylink = $receipt->url('pay');
                $rows[] = [
                    'numero' => $receipt->numero,
                    'bank' => empty($paylink) ? $this->getBankData($receipt) : '<c:alink:' . $paylink . '>'
                    . $this->i18n->trans('pay') . '</c:alink>',
                    'importe' => $this->numberTools->format($receipt->importe),
                    'vencimiento' => $receipt->pagado ? $this->i18n->trans('paid') : $receipt->vencimiento
                ];
            }
            $tableOptions = [
                'cols' => [
                    'numero' => ['justification' => 'center'],
                    'bank' => ['justification' => 'center'],
                    'importe' => ['justification' => 'right'],
                    'vencimiento' => ['justification' => 'right']
                ],
                'shadeCol' => [0.95, 0.95, 0.95],
                'shadeHeadingCol' => [0.95, 0.95, 0.95],
                'width' => $this->tableWidth
            ];
            $this->pdf->ezTable($rows, $headers, '', $tableOptions);
        }
    }
}
